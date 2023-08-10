<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $settings = [
            [
                'name' => 'brand_name',
                'value' => 'Pandatask',
            ],
            [
                'name' => 'brand_logo',
                'value' => null,
            ],
            [
                'name' => 'brand_favicon',
                'value' => null,
            ],
            [
                'name' => 'brand_color',
                'value' => '#347CE4',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::query()->updateOrCreate(['name' => $setting['name']], $setting);
        }
    }
}
