r<?php

use Illuminate\Database\Seeder;

class pro_regionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pro_regions')->insert([
            'slug'=>'Eastern-Development-Region',
            'name'=>'पुर्वाञ्चल विकास क्षेत्र',
            'name_eng'=>'Eastern Development Region',
            'description'=>'',
            'description_eng'=>'',
            'image'=>'',
            'coordinates'=>'',
            'status'=>1,
        ]);
        DB::table('pro_regions')->insert([
            'slug'=>'Central-Development-Region',
            'name'=>'मध्यमाञ्चल विकास क्षेत्र',
            'name_eng'=>'Central Development Region',
            'description'=>'',
            'description_eng'=>'',
            'image'=>'',
            'coordinates'=>'',
            'status'=>1,
        ]);
        DB::table('pro_regions')->insert([
            'slug'=>'Western-Development-Region',
            'name'=>'पश्चिमाञ्चल विकास क्षेत्र',
            'name_eng'=>'Western Development Region',
            'description'=>'',
            'description_eng'=>'',
            'image'=>'',
            'coordinates'=>'',
            'status'=>1,
        ]);
        DB::table('pro_regions')->insert([
            'slug'=>'Mid-Western-Development-Region',
            'name'=>'मध्य पश्चिमाञ्चल विकास क्षेत्र',
            'name_eng'=>'Mid-Western Development Region',
            'description'=>'',
            'description_eng'=>'',
            'image'=>'',
            'coordinates'=>'',
            'status'=>1,
        ]);
        DB::table('pro_regions')->insert([
            'slug'=>'Far-Western-Development-Region',
            'name'=>'सुदुर पश्चिमाञ्चल विकास क्षेत्र',
            'name_eng'=>'Far-Western Development Region',
            'description'=>'',
            'description_eng'=>'',
            'image'=>'',
            'coordinates'=>'',
            'status'=>1,
        ]);
    }
}
