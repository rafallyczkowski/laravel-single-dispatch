<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSingleDispatcherJobsRegisterTable extends Migration
{
    public function up()
    {
        Schema::create('jobs_register', function (Blueprint $table) {

            $table->string('checksum', 64);
            $table->primary('checksum');
            $table->string('job');
            $table->timestamps();
            $table->index('checksum');
            $table->index('created_at');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jobs_register');
    }
}
