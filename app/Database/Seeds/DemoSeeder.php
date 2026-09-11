<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Creates a small but complete demo tenant so a fresh checkout is usable
 * immediately after `php spark migrate`.
 *
 * Run with: php spark db:seed DemoSeeder
 */
class DemoSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('admin')->insert([
            'admin_name'         => 'Priya Sharma',
            'admin_email'        => 'admin@classroom.test',
            'admin_password'     => password_hash('admin@123', PASSWORD_DEFAULT),
            'admin_mobile'       => '9876543210',
            'admin_designation'  => 'Principal',
            'admin_organisation' => 'MCA Science College',
            'created_at'         => $now,
            'updated_at'         => $now,
        ]);
        $adminId = $this->db->insertID();

        $classIds = [];

        foreach ([['MCA First Year', 'A'], ['MCA Second Year', 'B']] as [$name, $section]) {
            $this->db->table('class')->insert([
                'class_name'   => $name,
                'section_name' => $section,
                'admin_id'     => $adminId,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
            $classIds[] = $this->db->insertID();
        }

        $teacherIds = [];

        foreach ([
            ['Anil Kumar', 'anil@classroom.test', $classIds[0]],
            ['Meera Nair', 'meera@classroom.test', $classIds[1]],
        ] as [$name, $email, $classId]) {
            $this->db->table('teacher')->insert([
                'teacher_name'         => $name,
                'teacher_email'        => $email,
                'teacher_password'     => password_hash('teacher@123', PASSWORD_DEFAULT),
                'teacher_organisation' => 'MCA Science College',
                'class_id'             => $classId,
                'admin_id'             => $adminId,
                'created_at'           => $now,
                'updated_at'           => $now,
            ]);
            $teacherIds[] = $this->db->insertID();
        }

        $subjectIds = [];

        foreach ([
            ['Data Structures', $classIds[0], $teacherIds[0]],
            ['Operating Systems', $classIds[0], $teacherIds[0]],
            ['Machine Learning', $classIds[1], $teacherIds[1]],
        ] as [$name, $classId, $teacherId]) {
            $this->db->table('subject')->insert([
                'subject_name' => $name,
                'class_id'     => $classId,
                'teacher_id'   => $teacherId,
                'admin_id'     => $adminId,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
            $subjectIds[] = $this->db->insertID();
        }

        $students = [
            ['MCA001', 'Rahul Verma', 'rahul@classroom.test', $classIds[0]],
            ['MCA002', 'Sneha Patil', 'sneha@classroom.test', $classIds[0]],
            ['MCA003', 'Arjun Rao', 'arjun@classroom.test', $classIds[0]],
            ['MCA004', 'Fatima Khan', 'fatima@classroom.test', $classIds[1]],
            ['MCA005', 'Joseph Dsouza', 'joseph@classroom.test', $classIds[1]],
        ];

        foreach ($students as [$roll, $name, $email, $classId]) {
            $this->db->table('student')->insert([
                'student_roll_no'  => $roll,
                'student_name'     => $name,
                'student_email'    => $email,
                'student_password' => password_hash('student@123', PASSWORD_DEFAULT),
                'class_id'         => $classId,
                'admin_id'         => $adminId,
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
        }

        $this->db->table('assignment_post')->insertBatch([
            [
                'assignment_post_title'       => 'Binary Search Tree implementation',
                'assignment_post_description' => 'Implement insertion, deletion and in-order traversal. Submit a single PDF with your source code and complexity analysis.',
                'assignment_post_due_date'    => date('Y-m-d', strtotime('+7 days')),
                'class_id'                    => $classIds[0],
                'subject_id'                  => $subjectIds[0],
                'teacher_id'                  => $teacherIds[0],
                'admin_id'                    => $adminId,
                'created_at'                  => $now,
                'updated_at'                  => $now,
            ],
            [
                'assignment_post_title'       => 'Process scheduling report',
                'assignment_post_description' => 'Compare round-robin and shortest-job-first scheduling with worked examples.',
                'assignment_post_due_date'    => date('Y-m-d', strtotime('-3 days')),
                'class_id'                    => $classIds[0],
                'subject_id'                  => $subjectIds[1],
                'teacher_id'                  => $teacherIds[0],
                'admin_id'                    => $adminId,
                'created_at'                  => $now,
                'updated_at'                  => $now,
            ],
        ]);

        $this->db->table('notice')->insert([
            'notice_title'   => 'Mid-semester examination schedule',
            'notice_content' => 'Mid-semester examinations begin next Monday. The detailed timetable is available on the notice board and with your class teacher.',
            'class_id'       => $classIds[0],
            'admin_id'       => $adminId,
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);

        $this->db->table('classwork')->insert([
            'classwork_title' => 'Week 3 — Linked list exercises',
            'class_id'        => $classIds[0],
            'subject_id'      => $subjectIds[0],
            'admin_id'        => $adminId,
            'created_at'      => $now,
            'updated_at'      => $now,
        ]);
    }
}
