<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExampleTaskResource;
use App\Models\ExampleTask;

class ExampleTaskController extends Controller
{
    /**
     * Returns a list of all available example tasks
     */
    public function fetchExampleTasks()
    {
        return ExampleTaskResource::collection(ExampleTask::all());
    }
}
