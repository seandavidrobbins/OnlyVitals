<?php

namespace App\Models;

use App\Enums\CmsType;
use Database\Factories\WebsiteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['user_id', 'name', 'url', 'cms_type', 'notes'])]
class Website extends Model
{
    /** @use HasFactory<WebsiteFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne<WebsiteHealth, $this>
     */
    public function health(): HasOne
    {
        return $this->hasOne(WebsiteHealth::class);
    }

    /**
     * @return HasMany<Plugin, $this>
     */
    public function plugins(): HasMany
    {
        return $this->hasMany(Plugin::class);
    }

    /**
     * @return HasMany<SecurityIssue, $this>
     */
    public function securityIssues(): HasMany
    {
        return $this->hasMany(SecurityIssue::class);
    }

    /**
     * @return HasMany<MaintenanceTask, $this>
     */
    public function maintenanceTasks(): HasMany
    {
        return $this->hasMany(MaintenanceTask::class);
    }

    /**
     * @return HasOne<CoreWebVital, $this>
     */
    public function vitals(): HasOne
    {
        return $this->hasOne(CoreWebVital::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'cms_type' => CmsType::class,
        ];
    }
}
