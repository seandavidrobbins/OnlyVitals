<?php

namespace App\Http\Resources\Api\V1;

use App\Models\WebsiteHealth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin WebsiteHealth
 */
class WebsiteHealthResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ssl_expires_at' => $this->ssl_expires_at,
            'wordpress_version' => $this->wordpress_version,
            'php_version' => $this->php_version,
            'uptime_status' => $this->uptime_status->value,
            'last_backup_at' => $this->last_backup_at,
            'last_checked_at' => $this->last_checked_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
