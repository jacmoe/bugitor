<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    //'id' => 1,
    'tracker_id' => 1,
    'project_id' => 1,
    'subject' => $faker->name,
    'description' => $faker->sentence(12, true),
    'issue_category_id' => 1,
    'user_id' => 1,
    'issue_priority_id' => 1,
    'version_id' => 1,
    'assigned_to' => 1,
    'created' => $faker->unixTime($max = 'now'),
    'modified' => $faker->unixTime($max = 'now'),
    'done_ratio' => 0,
    'status' => 1,
    'closed' => 0,
    'pre_done_ratio' => 0,
    'updated_by' => 1,
    'last_comment' => 1,
];
