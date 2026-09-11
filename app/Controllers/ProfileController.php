<?php

namespace App\Controllers;

use App\Libraries\Auth;
use App\Libraries\FileStore;
use App\Models\AdminModel;
use App\Models\BaseModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;

/**
 * Profile viewing and editing for whichever role is signed in.
 *
 * Previously only administrators had a profile page, and it read
 * `$_SESSION['admin']['admin_id']` directly, so a teacher or student session
 * hit an undefined-index error.
 */
class ProfileController extends BaseController
{
    public function show()
    {
        [$model, $prefix] = $this->modelForRole();

        return view('profile/show', [
            'title'   => 'My profile',
            'active'  => 'profile',
            'profile' => $model->find($this->auth->id()),
            'prefix'  => $prefix,
        ]);
    }

    public function edit()
    {
        [$model, $prefix] = $this->modelForRole();

        return view('profile/form', [
            'title'   => 'Edit profile',
            'active'  => 'profile',
            'profile' => $model->find($this->auth->id()),
            'prefix'  => $prefix,
        ]);
    }

    public function update()
    {
        [$model, $prefix] = $this->modelForRole();

        $id      = (int) $this->auth->id();
        $current = $model->find($id);

        if ($current === null) {
            return redirect()->to(route_to('profile'))->with('error', 'Your profile could not be loaded.');
        }

        $data = [
            $prefix . '_name'   => $this->request->getPost('name'),
            $prefix . '_mobile' => $this->request->getPost('mobile'),
        ];

        if ($prefix === 'admin') {
            $data['admin_designation']  = $this->request->getPost('designation');
            $data['admin_organisation'] = $this->request->getPost('organisation');
        }

        $store    = new FileStore();
        $tenantId = $this->tenantId();
        $image    = $this->request->getFile('image');

        if ($image !== null && $image->isValid()) {
            try {
                $data[$prefix . '_image'] = $store->store($image, 'avatars', $tenantId);
            } catch (\RuntimeException $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }

        if (! $model->update($id, $data)) {
            return $this->failValidation($model);
        }

        // Remove the old avatar only once the row has been updated, so a failed
        // save never leaves the profile pointing at a deleted file.
        if (isset($data[$prefix . '_image'])) {
            $store->delete($current[$prefix . '_image'] ?? null, 'avatars', $tenantId);
        }

        // Keep the header's name and avatar in step with the saved row.
        $this->auth->login($this->auth->role(), $model->find($id));

        return redirect()->to(route_to('profile'))->with('success', 'Profile updated.');
    }

    /**
     * @return array{0: BaseModel, 1: string}
     */
    private function modelForRole(): array
    {
        return match ($this->auth->role()) {
            Auth::ROLE_TEACHER => [new TeacherModel(), 'teacher'],
            Auth::ROLE_STUDENT => [new StudentModel(), 'student'],
            default            => [new AdminModel(), 'admin'],
        };
    }
}
