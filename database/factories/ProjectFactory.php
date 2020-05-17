<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Project;
use App\Task;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
    ];
});

$factory->afterCreatingState(Project::class, 'withTasks', function (Project $project) {
    $project->tasks()->saveMany(factory(Task::class, 3)->create());
});
