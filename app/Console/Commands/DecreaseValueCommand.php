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
//        $rows = DB::table('users')->whereNotNull('status')->get();
//
//        foreach ($rows as $row) {
//            $this->info('Skipping cron job for row ID: ' . $row->id);
//        }
//
//        DB::table('users')
//            ->whereNull('status')
//            ->decrement('day', 1);
//
//        $this->info('Value decreased successfully!');

        $rows = DB::table('users')->role('user')->where('status', '!=', '0')->get();
        foreach ($rows as $row) {
            $newValue = $row->day - 2;
            DB::table('users')->where('id', $row->id)->update(['day' => $newValue]);
        }
        $this->info('Value decreased successfully!');

    }
}
