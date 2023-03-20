<?php

namespace Database\Factories;

use App\Models\Section;
use App\Models\StudentRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StudentRecordFactory extends Factory
{
    protected $model = StudentRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $student = User::factory()->create();
        $section = Section::query()->inRandomOrder()->whereRelation('myClass.classGroup', 'school_id', 1)->first();
        $class = $section->myClass;
        $student->assignRole('student');

        return [
            'user_id'          => $student->id,
            'my_class_id'      => $class->id,
            'section_id'       => $class->sections->first()->id ?? null,
            'admission_date'   => $this->faker->date(),
            'is_graduated'     => false,
            'admission_number' => Str::random(10),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (StudentRecord $studentRecord) {
            $studentRecord->academicYears()->sync([$studentRecord->user->school->academicYear->id, [
                'my_class_id' => $studentRecord->my_class_id,
                'section_id'  => $studentRecord->section_id,
            ]]);
        });
    }
}