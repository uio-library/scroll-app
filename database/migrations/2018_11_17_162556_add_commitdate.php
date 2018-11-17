<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
class AddCommitdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->renameColumn('commit', 'last_commit');
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->dateTime('last_commit_at')->nullable();
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
            $table->renameColumn('last_commit', 'commit');
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('last_commit_at');
        });
    }
}
