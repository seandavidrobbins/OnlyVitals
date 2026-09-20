<?php

namespace App\Models;

use App\Enums\IssueStatus;
use App\Enums\Severity;
use Database\Factories\SecurityIssueFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['website_id', 'severity', 'title', 'description', 'status'])]
class SecurityIssue extends Model
{
    /** @use HasFactory<SecurityIssueFactory> */
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
            'severity' => Severity::class,
            'status' => IssueStatus::class,
        ];
    }
}
