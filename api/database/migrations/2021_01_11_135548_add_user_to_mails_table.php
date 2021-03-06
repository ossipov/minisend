<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserToMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->environment() === 'testing') {
            Schema::table('mails', function (Blueprint $table) {
                $table->integer('user_id')->nullable();
            });
            Schema::table('mails', function (Blueprint $table) {
                $table->integer('user_id')->nullable(false)->change();
            });
        } else {
            Schema::table('mails', function (Blueprint $table) {
                $table->integer('user_id')->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mails', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
