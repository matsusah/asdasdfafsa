<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcement', function (Blueprint $table) {
            $table->id('id_announcement');
            $table->string('title_announcement')->nullable();
            $table->text('description_announcement')->nullable();
            $table->string('is_active')->nullable()->default('1')->comment("1 => Aktif, 0 => Non Aktif");
            $table->dateTime('tanggal_jam_add_announcement')->nullable();
            $table->bigInteger('creator_announcement_id')->nullable();
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
        Schema::dropIfExists('announcement');
    }
}
