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
            $table->dropColumn('headertext');
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

            $table->dropColumn('header');
            $table->dropColumn('footer');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->string('header');
            $table->text('headertext');
            $table->string('footer');
        });
    }
}
