<?php

namespace App\Services\Teacher;

use App\Models\User;
use App\Services\Print\PrintService;
use App\Services\User\UserService;

class TeacherService
{
    public $user;

    public function __construct(UserService $user)
    {
        $this->user = $user;
    }

    public function getAllTeachers()
    {
        return $this->user->getUsersByRole('teacher')->load('teacherRecord');
    }

    //create teacher method

    public function createTeacher($record)
    {
        $teacher = $this->user->createUser($record);

        $teacher->assignRole('teacher');

        return session()->flash('success', 'Teacher Created Successfully');
    }

    //update teacher method

    public function updateTeacher(User $teacher, $records)
    {
        $this->user->updateUser($teacher, $records, 'teacher');

        return session()->flash('success', 'Teacher Updated Successfully');
    }

    //delete teacher method

    public function deleteTeacher(User $teacher)
    {
        $this->user->deleteUser($teacher);

        return session()->flash('success', 'Teacher Deleted Successfully');
    }

    //print teacher method

    public function printProfile(string $name, string $view, array $data)
    {
        return PrintService::createPdfFromView($name, $view, $data);
    }
}
