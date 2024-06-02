<?php

namespace Database\Seeders;

use App\Models\System;
use App\Models\Transaction;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $date = Carbon::createFromDate($year, $month, 1);

        for ($i = 0; $i < 300; $i++) {
            $user = User::role('user')->inRandomOrder()->first();
            $trip = Trip::create([
                'user_id' => $user->id,
                'distance' => $faker->randomFloat(2, 1, 100),
                'duration' => rand(0, 59) . ':' . rand(0, 59),
                'waiting_Time' => rand(0, 59) . ':' . rand(0, 59),
                'normal_fee' => rand(1, 10) * 500,
                'waiting_fee' => rand(1, 10) * 500,
                'extra_fee' =>  rand(1, 10) * 500,
                'total_cost' => rand(0, 100) * 1000,
                'start_lat' => $faker->latitude($min = -90, $max = 90),
                'start_lng' => $faker->longitude($min = -180, $max = 180),
                'end_lat' => $faker->latitude($min = -90, $max = 90),
                'end_lng' => $faker->longitude($min = -180, $max = 180),
                'cartype'=> $faker->randomElement([1,2,3]),
                'driver_id' =>rand(1,5),
                'created_at' => $date,
                'updated_at' => $date
            ]);

            $commissionFee = System::find(1)->commission_fee;
            $user->balance -= $commissionFee;
            $user->save();

            // Create a new transaction record
            Transaction::create([
                'user_id' => $trip->user_id,
                'staff_id' => 1,
                'amount' => $commissionFee,
                'income_outcome' => 'outcome',
                'created_at' => $date,
                'updated_at' => $date
            ]);
            $system = System::findOrFail(1);
            $system->balance += $commissionFee;
            $system->save();
            if ($user->id % 3 == 0) {
                $date->addDay();
            }


        }
    }
}
