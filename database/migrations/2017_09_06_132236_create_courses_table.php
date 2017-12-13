<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->json('modules');
            $table->string('header');
            $table->text('headertext');
            $table->string('footer');
            $table->string('repo');
            $table->timestamps();
        });

        Schema::table('exercises', function (Blueprint $table) {
            $table->integer('course_id')->unsigned();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->unique(['course_id', 'name']);  // course_id + name combo must be unique
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropUnique(['course_id', 'name']);
            $table->dropColumn('course_id');
        });

        Schema::dropIfExists('courses');
    }
}
