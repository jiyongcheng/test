<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Pet::class, function (Faker\Generator $faker) {

    $dogBreeds = array(
        'Bichon Frise',
        'Border Collie',
        'Brittany',
        'Golden Retriever',
        'Hamiltonstovare',
        'Japanese Spitz',
        'Maltese',
        'Welsh Corgi'
    );
    return [
        'breed' => $faker->randomElement($dogBreeds),
        'age' => $faker->randomDigitNotNull,
        'name' => $faker->name,
        'price' => $faker->randomFloat(2,0,999999),
        'list_date' => $faker->dateTimeThisYear,
        'sale_date' => $faker->dateTimeThisMonth,
    ];
});
