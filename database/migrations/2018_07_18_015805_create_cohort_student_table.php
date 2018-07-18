<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCohortStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cohort_student', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cohort_id');
            $table->unsignedInteger('student_id');
            $table->timestamps();

            $table->unique(array('cohort_id','student_id'));

            $table->foreign('cohort_id')
                ->references('id')
                ->on('cohorts')
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
        Schema::dropIfExists('cohort_student');
    }
}
