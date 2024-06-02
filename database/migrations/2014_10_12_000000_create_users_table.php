<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('driver_id')->nullable()->unique();
            $table->string('phone')->unique();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('birth_date')->nullable();
            $table->string('address')->nullable();
            $table->decimal('lat',10,8)->nullable();
            $table->decimal('lng',11,8)->nullable();
            $table->string('nrc_no')->unique()->nullable();
            $table->string('driving_license')->unique()->nullable();
            $table->decimal('balance', 10, 0)->default(0);
            $table->enum('status', ['active', 'pending'])->default('pending');
            $table->string('password');
            $table->string('device_token')->nullable();
            $table->string('fcm_token')->nullable();
            $table->boolean('available')->default(true);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
