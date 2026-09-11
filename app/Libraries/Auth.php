<?php

namespace App\Libraries;

use App\Models\AdminModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use CodeIgniter\Session\Session;

/**
 * Single entry point for authentication and the current identity.
 *
 * Replaces the previous approach, where the controller wrote raw rows into
 * `$_SESSION['admin']` / `$_SESSION['teacher']` / `$_SESSION['student']` and
 * every other file had to guess which of the three was populated. Views that
 * read `$_SESSION['admin']['admin_name']` fatally errored when a teacher or a
 * student logged in.
 *
 * It also fixes the most serious bug in the application: the student branch of
 * the old `loginAuth()` had its `password_verify()` call commented out, so any
 * student email address logged in with any password at all.
 */
class Auth
{
    public const ROLE_ADMIN   = 'admin';
    public const ROLE_TEACHER = 'teacher';
    public const ROLE_STUDENT = 'student';

    private Session $session;

    public function __construct(?Session $session = null)
    {
        $this->session = $session ?? session();
    }

    /**
     * Verify credentials against all three account types and start a session.
     *
     * Every branch verifies the password hash. Accounts are checked in a fixed
     * order and the same generic failure message is returned whether the email
     * is unknown or the password is wrong, so the response does not reveal
     * which addresses are registered.
     */
    public function attempt(string $email, string $password): bool
    {
        $candidates = [
            [self::ROLE_ADMIN, (new AdminModel())->findByEmail($email), 'admin_password'],
            [self::ROLE_TEACHER, (new TeacherModel())->findByEmail($email), 'teacher_password'],
            [self::ROLE_STUDENT, (new StudentModel())->findByEmail($email), 'student_password'],
        ];

        foreach ($candidates as [$role, $row, $passwordField]) {
            if ($row === null) {
                continue;
            }

            if (! password_verify($password, (string) ($row[$passwordField] ?? ''))) {
                return false;
            }

            $this->login($role, $row);

            return true;
        }

        // Equalise the timing between "no such account" and "wrong password"
        // so the response time does not disclose whether the email exists.
        password_verify($password, '$2y$10$usesomesillystringfore7hnbRJHxXVLeakoG8K30M1MlGZOYKl2');

        return false;
    }

    /**
     * Establish the session for an authenticated row.
     */
    public function login(string $role, array $row): void
    {
        // Prevent session fixation: the pre-login session id is discarded.
        $this->session->regenerate(true);

        $identity = match ($role) {
            self::ROLE_ADMIN => [
                'id'       => (int) $row['admin_id'],
                'name'     => $row['admin_name'],
                'email'    => $row['admin_email'],
                'image'    => $row['admin_image'] ?? null,
                'admin_id' => (int) $row['admin_id'],
                'class_id' => null,
            ],
            self::ROLE_TEACHER => [
                'id'       => (int) $row['teacher_id'],
                'name'     => $row['teacher_name'],
                'email'    => $row['teacher_email'],
                'image'    => $row['teacher_image'] ?? null,
                'admin_id' => (int) $row['admin_id'],
                'class_id' => isset($row['class_id']) ? (int) $row['class_id'] : null,
            ],
            self::ROLE_STUDENT => [
                'id'       => (int) $row['student_id'],
                'name'     => $row['student_name'],
                'email'    => $row['student_email'],
                'image'    => $row['student_image'] ?? null,
                'admin_id' => (int) $row['admin_id'],
                'class_id' => isset($row['class_id']) ? (int) $row['class_id'] : null,
            ],
            default => throw new \InvalidArgumentException("Unknown role: {$role}"),
        };

        $this->session->set([
            'isLoggedIn' => true,
            'user'       => $identity + ['role' => $role],
        ]);
    }

    public function check(): bool
    {
        return (bool) $this->session->get('isLoggedIn');
    }

    /**
     * The signed-in identity, or null.
     */
    public function user(): ?array
    {
        $user = $this->session->get('user');

        return is_array($user) ? $user : null;
    }

    public function role(): ?string
    {
        return $this->user()['role'] ?? null;
    }

    public function is(string ...$roles): bool
    {
        return in_array($this->role(), $roles, true);
    }

    public function id(): ?int
    {
        return $this->user()['id'] ?? null;
    }

    /**
     * The organisation every query must be scoped to.
     */
    public function tenantId(): ?int
    {
        return $this->user()['admin_id'] ?? null;
    }

    public function classId(): ?int
    {
        return $this->user()['class_id'] ?? null;
    }

    public function name(): string
    {
        return (string) ($this->user()['name'] ?? 'Guest');
    }

    /**
     * Roles allowed to create, edit and delete records.
     */
    public function canManage(): bool
    {
        return $this->is(self::ROLE_ADMIN, self::ROLE_TEACHER);
    }

    public function logout(): void
    {
        // Clear the identity explicitly before destroying the session. Some
        // session handlers keep the already-loaded data array alive after
        // destroy(), which would leave the request still looking signed in.
        $this->session->remove(['isLoggedIn', 'user']);

        // `session_destroy()` was called directly before, which left
        // CodeIgniter's session wrapper holding a handle to a destroyed session.
        $this->session->destroy();
    }
}
