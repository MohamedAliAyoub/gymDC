<?php

namespace App\Models\Dashboard;

use App\Enums\PackagesEnum;
use App\Enums\SubscriptionStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $appends = ['packages_name', 'status_name'];
    protected $fillable = [
        'nutrition_coach_id',
        'workout_coach_id',
        'client_id',
        'sale_id',
        'duration',
        'type',
        'started_at',
        'paid_amount',
        'freeze_start_at',
        'freeze_duration',
        'paid_at',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'started_at' => 'datetime',
        'freeze_start_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the nutrition coach associated with the subscription.
     */
    public function nutritionCoach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nutrition_coach_id');
    }

    /**
     * Get the workout coach associated with the subscription.
     */
    public function workoutCoach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'workout_coach_id');
    }

    /**
     * Get the client associated with the subscription.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the sale associated with the subscription.
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sale_id');
    }


    public function getPackagesNameAttribute(): string
    {
        return PackagesEnum::fromValue($this->attributes['type'])->name;
    }

    public function getStatusNameAttribute(): string
    {
        return SubscriptionStatusEnum::fromValue($this->attributes['status'])->name;
    }

    protected static function booted()
    {
        static::created(function ($userSubscription) {
            if ($userSubscription->paid_amount != null) {
                SubscriptionLogs::query()->create([
                    'sale_id' => $userSubscription->sale_id,
                    'client_id' => $userSubscription->client_id,
                    'log' => 'Paid Amount changed from null to' . $userSubscription->paid_amount,
                ]);
            }
        });

        static::updated(function ($subscription) {
            $changes = $subscription->getChanges();
            $log = 'Subscription updated: ';

            foreach ($changes as $field => $newValue) {
                $oldValue = $subscription->getOriginal($field);

                if ($field === 'sale_id' || $field === 'client_id') {
                    $oldValue = User::query()->find($oldValue)->name;
                    $newValue = User::query()->find($newValue)->name;
                }

                // Replace underscores with spaces
                $field = str_replace('_', ' ', $field);

                // If the field ends with '_id', remove the last 3 characters
                if (str_ends_with($field, 'id')) {
                    $field = substr($field, 0, -2);
                }

                $log .= "{$field} changed from {$oldValue} to {$newValue}, ";
            }

            SubscriptionLogs::query()->create([
                'sale_id' => $subscription->sale_id,
                'client_id' => $subscription->client_id,
                'log' => rtrim($log, ', '),
            ]);
        });
    }


}
