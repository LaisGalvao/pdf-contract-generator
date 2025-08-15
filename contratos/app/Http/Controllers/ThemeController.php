<?php

// app/Http/Controllers/ThemeController.php
namespace App\Http\Controllers;

use App\Models\Theme;

class ThemeController extends Controller
{
    public function index()
    {
        return Theme::all(['id', 'slug', 'name', 'plan']);
    }
}
