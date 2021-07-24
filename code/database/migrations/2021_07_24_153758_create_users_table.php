<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        DB::table('roles')->insert([
            ['role_name' => 'Manager'],
            ['role_name' => 'Technician'],
        ]);

        for ($i = 1; $i < 6; $i++) {
            $roleId = $i == 1 ? 1 : 2;
            DB::table('users')->insert([
                'role_id' => $roleId,
                'created_at' => Carbon::now(),
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            DB::table('tasks')->insert([
                'summary' => Str::random(2500),
                'user_id' => rand(1, 5),
                'created_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
}
