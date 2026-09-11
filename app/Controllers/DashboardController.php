<?php

namespace App\Controllers;

use App\Libraries\Auth;
use App\Models\AssignmentPostModel;
use App\Models\ClassModel;
use App\Models\ClassworkModel;
use App\Models\NoticeModel;
use App\Models\StudentModel;
use App\Models\SubjectModel;
use App\Models\TeacherModel;

/**
 * The landing page, which now renders something useful for all three roles.
 *
 * The old dashboard read `$_SESSION['admin']` and fell through to
 * `elseif ($_SESSION['teacher'])`, which raised an undefined-index error for a
 * student session and left the four count variables undefined, so the view
 * itself then errored as well.
 */
class DashboardController extends BaseController
{
    public function index()
    {
        return match ($this->auth->role()) {
            Auth::ROLE_STUDENT => $this->studentDashboard(),
            default            => $this->staffDashboard(),
        };
    }

    private function staffDashboard()
    {
        $tenantId = $this->tenantId();

        $assignments = new AssignmentPostModel();

        $data = [
            'title'  => 'Dashboard',
            'active' => 'dashboard',
            'stats'  => [
                [
                    'label' => 'Classes',
                    'value' => (new ClassModel())->forTenant($tenantId)->countAllResults(),
                    'href'  => route_to('classes'),
                    'icon'  => 'school',
                    'tone'  => 'indigo',
                ],
                [
                    'label' => 'Teachers',
                    'value' => (new TeacherModel())->forTenant($tenantId)->countAllResults(),
                    'href'  => route_to('teachers'),
                    'icon'  => 'teacher',
                    'tone'  => 'emerald',
                ],
                [
                    'label' => 'Students',
                    'value' => (new StudentModel())->forTenant($tenantId)->countAllResults(),
                    'href'  => route_to('students'),
                    'icon'  => 'students',
                    'tone'  => 'amber',
                ],
                [
                    'label' => 'Subjects',
                    'value' => (new SubjectModel())->forTenant($tenantId)->countAllResults(),
                    'href'  => route_to('subjects'),
                    'icon'  => 'book',
                    'tone'  => 'rose',
                ],
            ],
            'upcoming' => array_slice($assignments->withRelations($tenantId, null, true, false), 0, 5),
            'notices'  => array_slice((new NoticeModel())->withClass($tenantId), 0, 4),
            'classes'  => (new ClassModel())->withStudentCounts($tenantId),
        ];

        return view('dashboard/staff', $data);
    }

    private function studentDashboard()
    {
        $tenantId = $this->tenantId();
        $classId  = $this->auth->classId();

        $assignments = (new AssignmentPostModel())->withRelations($tenantId, $classId, false, true);
        $today       = date('Y-m-d');

        $open = array_values(array_filter(
            $assignments,
            static fn (array $a) => $a['assignment_post_due_date'] === null || $a['assignment_post_due_date'] >= $today
        ));

        $data = [
            'title'       => 'My classroom',
            'active'      => 'dashboard',
            'assignments' => $open,
            'overdue'     => count($assignments) - count($open),
            'notices'     => (new NoticeModel())->withClass($tenantId, $classId, true),
            'classworks'  => array_slice((new ClassworkModel())->withRelations($tenantId, $classId, true), 0, 5),
        ];

        return view('dashboard/student', $data);
    }
}
