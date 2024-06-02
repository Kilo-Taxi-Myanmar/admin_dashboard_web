<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->decimal('balance', 10, 0);
            $table->decimal('normal_fee', 10, 0);
            $table->decimal('initial_fee', 10, 0);
            $table->decimal('waiting_fee', 10, 0);
            $table->decimal('commission_fee', 10, 0);
            $table->integer('order_commission_fee')->default(10);
            $table->decimal('standard_fee', 10, 0);
            $table->decimal('cargo_fee', 10, 0);
            $table->decimal('plus_fee', 10, 0);
            $table->string('app_link')->nullable();
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('systems');
    }
};
