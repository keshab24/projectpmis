<?php

use Illuminate\Database\Seeder;

class pro_trimestersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pro_trimesters')->insert([
            'name'=>'प्रथम चौमासिक',
            'name_eng'=>'First Trimester',
            'status'=>1,
        ]);

        DB::table('pro_trimesters')->insert([
            'name'=>'दोस्रो चौमासिक',
            'name_eng'=>'Second Trimester',
            'status'=>1,
        ]);
        DB::table('pro_trimesters')->insert([
            'name'=>'तेस्रो चौमासिक',
            'name_eng'=>'Third Trimester',
            'status'=>1,
        ]);
    }
}
