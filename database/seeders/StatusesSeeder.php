<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'New',
                'color' => '#66ff66',
            ],
            [
                'name' => 'In Progress',
                'color' => '#ffff66',
            ],
            [
                'name' => 'On Hold',
                'color' => '#ff66ff',
            ],
            [
                'name' => 'Awaiting Feedback',
                'color' => '#66ffff',
            ],
            [
                'name' => 'Completed',
                'color' => '#ff6666',
            ],
        ];

        foreach ($statuses as $status) {
            Status::query()->updateOrCreate(['name' => $status['name']], $status);
        }
    }
}
