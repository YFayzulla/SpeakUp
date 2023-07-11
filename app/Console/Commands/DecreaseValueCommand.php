<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DecreaseValueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'decrease:value';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Decrease the value by 1';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('users')->decrement('day', 1);

        $this->info('Value decreased successfully!');
    }
}
