<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progressions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('session_id');
            $table->unsignedInteger('student_id');
            $table->timestamps();

            $table->foreign('session_id')
                ->references('id')
                ->on('sessions')
                ->onDelete('cascade');
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('progressions');
    }
}
