<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('institution_id')->nullable();
            $table->string('path');
            $table->unsignedInteger('fileable_id');
            $table->string('fileable_type');
            $table->integer('size');
            $table->timestamps();

            $table->foreign('institution_id')
                ->references('id')
                ->on('institutions')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
