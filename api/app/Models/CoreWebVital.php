<?php

namespace App\Models;

use Database\Factories\CoreWebVitalFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['website_id', 'lcp', 'inp', 'cls', 'measured_at'])]
class CoreWebVital extends Model
{
    /** @use HasFactory<CoreWebVitalFactory> */
    use HasFactory;

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
