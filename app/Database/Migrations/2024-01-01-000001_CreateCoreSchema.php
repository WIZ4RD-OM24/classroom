<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Initial schema for the Classroom application.
 *
 * The repository previously shipped with no migrations at all, so the database
 * had to be reconstructed by hand. This migration is the authoritative source
 * of truth for the schema; table and column names match the ones the models
 * already expected so that existing installations stay compatible.
 */
class CreateCoreSchema extends Migration
{
    public function up()
    {
        $this->createAdmin();
        $this->createClass();
        $this->createTeacher();
        $this->createSubject();
        $this->createStudent();
        $this->createNotice();
        $this->createClasswork();
        $this->createAssignmentPost();
        $this->createAssignmentUpload();
        $this->createTimeTable();
    }

    public function down()
    {
        // Reverse order so foreign keys unwind cleanly.
        foreach ([
            'time_table',
            'assignment_upload',
            'assignment_post',
            'classwork',
            'notice',
            'student',
            'subject',
            'teacher',
            'class',
            'admin',
        ] as $table) {
            $this->forge->dropTable($table, true);
        }
    }

    private function timestamps(): array
    {
        return [
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];
    }

    private function createAdmin(): void
    {
        $this->forge->addField([
            'admin_id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'admin_name'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'admin_email'        => ['type' => 'VARCHAR', 'constraint' => 150],
            'admin_password'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'admin_mobile'       => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'admin_image'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'admin_designation'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'admin_organisation' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
        ] + $this->timestamps());
        $this->forge->addKey('admin_id', true);
        $this->forge->addUniqueKey('admin_email');
        $this->forge->createTable('admin', true);
    }

    private function createClass(): void
    {
        $this->forge->addField([
            'class_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'class_name'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'section_name' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'admin_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ] + $this->timestamps());
        $this->forge->addKey('class_id', true);
        $this->forge->addKey('admin_id');
        $this->forge->addForeignKey('admin_id', 'admin', 'admin_id', '', 'CASCADE');
        $this->forge->createTable('class', true);
    }

    private function createTeacher(): void
    {
        $this->forge->addField([
            'teacher_id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'teacher_name'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'teacher_email'        => ['type' => 'VARCHAR', 'constraint' => 150],
            'teacher_password'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'teacher_mobile'       => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'teacher_image'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'teacher_organisation' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'class_id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'admin_id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ] + $this->timestamps());
        $this->forge->addKey('teacher_id', true);
        $this->forge->addUniqueKey('teacher_email');
        $this->forge->addKey('admin_id');
        $this->forge->addForeignKey('admin_id', 'admin', 'admin_id', '', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'class', 'class_id', '', 'SET NULL');
        $this->forge->createTable('teacher', true);
    }

    private function createSubject(): void
    {
        $this->forge->addField([
            'subject_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'subject_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'class_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'teacher_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'admin_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ] + $this->timestamps());
        $this->forge->addKey('subject_id', true);
        $this->forge->addKey('admin_id');
        $this->forge->addForeignKey('admin_id', 'admin', 'admin_id', '', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'class', 'class_id', '', 'SET NULL');
        $this->forge->addForeignKey('teacher_id', 'teacher', 'teacher_id', '', 'SET NULL');
        $this->forge->createTable('subject', true);
    }

    private function createStudent(): void
    {
        $this->forge->addField([
            'student_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'student_roll_no'  => ['type' => 'VARCHAR', 'constraint' => 50],
            'student_name'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'student_email'    => ['type' => 'VARCHAR', 'constraint' => 150],
            'student_password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'student_mobile'   => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'student_image'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'class_id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'admin_id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ] + $this->timestamps());
        $this->forge->addKey('student_id', true);
        $this->forge->addUniqueKey('student_email');
        $this->forge->addKey(['admin_id', 'student_roll_no']);
        $this->forge->addForeignKey('admin_id', 'admin', 'admin_id', '', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'class', 'class_id', '', 'SET NULL');
        $this->forge->createTable('student', true);
    }

    private function createNotice(): void
    {
        $this->forge->addField([
            'notice_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'notice_title'   => ['type' => 'VARCHAR', 'constraint' => 200],
            'notice_content' => ['type' => 'TEXT', 'null' => true],
            'notice_file'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'class_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'teacher_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'admin_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ] + $this->timestamps());
        $this->forge->addKey('notice_id', true);
        $this->forge->addKey('admin_id');
        $this->forge->addForeignKey('admin_id', 'admin', 'admin_id', '', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'class', 'class_id', '', 'SET NULL');
        $this->forge->createTable('notice', true);
    }

    private function createClasswork(): void
    {
        $this->forge->addField([
            'classwork_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'classwork_title' => ['type' => 'VARCHAR', 'constraint' => 200],
            'classwork_file'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'class_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'subject_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'teacher_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'admin_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ] + $this->timestamps());
        $this->forge->addKey('classwork_id', true);
        $this->forge->addKey('admin_id');
        $this->forge->addForeignKey('admin_id', 'admin', 'admin_id', '', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'class', 'class_id', '', 'SET NULL');
        $this->forge->addForeignKey('subject_id', 'subject', 'subject_id', '', 'SET NULL');
        $this->forge->createTable('classwork', true);
    }

    private function createAssignmentPost(): void
    {
        $this->forge->addField([
            'assignment_post_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'assignment_post_title'       => ['type' => 'VARCHAR', 'constraint' => 200],
            'assignment_post_description' => ['type' => 'TEXT', 'null' => true],
            'assignment_post_file'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'assignment_post_due_date'    => ['type' => 'DATE', 'null' => true],
            'class_id'                    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'subject_id'                  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'teacher_id'                  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'admin_id'                    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ] + $this->timestamps());
        $this->forge->addKey('assignment_post_id', true);
        $this->forge->addKey('admin_id');
        $this->forge->addForeignKey('admin_id', 'admin', 'admin_id', '', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'class', 'class_id', '', 'SET NULL');
        $this->forge->addForeignKey('subject_id', 'subject', 'subject_id', '', 'SET NULL');
        $this->forge->createTable('assignment_post', true);
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
        ] + $this->timestamps());
        $this->forge->addKey('assignment_upload_id', true);
        $this->forge->addKey(['assignment_post_id', 'student_id']);
        $this->forge->addForeignKey('assignment_post_id', 'assignment_post', 'assignment_post_id', '', 'CASCADE');
        $this->forge->addForeignKey('student_id', 'student', 'student_id', '', 'CASCADE');
        $this->forge->createTable('assignment_upload', true);
    }

    private function createTimeTable(): void
    {
        $this->forge->addField([
            'time_table_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'time_table_file' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'class_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'admin_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ] + $this->timestamps());
        $this->forge->addKey('time_table_id', true);
        $this->forge->addKey('admin_id');
        $this->forge->addForeignKey('admin_id', 'admin', 'admin_id', '', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'class', 'class_id', '', 'SET NULL');
        $this->forge->createTable('time_table', true);
    }
}
