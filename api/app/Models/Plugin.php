<?php

namespace App\Models;

use Database\Factories\PluginFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['website_id', 'name', 'current_version', 'latest_version'])]
class Plugin extends Model
{
    /** @use HasFactory<PluginFactory> */
    use HasFactory;
}
