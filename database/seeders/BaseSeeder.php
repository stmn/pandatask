<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * This seeder is used to generate demo data for the application.
 */
class BaseSeeder extends Seeder
{
    protected function truncateDatabase(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('comments')->truncate();
        DB::table('activities')->truncate();
        DB::table('times')->truncate();
        DB::table('task_users')->truncate();
        DB::table('tasks')->truncate();
        DB::table('project_members')->truncate();
        DB::table('projects')->truncate();
        DB::table('group_users')->truncate();
        DB::table('groups')->truncate();
        DB::table('users')->truncate();
        DB::table('milestones')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
