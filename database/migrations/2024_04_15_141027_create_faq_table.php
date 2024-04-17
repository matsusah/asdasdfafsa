<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FaqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faq', function (Blueprint $table) {
            $table->id('id_faq');
            $table->string('title_faq')->nullable();
            $table->text('description_faq')->nullable();
            $table->string('is_active')->nullable()->default('1')->comment("1 => Aktif, 0 => Non Aktif");
            $table->dateTime('tanggal_jam_add_faq')->nullable();
            $table->bigInteger('creator_faq_id')->nullable();
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
        Schema::dropIfExists('faq');
    }
}
