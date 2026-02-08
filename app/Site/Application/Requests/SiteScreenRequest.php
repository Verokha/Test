<?php

namespace App\Site\Application\Requests;

use App\Shared\Application\Rules\DomainRule;
use App\Shared\Application\Rules\TelegramChannelRule;
use App\User\Domain\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @property string $domain
 */
class SiteScreenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->has('site.user_id')) {
            /** @var User $curretUser */
            $curretUser = Auth::user();

            return $curretUser->isSuperAdmin() === true;
        }

        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function validationData(): array
    {
        /**
         * @var array<string, mixed> $validatioData
         */
        $validatioData = $this->input('site', []);

        return $validatioData;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();
        $isSuperAdmin = $currentUser->isSuperAdmin();

        return [
            'domain' => [
                'required',
                // 'unique:sites',
                Rule::unique('sites')->ignore($this->route('site') ?? null),
                new DomainRule,
            ],
            'telegram_channel' => [
                'required',
                // 'unique:sites',
                Rule::unique('sites')->ignore($this->route('site') ?? null),
                new TelegramChannelRule,
            ],
            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],
            'seo' => ['array'],
            'name' => ['required', 'max:255'],
            'enabled' => ['required', 'boolean'],
            'blocked' => $isSuperAdmin
                ? ['required', 'boolean']
                : ['sometimes', 'boolean'],

            'block_reason' => $isSuperAdmin
                ? ['nullable', 'string', 'max:255']
                : ['prohibited'],
        ];
    }

    public function resolvedUserId(): int
    {
        /** @var User $curretUser */
        $curretUser = Auth::user();
        if ($curretUser->isSuperAdmin()) {
            /** @var int $userId */
            $userId = $this->input('site.user_id');

            return $userId;
        }

        return $curretUser->id;
    }
}
