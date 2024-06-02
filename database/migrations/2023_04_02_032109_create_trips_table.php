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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('distance', 8, 2);
            $table->string('duration');
            $table->string('waiting_time');
            $table->decimal('normal_fee', 8, 0)->nullable();
            $table->decimal('waiting_fee', 8, 0)->nullable();
            $table->decimal('extra_fee', 8, 0)->nullable();
            $table->decimal('initial_fee', 8, 0)->nullable();
            $table->decimal('total_cost', 8, 0)->nullable();
            $table->decimal('start_lat',10,8)->nullable();
            $table->decimal('start_lng',11,8)->nullable();
            $table->decimal('end_lat',10,8)->nullable();
            $table->decimal('end_lng',11,8)->nullable();
            $table->enum('status',['pending','accepted','canceled','completed'])->default('pending');
            $table->string('start_address')->nullable();
            $table->string('end_address')->nullable();
            $table->integer('driver_id')->nullable();
            $table->string('cartype');
            $table->timestamps();
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travel');
    }
};
