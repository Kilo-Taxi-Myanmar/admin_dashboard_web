<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0; $i < 50; $i++) {
            Notification::create([
                'user_id' => User::role('user')->inRandomOrder()->first()->id,
                'service' => 'KBZ',
                'account_name' => User::role('user')->inRandomOrder()->first()->name,
                'phone' => User::role('user')->inRandomOrder()->first()->phone,
                'amount' => rand(1, 20) * 500,
                'screenshot' => 'photo',
                'status' => 'unread',
            ]);
        }
    }
}
