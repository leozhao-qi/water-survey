<?php

use Carbon\Carbon;
use App\LogbookCategory;
use Illuminate\Database\Seeder;

class LogbookCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $catagories = [
            [ 
                'name' => [
                    'en' => 'Theory',
                    'fr' => 'f Theory'
                ], 
                'supervisor_only' => 0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [ 
                'name' => [
                    'en' => 'Practical Application',
                    'fr' => 'f Practical Application'
                ], 
                'supervisor_only' => 0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [ 
                'name' => [
                    'en' => 'Theory/Practical Application',
                    'fr' => 'f Theory/Practical Application'
                ], 
                'supervisor_only' => 0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [ 
                'name' => [
                    'en' => 'Supervisor Comment',
                    'fr' => 'f Supervisor Comment'
                ], 
                'supervisor_only' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ];

        foreach ($catagories as $catagory) {
            LogbookCategory::create($catagory);
        }
    }
}
