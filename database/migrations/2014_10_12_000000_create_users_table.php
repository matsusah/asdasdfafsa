<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Ini berisi username Users');
            $table->string('email');
            $table->string('password');
            $table->text('token')->nullable()->comment("Digunakan Untuk Login Aplikasi");
            $table->string('level_users')->comment("Admin, Pengguna");
            $table->string('is_active')->nullable()->default('1')->comment("1 => Aktif, 0 => Non Aktif");
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
