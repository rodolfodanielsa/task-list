<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table('tasks')->insert([
                'summary' => Str::random(2500),
                'user_id' => rand(1, 5),
                'created_at' => Carbon::now(),
            ]);
        }
    }
}
