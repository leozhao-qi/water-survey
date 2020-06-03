<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Logbook;
use App\LogbookCategory;
use Faker\Generator as Faker;

function getUserId()
{
    $userIds = User::all()->filter(function ($user) {
        return $user->hasRole(['supervisor', 'apprentice']);
    })->pluck('id')->toArray();

    return $userIds[array_rand($userIds)];
}

function getLogbookCategoryId($userId)
{
    $user = User::find($userId);

    if ($user->hasRole(['supervisor'])) {
        $logbookCategoryIds = LogbookCategory::all()->filter(function ($category) {
            return $category->supervisor_only;
        })->pluck('id')->toArray();
    } else {
        $logbookCategoryIds = LogbookCategory::all()->filter(function ($category) {
            return !$category->supervisor_only;
        })->pluck('id')->toArray();
    }

    return $logbookCategoryIds[array_rand($logbookCategoryIds)];
}

$factory->define(Logbook::class, function (Faker $faker) {
    return [
        'user_id' => $userId = getUserId(),
        'logbook_category_id' => getLogbookCategoryId($userId),
        'created' => $faker->dateTimeBetween($startDate = '-5 months', $endDate = 'now'),
        'event_description' => $faker->sentence,
        'details_of_event' => "<p>{$faker->paragraph}</p><p>{$faker->paragraph}</p><p>{$faker->paragraph}</p><p>{$faker->paragraph}</p><p>{$faker->paragraph}</p><p>{$faker->paragraph}</p>",
    ];
});
