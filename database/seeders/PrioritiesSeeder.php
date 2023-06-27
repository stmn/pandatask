<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritiesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $priorities = [
            [
                'name' => 'Low',
                'color' => '#1be32d',
            ],
            [
                'name' => 'Medium',
                'color' => '#9d9ef7',
            ],
            [
                'name' => 'High',
                'color' => '#ff2f2f',
            ],
        ];

        foreach ($priorities as $priority) {
            Priority::query()->updateOrCreate(['name' => $priority['name']], $priority);
        }
    }
}
