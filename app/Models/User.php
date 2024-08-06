<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\FormStatusEnum;
use App\Enums\SubscriptionStatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\CheckIn\CheckIn;
use App\Models\CheckIn\CheckInWorkout;
use App\Models\CheckIn\FirstCheckInForm;
use App\Models\Dashboard\Subscription;
use App\Models\Diet\Plan;
use App\Models\Diet\UserPlan;
use App\Models\Exercise\PlanExercise;
use App\Models\Exercise\UserPlanExercise;
use App\Models\Exercise\WeeklyPlan;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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

//    public function setTypeAttribute(UserTypeEnum $type)
//    {
//        $this->attributes['type'] = $type->value;
//    }

    public function plans(): HasManyThrough
    {
        return $this->hasManyThrough(Plan::class, UserPlan::class, 'user_id', 'id', 'id', 'plan_id');
    }

    public function plan_exercises(): HasManyThrough
    {
        return $this->hasManyThrough(PlanExercise::class, UserPlanExercise::class, 'user_id', 'id', 'id', 'plan_id');
    }


    public function weekly_plan(): HasManyThrough
    {
        return $this->hasManyThrough(WeeklyPlan::class, UserPlanExercise::class, 'user_id', 'id', 'id', 'weekly_plan_id');
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

    public function nutritionSubscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'nutrition_coach_id');
    }

    public function workoutSubscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'workout_coach_id');
    }

    public function firstCheckInForm(): HasOne
    {
        return $this->hasOne(FirstCheckInForm::class);
    }

    public function checkIn(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }

    public function checkInWorkout(): HasMany
    {
        return $this->hasMany(CheckInWorkout::class);
    }

    public function scopeFirstPlanNeeded($query)
    {
        return $query->whereHas('firstCheckInForm')
            ->whereDoesntHave('checkIn')
            ->whereDoesntHave('checkInWorkout');
    }

    public function scopeUpdateNeeded($query)
    {
        return $query->whereHas('checkIn')
            ->whereHas('checkInWorkout')
            ->whereDoesntHave('plans');
    }

    public function ScopeAllReadyHasPlan($query)
    {
        return $query->whereHas('checkIn')
            ->whereHas('checkInWorkout')
            ->whereHas('plan_exercises')
            ->whereHas('firstCheckInForm');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhere('mobile', 'like', '%' . $search . '%')
            ->orWhere('id', 'like', $search);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getNutritionPlansCount()
    {
        $plan_done = $this->getPlanCount('client.checkIn', 'client.checkInWorkout', 'client.firstCheckInForm');
        $plan_done_this_month = $this->getPlanCount('client.checkIn', 'client.checkInWorkout', 'client.firstCheckInForm', true);
        $plan_needed = $this->getPlanCount('client.checkIn', 'client.checkInWorkout', 'client.firstCheckInForm', false, true);
        $this_month_plan_needed = $this->getPlanCount('client.checkIn', 'client.checkInWorkout', 'client.firstCheckInForm', true, true);

        return [
            'plan_done' => $plan_done,
            'plan_done_this_month' => $plan_done_this_month,
            'plan_needed' => $plan_needed,
            'this_month_plan_needed' => $this_month_plan_needed,
            'full_plans' => $plan_done + $plan_needed,
            'full_plans_this_month' => $plan_done_this_month + $this_month_plan_needed
        ];
    }

    private function getPlanCount(string $relation1, string $relation2, string $relation3, bool $thisMonth = false, bool $doesntHave = false)
    {
        $query = $this->nutritionSubscriptions();

        if ($doesntHave) {
            $query->whereDoesntHave($relation1)
                ->whereDoesntHave($relation2)
                ->whereDoesntHave($relation3);
        } else {
            $query->whereHas($relation1)
                ->whereHas($relation2)
                ->whereHas($relation3);
        }

        if ($thisMonth) {
            $query->where('created_at', '>=', now()->startOfMonth());
        }

        return $query->with(['client' => function ($query) use ($thisMonth) {
            $query->withCount(['weekly_plan as plan_exercises_count' => function ($query) use ($thisMonth) {
                if ($thisMonth) {
                    $query->whereHas('userPlanExercises', function ($query) {
                        $query->where('created_at', '>=', now()->startOfMonth());
                    });
                }
            }]);
        }])->get()->sum('client.plan_exercises_count');
    }

    public function getWorkoutPlans(): array
    {
        $plan_done = $this->getWorkoutPlanCount('client.checkIn', 'client.checkInWorkout', 'client.firstCheckInForm');
        $plan_done_this_month = $this->getWorkoutPlanCount('client.checkIn', 'client.checkInWorkout', 'client.firstCheckInForm', true);
        $plan_needed = $this->getWorkoutPlanCount('client.checkIn', 'client.checkInWorkout', 'client.firstCheckInForm', false, true);
        $this_month_plan_needed = $this->getWorkoutPlanCount('client.checkIn', 'client.checkInWorkout', 'client.firstCheckInForm', true, true);

        return [
            'plan_done' => $plan_done,
            'plan_done_this_month' => $plan_done_this_month,
            'plan_needed' => $plan_needed,
            'this_month_plan_needed' => $this_month_plan_needed,
            'full_plans' => $plan_done + $plan_needed,
            'full_plans_this_month' => $plan_done_this_month + $this_month_plan_needed
        ];
    }

    private function getWorkoutPlanCount(string $relation1, string $relation2, string $relation3, bool $thisMonth = false, bool $doesntHave = false)
    {
        $query = $this->workoutSubscriptions();

        if ($doesntHave) {
            $query->whereDoesntHave($relation1)
                ->whereDoesntHave($relation2)
                ->whereDoesntHave($relation3);
        } else {
            $query->whereHas($relation1)
                ->whereHas($relation2)
                ->whereHas($relation3);
        }

        if ($thisMonth) {
            $query->where('created_at', '>=', now()->startOfMonth());
        }

        return $query->with(['client' => function ($query) use ($thisMonth) {
            $query->withCount(['weekly_plan as plan_exercises_count' => function ($query) use ($thisMonth) {
                if ($thisMonth) {
                    $query->whereHas('userPlanExercises', function ($query) {
                        $query->where('created_at', '>=', now()->startOfMonth());
                    });
                }
            }]);
        }])->get()->sum('client.plan_exercises_count');
    }
}
