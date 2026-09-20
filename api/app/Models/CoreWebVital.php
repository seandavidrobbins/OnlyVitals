<?php

namespace App\Models;

use Database\Factories\CoreWebVitalFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['website_id', 'lcp', 'inp', 'cls', 'measured_at'])]
class CoreWebVital extends Model
{
    /** @use HasFactory<CoreWebVitalFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Website, $this>
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'lcp' => 'decimal:3',
            'inp' => 'decimal:2',
            'cls' => 'decimal:3',
            'measured_at' => 'datetime',
        ];
    }
}
