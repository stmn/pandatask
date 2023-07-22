<?php

namespace App\Console\Commands\Demo;

use Illuminate\Console\Command;

class ResetDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:reset-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset database for demo purposes';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->call('migrate:fresh', [
            '--seed' => true,
        ]);
        $this->call('db:seed', [
            '--class' => 'DemoDatabaseSeeder',
        ]);
    }
}
