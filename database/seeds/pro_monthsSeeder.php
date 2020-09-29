<?php

use Illuminate\Database\Seeder;

class pro_monthsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pro_months')->insert([
            'slug'=>'साउन',
            'name'=>'साउन',
            'name_eng'=>'Shrawan',
            'name_eng_eng'=>'mid-July to mid-August',
            'trim_id'=>1,
            'status'=>1,
        ]);
        DB::table('pro_months')->insert([
            'slug'=>'भदौ',
            'name'=>'भदौ',
            'name_eng'=>'Bhadra',
            'name_eng_eng'=>'mid-August to mid-September',
            'trim_id'=>1,
            'status'=>1,
        ]);
        DB::table('pro_months')->insert([
            'slug'=>'असोज',
            'name'=>'असोज',
            'name_eng'=>'Ashwin',
            'name_eng_eng'=>'mid-September to mid-October',
            'trim_id'=>1,
            'status'=>1,
        ]);
        DB::table('pro_months')->insert([
            'slug'=>'कार्तिक',
            'name'=>'कार्तिक',
            'name_eng'=>'Kartik',
            'name_eng_eng'=>'mid-October to mid-November',
            'trim_id'=>1,
            'status'=>1,
        ]);
        DB::table('pro_months')->insert([
            'slug'=>'मंसिर',
            'name'=>'मंसिर',
            'name_eng'=>'Mangsir',
            'name_eng_eng'=>'mid-November to mid-December',
            'trim_id'=>2,
            'status'=>1,
        ]);
        DB::table('pro_months')->insert([
            'slug'=>'पुष',
            'name'=>'पुष',
            'name_eng'=>'Poush',
            'name_eng_eng'=>'mid-December to mid-January',
            'trim_id'=>2,
            'status'=>1,
        ]);
        DB::table('pro_months')->insert([
            'slug'=>'माघ',
            'name'=>'माघ',
            'name_eng'=>'Magh',
            'name_eng_eng'=>'mid-January to mid-February',
            'trim_id'=>2,
            'status'=>1,
        ]);
        DB::table('pro_months')->insert([
            'slug'=>'फागुन',
            'name'=>'फागुन',
            'name_eng'=>'Falgun',
            'name_eng_eng'=>'mid-February to mid-March',
            'trim_id'=>2,
            'status'=>1,
        ]);
        DB::table('pro_months')->insert([
            'slug'=>'चैत',
            'name'=>'चैत',
            'name_eng'=>'Chaitra',
            'name_eng_eng'=>'mid-March to mid-April',
            'trim_id'=>3,
            'status'=>1,
        ]);
        DB::table('pro_months')->insert([
            'slug'=>'बैशाख',
            'name'=>'बैशाख',
            'name_eng'=>'Baishakh',
            'name_eng_eng'=>'mid-April to mid-May',
            'trim_id'=>3,
            'status'=>1,
        ]);
        DB::table('pro_months')->insert([
            'slug'=>'जेठ',
            'name'=>'जेठ',
            'name_eng'=>'Jestha',
            'name_eng_eng'=>'mid-May to mid-June',
            'trim_id'=>3,
            'status'=>1,
        ]);
        DB::table('pro_months')->insert([
            'slug'=>'असार',
            'name'=>'असार',
            'name_eng'=>'Ashad',
            'name_eng_eng'=>'mid-June to mid-July',
            'trim_id'=>3,
            'status'=>1,
        ]);
    }
}
