<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\FormStatusEnum;
use App\Enums\SubscriptionStatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\Dashboard\Subscription;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable implements JWTSubject, CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'mobile',
        'google_id',
        'type',
        'subscription_status',
        'packages',
        'form_status',
        'subscription_status',
        'packages',
        'form_status',
        'age',
        'weight',
        'height',
        'vib',
        'nutrition_coach_id',
        'work_out_coach_id',
    ];

    protected $appends = ['image_url'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'type' => 'integer',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }


    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function workoutCoach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'work_out_coach_id');
    }

    public function nutritionCoach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nutrition_coach_id');
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : Storage::disk('public')->url('users/default.jpg');
    }

    public function getTypeAttribute($value): UserTypeEnum
    {
        return UserTypeEnum::fromValue($value);
    }

    public function setTypeAttribute(UserTypeEnum $type)
    {
        $this->attributes['type'] = $type->value;
    }

    public function getFormStatusAttribute($value): FormStatusEnum
    {
        return FormStatusEnum::fromValue($value);
    }

    public function setFormStatusAttribute(FormStatusEnum $status)
    {
        $this->attributes['status'] = $status->value;
    }

    public function getSubscriptionStatusAttribute($value): SubscriptionStatusEnum
    {
        return SubscriptionStatusEnum::fromValue($value);
    }

    public function setSubscriptionStatusAttribute(SubscriptionStatusEnum $status)
    {
        $this->attributes['status'] = $status->value;
    }

    public function getPackageAttribute($value): SubscriptionStatusEnum
    {
        return SubscriptionStatusEnum::fromValue($value);
    }

    public function setPackageAttribute(SubscriptionStatusEnum $status)
    {
        $this->attributes['status'] = $status->value;
    }

    public function userDetails(): HasMany
    {
        return $this->hasMany(UserDetails::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'client_id')->where('status', SubscriptionStatusEnum::Active);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'client_id');
    }
}
