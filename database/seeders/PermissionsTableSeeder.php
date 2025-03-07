<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [ 
            ['id' => 1, 'name' => 'View Projects', 'guard_name' => 'web'],
            ['id' => 2, 'name' => 'Add New Project', 'guard_name' => 'web'],
            ['id' => 3, 'name' => 'Edit Project', 'guard_name' => 'web'],
            ['id' => 4, 'name' => 'Delete Project', 'guard_name' => 'web'],
            ['id' => 5, 'name' => 'View Clients', 'guard_name' => 'web'],
            ['id' => 6, 'name' => 'Add New Client', 'guard_name' => 'web'],
            ['id' => 7, 'name' => 'Edit Client', 'guard_name' => 'web'],
            ['id' => 8, 'name' => 'Delete Client', 'guard_name' => 'web'],
            ['id' => 9, 'name' => 'View Users', 'guard_name' => 'web'],
            ['id' => 10, 'name' => 'Add New User', 'guard_name' => 'web'],
            ['id' => 11, 'name' => 'Edit User', 'guard_name' => 'web'],
            ['id' => 12, 'name' => 'Delete User', 'guard_name' => 'web'],
            ['id' => 13, 'name' => 'View Permissions', 'guard_name' => 'web'],
            ['id' => 14, 'name' => 'Add New Permission', 'guard_name' => 'web'],
            ['id' => 15, 'name' => 'Edit Permission', 'guard_name' => 'web'],
            ['id' => 16, 'name' => 'Delete Permission', 'guard_name' => 'web'],
            ['id' => 17, 'name' => 'View Roles', 'guard_name' => 'web'],
            ['id' => 18, 'name' => 'Add New Role', 'guard_name' => 'web'],
            ['id' => 19, 'name' => 'Edit Role', 'guard_name' => 'web'],
            ['id' => 20, 'name' => 'Delete Role', 'guard_name' => 'web'],
            ['id' => 21, 'name' => 'View Sources', 'guard_name' => 'web'],
            ['id' => 22, 'name' => 'Add New Source', 'guard_name' => 'web'],
            ['id' => 23, 'name' => 'Edit Source', 'guard_name' => 'web'],
            ['id' => 24, 'name' => 'Delete Source', 'guard_name' => 'web'],
            ['id' => 25, 'name' => 'View KnowledgeBase', 'guard_name' => 'web'],
            ['id' => 26, 'name' => 'Add New KnowledgeBase', 'guard_name' => 'web'],
            ['id' => 27, 'name' => 'Edit KnowledgeBase', 'guard_name' => 'web'],
            ['id' => 28, 'name' => 'Delete KnowledgeBase', 'guard_name' => 'web'],
            ['id' => 29, 'name' => 'View Departments', 'guard_name' => 'web'],
            ['id' => 30, 'name' => 'Add New Department', 'guard_name' => 'web'],
            ['id' => 31, 'name' => 'Edit Department', 'guard_name' => 'web'],
            ['id' => 32, 'name' => 'Delete Department', 'guard_name' => 'web'],
            ['id' => 34, 'name' => 'View Teams', 'guard_name' => 'web'],
            ['id' => 35, 'name' => 'Add New Team', 'guard_name' => 'web'],
            ['id' => 36, 'name' => 'Edit Team', 'guard_name' => 'web'],
            ['id' => 37, 'name' => 'Delete Team', 'guard_name' => 'web'],
            ['id' => 38, 'name' => 'View Tasks', 'guard_name' => 'web'],
            ['id' => 39, 'name' => 'Add New Task', 'guard_name' => 'web'],
            ['id' => 40, 'name' => 'Edit Task', 'guard_name' => 'web'],
            ['id' => 41, 'name' => 'Delete Task', 'guard_name' => 'web'],
            ['id' => 42, 'name' => 'View TaskTypes', 'guard_name' => 'web'],
            ['id' => 43, 'name' => 'Add New TaskType', 'guard_name' => 'web'],
            ['id' => 44, 'name' => 'Edit TaskType', 'guard_name' => 'web'],
            ['id' => 45, 'name' => 'Delete TaskType', 'guard_name' => 'web'],            
            ['id' => 46, 'name' => 'View TaskStatus', 'guard_name' => 'web'],
            ['id' => 47, 'name' => 'Add New TaskStatus', 'guard_name' => 'web'],
            ['id' => 48, 'name' => 'Edit TaskStatus', 'guard_name' => 'web'],
            ['id' => 49, 'name' => 'Delete TaskStatus', 'guard_name' => 'web'],
            ['id' => 50, 'name' => 'View TaskStages', 'guard_name' => 'web'],
            ['id' => 51, 'name' => 'Add New TaskStage', 'guard_name' => 'web'],
            ['id' => 52, 'name' => 'Edit TaskStage', 'guard_name' => 'web'],
            ['id' => 53, 'name' => 'Delete TaskStage', 'guard_name' => 'web'],
            ['id' => 54, 'name' => 'View TaskPriorities', 'guard_name' => 'web'],
            ['id' => 55, 'name' => 'Add New TaskPriority', 'guard_name' => 'web'],
            ['id' => 56, 'name' => 'Edit TaskPriority', 'guard_name' => 'web'],
            ['id' => 57, 'name' => 'Delete TaskPriority', 'guard_name' => 'web'],
        ];

        DB::table('permissions')->insert($permissions);
    }
}
