<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Example;
use Illuminate\Support\Facades\DB;

class DataVisualizationController extends Controller
{
     public function index()
    {
        return view('index');
    }

	
}
