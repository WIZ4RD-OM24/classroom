<?php

namespace Tests\App\Support;

use App\Models\AdminModel;
use App\Models\AssignmentPostModel;
use App\Models\ClassModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Base class for the application's tests: migrates the schema into the
 * in-memory SQLite database and provides fixtures for two separate
 * organisations, so cross-tenant leaks are easy to assert against.
 */
abstract class AppTestCase extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    protected int $adminA;
    protected int $adminB;
    protected int $classA;
    protected int $studentA;
    protected int $teacherA;
    protected int $assignmentA;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedFixtures();
    }

    private function seedFixtures(): void
    {
        $admins = new AdminModel();
        $admins->skipValidation(true);

        $this->adminA = (int) $admins->insert([
            'admin_name'         => 'Admin A',
            'admin_email'        => 'a@example.test',
            'admin_password'     => password_hash('password-a', PASSWORD_DEFAULT),
            'admin_organisation' => 'College A',
        ], true);

        $this->adminB = (int) $admins->insert([
            'admin_name'         => 'Admin B',
            'admin_email'        => 'b@example.test',
            'admin_password'     => password_hash('password-b', PASSWORD_DEFAULT),
            'admin_organisation' => 'College B',
        ], true);

        $this->classA = (int) (new ClassModel())->insert([
            'class_name'   => 'Class A1',
            'section_name' => 'A',
            'admin_id'     => $this->adminA,
        ], true);

        $this->teacherA = (int) (new TeacherModel())->insert([
            'teacher_name'     => 'Teacher A',
            'teacher_email'    => 'teacher-a@example.test',
            'teacher_password' => password_hash('password-t', PASSWORD_DEFAULT),
            'class_id'         => $this->classA,
            'admin_id'         => $this->adminA,
        ], true);

        $this->studentA = (int) (new StudentModel())->insert([
            'student_roll_no'  => 'A001',
            'student_name'     => 'Student A',
            'student_email'    => 'student-a@example.test',
            'student_password' => password_hash('password-s', PASSWORD_DEFAULT),
            'class_id'         => $this->classA,
            'admin_id'         => $this->adminA,
        ], true);

        $this->assignmentA = (int) (new AssignmentPostModel())->insert([
            'assignment_post_title'    => 'Assignment A',
            'assignment_post_due_date' => date('Y-m-d', strtotime('+5 days')),
            'class_id'                 => $this->classA,
            'admin_id'                 => $this->adminA,
        ], true);
    }

    /**
     * Put a signed-in identity into the session for a feature request.
     */
    protected function actingAs(string $role, int $id, int $adminId, ?int $classId = null): array
    {
        return [
            'isLoggedIn' => true,
            'user'       => [
                'id'       => $id,
                'name'     => ucfirst($role),
                'email'    => "{$role}@example.test",
                'image'    => null,
                'role'     => $role,
                'admin_id' => $adminId,
                'class_id' => $classId,
            ],
        ];
    }
}
