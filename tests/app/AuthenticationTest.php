<?php

namespace Tests\App;

use App\Libraries\Auth;
use Tests\App\Support\AppTestCase;

/**
 * @internal
 */
final class AuthenticationTest extends AppTestCase
{
    private function auth(): Auth
    {
        return new Auth(session());
    }

    /**
     * The regression this whole class exists for: the student branch of the old
     * loginAuth() had its password_verify() call commented out, so any student
     * email signed in with any password whatsoever.
     */
    public function testStudentCannotSignInWithAWrongPassword(): void
    {
        $this->assertFalse($this->auth()->attempt('student-a@example.test', 'not-the-password'));
        $this->assertFalse($this->auth()->check());
    }

    public function testStudentCanSignInWithTheCorrectPassword(): void
    {
        $this->assertTrue($this->auth()->attempt('student-a@example.test', 'password-s'));

        $auth = $this->auth();
        $this->assertTrue($auth->check());
        $this->assertSame(Auth::ROLE_STUDENT, $auth->role());
        $this->assertSame($this->adminA, $auth->tenantId());
        $this->assertSame($this->classA, $auth->classId());
    }

    public function testTeacherCannotSignInWithAWrongPassword(): void
    {
        $this->assertFalse($this->auth()->attempt('teacher-a@example.test', 'wrong'));
    }

    public function testAdminSignsInWithTheirOwnTenantId(): void
    {
        $this->assertTrue($this->auth()->attempt('a@example.test', 'password-a'));

        $auth = $this->auth();
        $this->assertSame(Auth::ROLE_ADMIN, $auth->role());
        $this->assertSame($this->adminA, $auth->tenantId());
        $this->assertTrue($auth->canManage());
    }

    public function testUnknownEmailIsRejected(): void
    {
        $this->assertFalse($this->auth()->attempt('nobody@example.test', 'password-a'));
    }

    public function testOneAdminsPasswordDoesNotUnlockAnother(): void
    {
        $this->assertFalse($this->auth()->attempt('b@example.test', 'password-a'));
    }

    public function testStudentsCannotManage(): void
    {
        $this->auth()->attempt('student-a@example.test', 'password-s');
        $this->assertFalse($this->auth()->canManage());
    }

    public function testLogoutClearsTheSession(): void
    {
        $auth = $this->auth();
        $auth->attempt('a@example.test', 'password-a');
        $this->assertTrue($auth->check());

        $auth->logout();
        $this->assertFalse($auth->check());
        $this->assertNull($auth->user());
    }
}
