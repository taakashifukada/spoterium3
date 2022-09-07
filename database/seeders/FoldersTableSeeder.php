<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoldersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("folders")->insert(
        [
            [
                "id"=>1,
                "name"=>"root",
                "created_at"=>now(),
                "updated_at"=>now()
                
            ],
            
            [
                "id"=>2,
                "name"=>"favs",
                "created_at"=>now(),
                "updated_at"=>now()
                
            ]
        ]    
        );
    }
}
