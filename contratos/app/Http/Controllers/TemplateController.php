<?php

// app/Http/Controllers/TemplateController.php
namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index(Request $req)
    {
        $niche = $req->query('niche');
        $q = Template::query();
        if ($niche) $q->where('niche', $niche);
        return $q->get(['id', 'slug', 'name', 'niche', 'fields_schema', 'premium_only']);
    }
    public function show($id)
    {
        return Template::findOrFail($id);
    }
}
