<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
class RefactorHeaderFooter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('header');
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('headertext');
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('footer');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->json('header')->default('{}');
            $table->json('footer')->default('{}');

            $table->string('domain')->nullable();
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
            $table->dropColumn('domain');
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('header');
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('footer');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->string('header')->nullable();
            $table->text('headertext')->nullable();
            $table->string('footer')->nullable();
        });
    }
}
