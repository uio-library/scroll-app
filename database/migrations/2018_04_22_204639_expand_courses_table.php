<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->json('github_hook')->nullable();
            $table->string('github_secret')->nullable();
            $table->string('commit')->nullable();
            $table->dateTime('last_event_at')->nullable();
            $table->json('last_event')->nullable();
            $table->string('last_event_type')->nullable();
            $table->unique('repo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropUnique('courses_repo_unique');
            $table->dropColumn('github_hook');
            $table->dropColumn('github_secret');
            $table->dropColumn('commit');
            $table->dropColumn('last_event_at');
            $table->dropColumn('last_event_type');
            $table->dropColumn('last_event');
        });
    }
}
