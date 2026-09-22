<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\CmsType;
use App\Models\Website;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWebsiteRequest extends FormRequest
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
        /** @var Website $website */
        $website = $this->route('website');

        return [
            'name' => ['required', 'string', 'max:255'],
            'url' => [
                'required',
                'url',
                'max:255',
                Rule::unique('websites', 'url')
                    ->where('user_id', $this->user()->id)
                    ->ignore($website),
            ],
            'cms_type' => ['required', Rule::enum(CmsType::class)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
