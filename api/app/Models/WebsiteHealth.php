<?php

namespace App\Models;

use App\Enums\UptimeStatus;
use Database\Factories\WebsiteHealthFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'website_id',
    'ssl_expires_at',
    'wordpress_version',
    'php_version',
    'uptime_status',
    'last_backup_at',
    'last_checked_at',
])]
class WebsiteHealth extends Model
{
    /** @use HasFactory<WebsiteHealthFactory> */
    use HasFactory;

    protected $table = 'website_health';

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
            'ssl_expires_at' => 'datetime',
            'last_backup_at' => 'datetime',
            'last_checked_at' => 'datetime',
            'uptime_status' => UptimeStatus::class,
        ];
    }
}
