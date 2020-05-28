<?php

use App\Status;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [ 
                'name' => [
                    'en' => 'Incomplete',
                    'fr' => 'f Incomplete'
                ], 
                'code' => 'incomplete', 
                'completion' => 0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [ 
                'name' => [
                    'en' => 'Complete EG3',
                    'fr' => 'f Complete EG3'
                ], 
                'code' => 'complete_eg3',  
                'completion' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [ 
                'name' => [
                    'en' => 'Complete EG4',
                    'fr' => 'f Complete EG4'
                ], 
                'code' => 'complete_eg4',  
                'completion' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [ 
                'name' => [
                    'en' => 'Deferred',
                    'fr' => 'f Deferred'
                ], 
                'code' => 'deferred', 
                'completion' => 0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [ 
                'name' => [
                    'en' => 'Exempt',
                    'fr' => 'f Exempt'
                ], 
                'code' => 'exempt',  
                'completion' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
