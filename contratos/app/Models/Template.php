<?php
// app/Models/Template.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = ['slug', 'name', 'niche', 'fields_schema', 'blade_view', 'premium_only'];
    protected $casts = ['fields_schema' => 'array', 'premium_only' => 'bool'];
}
