<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ExampleTaskResource;
use App\Models\ExampleTask;

class ExampleTaskController extends Controller
{
    public function fetchExampleTasks(){
        return ExampleTaskResource::collection(ExampleTask::all());
    }
}
