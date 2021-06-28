<?php

use ALttP\Build;
use ALttP\Seed;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchToBuildsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('builds', function (Blueprint $table) {
            $table->string('branch', 40)->nullable()->after('build');
            $table->dropUnique(['build']);
            $table->unique(['build', 'branch']);
        });
        Schema::table('seeds', function (Blueprint $table) {
            $table->string('branch', 40)->nullable()->after('build');
        });

        // add branch data to DB
        Build::each(function ($build) {
            $build->branch = "base";
            $build->save();
        });

        // add branch data to DB
        Seed::each(function ($build) {
            $build->branch = "base";
            $build->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('builds', function (Blueprint $table) {
            $table->dropColumn('branch');
            $table->unique(['build']);
            $table->dropUnique(['build', 'branch']);
        });
        Schema::table('seeds', function (Blueprint $table) {
            $table->dropColumn('branch');
        });
    }
}
