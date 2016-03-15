<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    //'id' => 1,
    'name' => $faker->name,
    'description' => $faker->sentence(12, true),
    'homepage' => $faker->url,
    'public' => $faker->boolean(true),
    'created' => $faker->unixTime($max = 'now'),
    'modified' => $faker->unixTime($max = 'now'),
    'identifier' => $faker->name,
    'logo' => null,
    'logoname' => null,
    'owner_id' => 1,
    'updater_id' => 1,
];
