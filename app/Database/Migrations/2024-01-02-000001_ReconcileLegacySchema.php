<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Brings a database built before this project had migrations up to the schema
 * the code now expects.
 *
 * The original application shipped no migrations at all, so every real install
 * has a hand-built schema that has drifted from the models. CreateCoreSchema
 * creates tables with IF NOT EXISTS, which is right for a fresh database but
 * means it silently skips tables that already exist — leaving the drift in
 * place. This migration reconciles the differences that actually break the
 * application.
 *
 * Every step checks the current state first, so this is a no-op on a database
 * created by CreateCoreSchema and safe to run more than once.
 */
class ReconcileLegacySchema extends Migration
{
    public function up()
    {
        $this->fixAssignmentUpload();
        $this->fixAssignmentDueDate();
    }

    public function down()
    {
        // Deliberately not reversible: this repairs a broken schema, and
        // recreating the broken shape would only break the application again.
    }

    /**
     * The legacy `assignment_upload` table describes an assignment rather than
     * a submission: it has a title, a description, a class and a subject, but
     * no link to the assignment it answers and no link to the student who
     * handed it in. Every column is also NOT NULL with no default, so an insert
     * from the current code fails twice over — once on the missing columns, and
     * once on the legacy ones it does not populate.
     *
     * Student submissions and the student profile page both query this table,
     * so on a legacy database they fail outright.
     */
    private function fixAssignmentUpload(): void
    {
        if (! $this->db->tableExists('assignment_upload')) {
            return;
        }

        $hasLink = $this->db->fieldExists('assignment_post_id', 'assignment_upload')
            && $this->db->fieldExists('student_id', 'assignment_upload');

        $legacyColumns = array_filter(
            ['assignment_upload_title', 'assignment_upload_description', 'teacher_id', 'class_id', 'subject_id'],
            fn (string $column): bool => $this->db->fieldExists($column, 'assignment_upload')
        );

        if ($hasLink && $legacyColumns === []) {
            return; // Already correct.
        }

        $rows = (int) $this->db->table('assignment_upload')->countAllResults();

        if ($rows === 0) {
            // Nothing to preserve, so rebuild it cleanly rather than patching
            // a table that describes the wrong thing.
            $this->forge->dropTable('assignment_upload', true);
            $this->createAssignmentUpload();

            return;
        }

        // There is data, so keep it and widen the table instead. The legacy
        // columns are left in place — they may hold something the school
        // cares about — but are made nullable so inserts stop failing.
        $additions = [];

        if (! $this->db->fieldExists('assignment_post_id', 'assignment_upload')) {
            $additions['assignment_post_id'] = ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true];
        }

        if (! $this->db->fieldExists('student_id', 'assignment_upload')) {
            $additions['student_id'] = ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true];
        }

        if ($additions !== []) {
            $this->forge->addColumn('assignment_upload', $additions);
        }

        foreach ($legacyColumns as $column) {
            $this->makeNullable('assignment_upload', $column);
        }
    }

    private function createAssignmentUpload(): void
    {
        $this->forge->addField([
            'assignment_upload_id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'assignment_post_id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'student_id'                        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'assignment_upload_file'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'assignment_upload_remarks'         => ['type' => 'TEXT', 'null' => true],
            'assignment_upload_grades'          => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'assignment_upload_received_grades' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'admin_id'                          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'created_at'                        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'                        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('assignment_upload_id', true);
        $this->forge->addKey(['assignment_post_id', 'student_id']);
        $this->forge->createTable('assignment_upload', true);
    }

    /**
     * The due date is read as a date: it is ordered by, compared against
     * today, and rendered. A legacy install stores it as VARCHAR(255)
     * alongside values written by the old `date('d-m-y h:i:s')` code, which do
     * not sort chronologically as strings.
     */
    private function fixAssignmentDueDate(): void
    {
        if (! $this->db->tableExists('assignment_post')
            || ! $this->db->fieldExists('assignment_post_due_date', 'assignment_post')) {
            return;
        }

        if ($this->columnType('assignment_post', 'assignment_post_due_date') === 'date') {
            return; // Already correct.
        }

        // Anything that is not already an ISO date cannot be interpreted
        // reliably, so it becomes NULL — which the application renders as
        // "No due date" rather than as a wrong one.
        $this->db->query(
            "UPDATE assignment_post
                SET assignment_post_due_date = NULL
              WHERE assignment_post_due_date IS NOT NULL
                AND assignment_post_due_date NOT REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}'"
        );

        $this->db->query(
            'UPDATE assignment_post
                SET assignment_post_due_date = LEFT(assignment_post_due_date, 10)
              WHERE assignment_post_due_date IS NOT NULL'
        );

        $this->forge->modifyColumn('assignment_post', [
            'assignment_post_due_date' => [
                'name'       => 'assignment_post_due_date',
                'type'       => 'DATE',
                'null'       => true,
            ],
        ]);
    }

    /**
     * Relax a NOT NULL column, preserving its declared type.
     */
    private function makeNullable(string $table, string $column): void
    {
        foreach ($this->db->getFieldData($table) as $field) {
            if ($field->name !== $column) {
                continue;
            }

            if (isset($field->nullable) && $field->nullable) {
                return; // Already nullable.
            }

            $definition = ['name' => $column, 'null' => true];

            // getFieldData() reports e.g. "varchar(255)" or "int(11)".
            if (preg_match('/^(?<type>[a-z]+)(\((?<length>\d+)\))?/i', (string) $field->type, $m) === 1) {
                $definition['type'] = strtoupper($m['type']);

                if (isset($m['length']) && $m['length'] !== '') {
                    $definition['constraint'] = (int) $m['length'];
                }
            } else {
                $definition['type'] = 'VARCHAR';
                $definition['constraint'] = 255;
            }

            $this->forge->modifyColumn($table, [$column => $definition]);

            return;
        }
    }

    /**
     * Lower-cased base type of a column, e.g. "date" or "varchar".
     */
    private function columnType(string $table, string $column): string
    {
        foreach ($this->db->getFieldData($table) as $field) {
            if ($field->name === $column) {
                return strtolower(preg_replace('/\(.*$/', '', (string) $field->type) ?? '');
            }
        }

        return '';
    }
}
