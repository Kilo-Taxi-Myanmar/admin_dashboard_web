<?php

namespace Database\Seeders;

use App\Models\System;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       

      
            // DB::table('systems')->insert([
            //     'balance' => 0.00,
            //     'normal_fee' => 2500.00,
            //     'initial_fee' => 1000.00,
            //     'waiting_fee' => 500.00,
            //     'commission_fee' => 200.00,
            //     'cartype_id'=>1
            // ]);
            $fees = ['orange shop','capital shop','ocean shop'];
            $amount = [1000,2000,3000];

            foreach($fees as $key=>$fee){
                DB::table('fees')->insert([
                    'type' => $fee,
                    'amount'=>$amount[$key]
                ]);
            }
           

            DB::table('systems')->insert([
                'balance' => 0.00,
                'normal_fee' => 1000.00,
                'initial_fee' => 1000.00,
                'waiting_fee' => 500.00,
                'commission_fee' => 3,
               
                'standard_fee'=>2000,
                'cargo_fee'=>2500,
                'plus_fee'=>2500
                
            ]);
       
       

        
       
    }
}
