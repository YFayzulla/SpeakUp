<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Room::query()->create(['number'=> 1]);
        Room::query()->create(['number'=> 2]);
        Room::query()->create(['number'=> 3]);
        Room::query()->create(['number'=> 4]);
    }
}
