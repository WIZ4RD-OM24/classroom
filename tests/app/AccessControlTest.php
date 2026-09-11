<?php

namespace Tests\App;

use App\Libraries\Auth;
use App\Models\StudentModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Tests\App\Support\AppTestCase;

/**
 * Route-level access control and tenant isolation.
 *
 * @internal
 */
final class AccessControlTest extends AppTestCase
{
    /**
     * @dataProvider protectedRoutes
     */
    public function testGuestIsRedirectedToLogin(string $route): void
    {
        $result = $this->get($route);

        $this->assertTrue($result->isRedirect(), "{$route} should redirect a guest");
        $this->assertStringContainsString('login', (string) $result->getRedirectUrl());
    }

    public static function protectedRoutes(): array
    {
        return [
            ['/'],
            ['students'],
            ['teachers'],
            ['classes'],
            ['subjects'],
            ['assignments'],
            ['notices'],
            ['classworks'],
            ['profile'],
        ];
    }

    /**
     * @dataProvider staffOnlyRoutes
     */
    public function testStudentCannotReachStaffRoutes(string $route): void
    {
        $result = $this->withSession(
            $this->actingAs(Auth::ROLE_STUDENT, $this->studentA, $this->adminA, $this->classA)
        )->get($route);

        $this->assertTrue($result->isRedirect(), "{$route} should be refused to a student");
    }

    public static function staffOnlyRoutes(): array
    {
        return [
            ['students'],
            ['students/new'],
            ['teachers'],
            ['classes'],
            ['classes/new'],
            ['subjects'],
            ['assignments/new'],
            ['notices/new'],
            ['classworks/new'],
        ];
    }

    public function testStaffCanReachStaffRoutes(): void
    {
        $result = $this->withSession(
            $this->actingAs(Auth::ROLE_ADMIN, $this->adminA, $this->adminA)
        )->get('students');

        // Asserted against the status code and raw body rather than with
        // isOK()/assertSee(), which route through CodeIgniter 4.1's DOMParser
        // and its mb_convert_encoding() call — deprecated on PHP 8.2.
        $this->assertSame(200, $result->response()->getStatusCode());
        $this->assertStringContainsString('Student A', $result->getBody());
    }

    /**
     * Auto-routing used to expose every public controller method as a URL.
     *
     * Outside production CodeIgniter throws rather than rendering a 404 page,
     * so the exception itself is the assertion.
     *
     * @dataProvider legacyAutoRoutes
     */
    public function testLegacyAutoRoutedUrlsAreGone(string $route): void
    {
        $this->expectException(PageNotFoundException::class);

        $this->withSession($this->actingAs(Auth::ROLE_ADMIN, $this->adminA, $this->adminA))
            ->get($route);
    }

    public static function legacyAutoRoutes(): array
    {
        return [
            ['AdminController/loginAuth'],
            ['StudentController/add_student'],
            ['TeacherController/delete_teacher/1'],
            ['delete-student/1'],
            ['delete-class/1'],
        ];
    }

    // ---- Tenant isolation -------------------------------------------------

    public function testOneOrganisationCannotSeeAnothersStudents(): void
    {
        $result = $this->withSession(
            $this->actingAs(Auth::ROLE_ADMIN, $this->adminB, $this->adminB)
        )->get('students');

        $this->assertSame(200, $result->response()->getStatusCode());
        $this->assertStringNotContainsString('Student A', $result->getBody());
        $this->assertStringNotContainsString('student-a@example.test', $result->getBody());
    }

    public function testOneOrganisationCannotOpenAnothersStudent(): void
    {
        $this->expectException(PageNotFoundException::class);

        $this->withSession($this->actingAs(Auth::ROLE_ADMIN, $this->adminB, $this->adminB))
            ->get("students/{$this->studentA}");
    }

    public function testOneOrganisationCannotDeleteAnothersStudent(): void
    {
        $this->withSession($this->actingAs(Auth::ROLE_ADMIN, $this->adminB, $this->adminB))
            ->post("students/{$this->studentA}/delete");

        $this->assertNotNull(
            (new StudentModel())->find($this->studentA),
            "Organisation B deleted organisation A's student"
        );
    }

    public function testOneOrganisationCannotEditAnothersStudent(): void
    {
        try {
            $this->withSession($this->actingAs(Auth::ROLE_ADMIN, $this->adminB, $this->adminB))
                ->post("students/{$this->studentA}", [
                    'student_roll_no' => 'HACKED',
                    'student_name'    => 'Hacked',
                    'student_email'   => 'hacked@example.test',
                ]);
        } catch (PageNotFoundException $e) {
            // Expected: the record is invisible to organisation B.
        }

        $student = (new StudentModel())->find($this->studentA);
        $this->assertSame('Student A', $student['student_name']);
    }

    /**
     * A student who has not been put in a class yet must not fall back to
     * seeing everything in the organisation.
     */
    public function testStudentWithNoClassSeesOnlyOrganisationWideItems(): void
    {
        $result = $this->withSession(
            $this->actingAs(Auth::ROLE_STUDENT, $this->studentA, $this->adminA, null)
        )->get('assignments');

        $this->assertSame(200, $result->response()->getStatusCode());
        $this->assertStringNotContainsString('Assignment A', $result->getBody());
    }

    public function testStudentSeesTheirOwnClassesAssignment(): void
    {
        $result = $this->withSession(
            $this->actingAs(Auth::ROLE_STUDENT, $this->studentA, $this->adminA, $this->classA)
        )->get('assignments');

        $this->assertSame(200, $result->response()->getStatusCode());
        $this->assertStringContainsString('Assignment A', $result->getBody());
    }

    public function testStudentCannotOpenAnotherClassesAssignment(): void
    {
        $this->expectException(PageNotFoundException::class);

        // Same organisation, but a different class.
        $this->withSession(
            $this->actingAs(Auth::ROLE_STUDENT, $this->studentA, $this->adminA, $this->classA + 99)
        )->get("assignments/{$this->assignmentA}");
    }
}
