<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetCollationOfMultiworldHashes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('multiworlds', function (Blueprint $table) {
            $table->string('hash')->collation('utf8_bin')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('multiworlds', function (Blueprint $table) {
            $table->string('hash')->collation('utf8_unicode_ci')->change();
        });
    }
}
