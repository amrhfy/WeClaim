<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(DepartmentSeeder::class);

        $adminRoleId = Role::where('name', 'SU')->value('id');
        $allDepartmentId = Department::where('name', 'All')->value('id');

        if (is_null($allDepartmentId)) {
            throw new \Exception('Department "All" not found in the departments table.');
        }

        User::create([
            'first_name' => 'Ammar',
            'second_name' => 'Hafiy',
            'email' => 'admin@localhost',
            'password' => bcrypt('iCt@123./'),
            'role_id' => $adminRoleId,
            'department_id' => $allDepartmentId,
        ]);
    }
}
