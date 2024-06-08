<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->string('polyline')->after('extra_fee_list')->nullable();
            $table->decimal('commission_fee', 8, 2)->after('polyline')->default(3)->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->string('polyline')->after('extra_fee_list')->nullable();
            $table->decimal('commission_fee', 8, 2)->after('polyline')->default(3)->nullable();
            
        });
    }
};
