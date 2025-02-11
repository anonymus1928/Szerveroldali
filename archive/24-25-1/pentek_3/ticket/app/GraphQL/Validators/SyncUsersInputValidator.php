<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

final class SyncUsersInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'ticketId' => [
                'integer',
                'exists:tickets,id',
            ],
            'up' => [
                'array'
            ],
            'up.*' => [
                'integer',
                'exists:users,id'
            ],
            'down' => [
                'array'
            ],
            'down.*' => [
                'integer',
                'exists:users,id'
            ],
        ];
    }
}
