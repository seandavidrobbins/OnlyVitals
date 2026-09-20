<?php

namespace App\Models;

use Database\Factories\PluginFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['website_id', 'name', 'current_version', 'latest_version'])]
class Plugin extends Model
{
    /** @use HasFactory<PluginFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Website, $this>
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
