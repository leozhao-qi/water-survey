<?php

use Carbon\Carbon;
use App\Recommendation;
use Illuminate\Database\Seeder;

class RecommendationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $recommendations = [
            [ 
                'name' => [
                    'en' => 'Has met all objectives of this lesson package',
                    'fr' => 'f Has met all objectives of this lesson package'
                ], 
                'code' => 'A', 
                'completion' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [ 
                'name' => [
                    'en' => 'Has not met all the objectives. Recommend deferral. See Evaluation Details.',
                    'fr' => 'f Has not met all the objectives. Recommend deferral. See Evaluation Details.'
                ], 
                'code' => 'B', 
                'completion' => 1,  
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [ 
                'name' => [
                    'en' => 'Exempt – See Evaluation details (to be approved by Manager)',
                    'fr' => 'f Exempt – See Evaluation details (to be approved by Manager)'
                ], 
                'code' => 'C', 
                'completion' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ];

        foreach ($recommendations as $recommendation) {
            Recommendation::create($recommendation);
        }
    }
}
