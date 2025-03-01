<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

final class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:accounts,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:15'],
            'role' => ['nullable', 'string', 'max:50'],
            'is_active' => ['required', 'boolean'],
            'person_id' => ['nullable', 'integer'],
            'person_type' => ['nullable', 'string'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo']) && $input['photo'] instanceof UploadedFile) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email && $user->hasVerifiedEmail()) {
            $validated = Validator::make($input, [
                'name' => ['required', 'string'],
                'email' => ['required', 'string'],
            ])->validate();

            /** @var array{name: string, email: string} $data */
            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            $this->updateVerifiedUser($user, $data);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'role' => $input['role'],
                'is_active' => $input['is_active'],
                'person_id' => $input['person_id'],
                'person_type' => $input['person_type'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    private function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
