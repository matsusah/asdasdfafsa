<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guide', function (Blueprint $table) {
            $table->id('id_guide');
            $table->string('title_guide')->nullable();
            $table->text('description_guide')->nullable();
            $table->string('is_active')->nullable()->default('1')->comment("1 => Aktif, 0 => Non Aktif");
            $table->dateTime('tanggal_jam_add_guide')->nullable();
            $table->bigInteger('creator_guide_id')->nullable();
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
        Schema::dropIfExists('guide');
    }
}
