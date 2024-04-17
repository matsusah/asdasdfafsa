<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign', function (Blueprint $table) {
            $table->id('id_campaign');
            $table->string('title_campaign')->nullable();
            $table->text('description_campaign')->nullable();
            $table->text('picture')->nullable()->comment('Photo Campaign');
            $table->string('is_active')->nullable()->default('1')->comment("1 => Aktif, 0 => Non Aktif");
            $table->string('status_verified_campaign')->nullable()->comment("Pending, Accepted, Declined, Expired");
            $table->dateTime('tanggal_jam_add_campaign')->nullable();
            $table->dateTime('tanggal_jam_verifikasi_campaign')->nullable()->comment('Tanggal Jam Pergantian Dari Pending Ke Accepted / Declined / Expired');
            $table->string('level_user_creator_campaign_id')->nullable()->comment('Untuk Filter Data Index');
            $table->bigInteger('creator_campaign_id')->nullable();
            $table->bigInteger('verified_user_id')->nullable();
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
        Schema::dropIfExists('campaign');
    }
}
