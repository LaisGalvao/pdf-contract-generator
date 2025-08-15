<?php
// app/Models/Contract.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Contract extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'user_id', 'template_id', 'theme_id', 'data', 'pdf_path', 'status', 'signature_meta'];
    protected $casts = ['data' => 'array', 'signature_meta' => 'array'];
}
