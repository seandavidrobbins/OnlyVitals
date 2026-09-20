<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Database\Factories\MaintenanceTaskFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['website_id', 'title', 'due_at', 'status', 'completed_at'])]
class MaintenanceTask extends Model
{
    /** @use HasFactory<MaintenanceTaskFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'completed_at' => 'datetime',
            'status' => TaskStatus::class,
        ];
    }
}
