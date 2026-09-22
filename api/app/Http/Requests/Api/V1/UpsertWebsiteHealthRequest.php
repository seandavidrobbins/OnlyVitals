<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\UptimeStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertWebsiteHealthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('website'));
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ssl_expires_at' => ['nullable', 'date'],
            'wordpress_version' => ['nullable', 'string', 'max:255'],
            'php_version' => ['nullable', 'string', 'max:255'],
            'uptime_status' => ['required', Rule::enum(UptimeStatus::class)],
            'last_backup_at' => ['nullable', 'date'],
            'last_checked_at' => ['nullable', 'date'],
        ];
    }
}
