<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Option;
use App\Poll;
use App\Question;
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Poll::class, function (Faker $faker) {
    return [
        'app_id' => $faker->numberBetween(1, 2),
        'title' => $faker->sentence,
        'description' => $faker->sentence,
        'first_text' => $faker->sentence,
        'final_text' => $faker->sentence,
        'start_date' => $faker->date(),
        'end_date' => $faker->date()

    ];
});

$factory->define(Category::class, function (Faker $faker) {

    return [
        'poll_id' => function(){
            return factory('App\Poll')->create()->id;
        },
        'title' => $faker->sentence,
        'description' => $faker->sentence,
        'dependant_question_id' => 0,
        'dependant_option_id' => 0,
        'parent_id' => 0
    ];
});

$factory->define(Question::class, function (Faker $faker) {

    return [
        'poll_id' => function () {
            return factory('App\Poll')->create()->id;
        },
        'text' => $faker->sentence,
        'description' => $faker->sentence,
        'answer_type_id' => $faker->numberBetween(1, 4),
        'dependant_option_id' => 0,
        'parent_id' => 0
    ];
});

$factory->define(Option::class, function (Faker $faker) {

    return [
        'question_id' => function(){
            return factory('App\Question')->create()->id;
        },
        'text' => $faker->sentence,
        'description' => $faker->sentence
    ];
});

$factory->define(\App\Answer::class, function (Faker $faker) {
    $questionId = function (){return factory('App\Question')->create()->id;};
    $optionId = function () use ($questionId){factory('App\Option')->create(['question_id' => $questionId])->id;};
    return [
        'user_id' => 2,
        'app_id' => 2,
        'question_id' => $questionId,
        'answer' => $optionId,
    ];
});