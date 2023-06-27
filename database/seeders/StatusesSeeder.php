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
                'color' => '#d3ad03',
            ],
            [
                'name' => 'In Progress',
                'color' => '#9d9ef7',
            ],
            [
                'name' => 'On Hold',
                'color' => '#000000',
            ],
            [
                'name' => 'Awaiting Feedback',
                'color' => '#1be32d',
            ],
            [
                'name' => 'Completed',
                'color' => '#000000',
            ],
        ];

        foreach ($statuses as $status) {
            Status::query()->updateOrCreate(['name' => $status['name']], $status);
        }
    }
}
