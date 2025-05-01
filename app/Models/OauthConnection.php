<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\OauthConnectionFactory;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $provider_id
 * @property Collection<string, mixed> $data
 * @property string|null $token
 * @property string|null $refresh_token
 * @property CarbonImmutable|null $expires_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read User $user
 *
 * @method static \Database\Factories\OauthConnectionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthConnection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthConnection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthConnection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthConnection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthConnection whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthConnection whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthConnection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthConnection whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthConnection whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthConnection whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthConnection whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthConnection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthConnection whereUserId($value)
 *
 * @mixin \Eloquent
 */
final class OauthConnection extends Model
{
    /** @use HasFactory<OauthConnectionFactory> */
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => AsCollection::class,
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that the OAuth connection belongs to.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
