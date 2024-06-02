<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use App\Models\UserImage;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Create admin user
        $admin =
            User::create([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'phone' => '09876543210',
                'password' => Hash::make('admin'),
            ]);
        $admin->assignRole('admin');


        // Create user
        $faker = Faker::create();
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $date = Carbon::createFromDate($year, $month, 1);
        for ($i = 0; $i < 5; $i++) {
            $user = User::create([
                'name' => $faker->userName(),
                'email' => $faker->email(),
                'phone' => $faker->phoneNumber(),
                'birth_date' => $faker->date(),
                'address' => $faker->streetAddress(),
                'nrc_no' => '9/YNG(N)' . rand(111111, 999999),
                'driving_license' => 'D/' . rand(11111, 99999) . '/10',
                'lat' => $faker->latitude($min = -90, $max = 90),
                'lng' => $faker->longitude($min = -180, $max = 180),

                'balance' => 0,
                'password' => Hash::make(12345678),
            ]);
            $user->driver_id = sprintf('%04d', $user->id - 1);
            UserImage::create(['user_id' => $user->id]);
            Vehicle::create(['vehicle_plate_no' => 'YNG-' . $faker->numberBetween(11111, 99999),
                'vehicle_model' => 'BMW-' . $faker->numberBetween(2000, 2020),
                'user_id' => $user->id,
                'type' => json_encode([$faker->randomElement([1,2,3]), $faker->randomElement([1,2,3])]),  
                

            ]);
           
            $user->assignRole('user');

            // Create a new transaction record
            for ($j = 0; $j < 3; $j++) {

                $topup = rand(1, 20) * 1000;
                $user->balance += $topup;
                $user->save();

                Transaction::create([
                    'user_id' => $user->id,
                    'staff_id' => 1,
                    'amount' => $topup,
                    'income_outcome' => 'income',
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
                if ($user->id % 3 == 0) {
                    $date->addDays(1);
                }
                if ($user->id % 7 == 0) {
                    $date->addWeek();
                }
            }
        }
    }
}
