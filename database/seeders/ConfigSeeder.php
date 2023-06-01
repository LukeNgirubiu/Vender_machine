<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    /*
      Run the database seeds.
     
  
     */
    public function run()
    {
        /**
         * 
         */
    //Stick to seeding syntax used below
      DB::table('slots')->insert([
        'name' => '500 Grams',
        'cost' => 0,
        'quantity' => 0
       ]);
        DB::table('slots')->insert([
            'name' => '1 Kg',
            'cost' => 0,
            'quantity' => 0
        ]);
        DB::table('slots')->insert([
            'name' => '2 Kg',
            'cost' => 0,
            'quantity' => 0
        ]);
        DB::table('slots')->insert([
            'name' => '5 Kgs',
            'cost' => 0,
            'quantity' => 0
        ]);
        DB::table('slots')->insert([
            'name' => '2 Kg',
            'cost' => 0,
            'quantity' => 0
        ]);
    
        //Coins
        DB::table('coins')->insert([
            'name' => '5',
            'quantity' => 0
        ]);
        DB::table('coins')->insert([
            'name' => '10',
            'quantity' => 0
        ]);
        DB::table('coins')->insert([
            'name' => '20',
            'quantity' => 0
        ]);
        DB::table('coins')->insert([
            'name' => '40',
            'quantity' => 0
        ]);
    }
}
