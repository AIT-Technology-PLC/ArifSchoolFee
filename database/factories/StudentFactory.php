<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student;
use App\Models\Company;
use App\Models\Warehouse;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StudentCategory;
use App\Models\StudentGroup;
use App\Models\AcademicYear;
use App\Models\Employee;
use App\Models\Route;
use App\Models\Vehicle;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'company_id' => function () { return Company::inRandomOrder()->value('id'); },
            'code' => function (array $attributes) { return nextReferenceNumber('students', 'code', $attributes['company_id']); },
            'warehouse_id' => function (array $attributes) { return Warehouse::where('company_id', $attributes['company_id'])->inRandomOrder()->value('id'); }, 
            'school_class_id' => function (array $attributes) { return SchoolClass::where('company_id', $attributes['company_id'])->inRandomOrder()->value('id'); },
            'section_id' => function (array $attributes) { 
                return Section::whereHas('schoolClasses', function ($query) use ($attributes) {
                    $query->where('school_classes.id', $attributes['school_class_id']);
                })->where('company_id', $attributes['company_id'])->inRandomOrder()->value('id'); },
            'student_category_id' => function (array $attributes) { return StudentCategory::where('company_id', $attributes['company_id'])->inRandomOrder()->value('id'); },
            'student_group_id' => function (array $attributes) { return StudentGroup::where('company_id', $attributes['company_id'])->inRandomOrder()->value('id'); },
            'academic_year_id' => function (array $attributes) { return AcademicYear::where('company_id', $attributes['company_id']) ->orderByDesc('starting_period')->inRandomOrder()->value('id'); },
            'route_id' => function (array $attributes) { return Route::where('company_id', $attributes['company_id'])->inRandomOrder()->value('id'); },
            'vehicle_id' => function (array $attributes) { return Vehicle::where('company_id', $attributes['company_id'])->inRandomOrder()->value('id'); },
            'created_by' => function (array $attributes) { return Employee::where('company_id', $attributes['company_id'])->inRandomOrder()->value('id'); },
            'updated_by' => function (array $attributes) { return Employee::where('company_id', $attributes['company_id'])->inRandomOrder()->value('id'); },
            'first_name' => $this->faker->randomElement([
                'Dawit', 'Natenal', 'Helen', 'Hawi', 'Genet', 'Elsa', 'Tigist', 'Selam', 'Blen', 'Marta','Mikiyas','Fira','Solomon','Befikir','Salah','Sofoniyas','Miheret','Iserael','Makeda','Bezawit','Rekik','Meklit','Samson','Abel','Denis','Fetsum','Hesekiyas','Maramawit','Tolosa','Kelkiyas'
            ]),
            'last_name' => $this->faker->randomElement([
                'Fikremariam', 'Tadesse', 'Asfaw', 'Girma', 'Bekele', 'Desalegn', 'Mekuria', 'Hailu', 'Abdi', 'Hussen','Zablon','Mikiyas','Debebe','Zemedihun','Tarekegne'
            ]),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->date('Y-m-d', '-10 years'),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->regexify('\+2519[0-9]{8}'),
            'admission_date' => $this->faker->date('2024-9-21'),
            'father_name' => $this->faker->randomElement(['Abera', 'Biruk', 'Desta', 'Kebede', 'Solomon', 'Alemayehu', 'Fasil', 'Kiros', 'Mulugeta', 'Tesfaye', 'Yohannes', 'Bekele', 'Wondimu', 'Tewodros', 'Mekonnen', 'Tadele', 'Haile', 'Tesfaye', 'Nega', 'Kebede', 'Kidane', 'Getachew', 'Biru', 'Hailu', 'Tiruneh', 'Addis', 'Zewdu']),
            'father_phone' => $this->faker->unique()->regexify('\+2519[0-9]{8}'),
            'mother_name' => $this->faker->randomElement(['Mekdes', 'Selam', 'Tsehay', 'Yemisirach', 'Winta', 'Genet', 'Saba', 'Tsion', 'Mulu', 'Tena', 'Rahel', 'Dina', 'Miriam', 'Biru', 'Asrat', 'Fikirte', 'Eden', 'Zeritu', 'Hirut', 'Martha', 'Hiwot', 'Sonia', 'Ruth', 'Kalkidan', 'Zewditu', 'Tseday', 'Tizita']),
            'mother_phone' => $this->faker->unique()->regexify('\+2519[0-9]{8}'),
            'current_address' => 'Addis Ababa, Ethiopia',
            'permanent_address' => 'Addis Ababa, Ethiopia',
        ];
    }
}
