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
        'description' => $faker->paragraph,
        'first_text' => $faker->sentence,
        'final_text' => $faker->sentence,
        'start_date' => $faker->date(),
        'end_date' => $faker->date()

    ];
});

$factory->define(Category::class, function (Faker $faker) {
    return [
        'poll_id' => $faker->numberBetween(1, 33),
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'dependant_question_id' => 1,
        'dependant_option_id' => 2,
        'parent_id' => 0
    ];
});

$factory->define(Question::class, function (Faker $faker) {
    return [
        'poll_id' => $faker->numberBetween(3, 33),
        'text' => $faker->sentence,
        'description' => $faker->paragraph,
        'answer_type_id' => $faker->numberBetween(1,3),
        'dependant_option_id' => 2,
        'parent_id' => 0
    ];
});

$factory->define(Option::class, function (Faker $faker) {
    return [
        'question_id' => $faker->numberBetween(3, 20),
        'text' => $faker->sentence,
        'description' => $faker->paragraph
    ];
});