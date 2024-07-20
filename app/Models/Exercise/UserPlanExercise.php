<?php

namespace App\Models\Exercise;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserPlanExercise
 *
 * @property int $plan_id
 * @property int $user_id
 * @property string $status
 * @property bool $is_work
 * @property string $days
 * @property string $created_at
 * @property string $updated_at
 * @property PlanExercise $plan
 * @property User $user
 *
 * @package App\Models\Exercise
 */
class UserPlanExercise extends Model
{
    use HasFactory;

    const SUNDAY = 0;
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;

    protected $appends = ['day_names'];


    protected $fillable = [
        'plan_id',
        'user_id',
        'status',
        'is_work',
        'days',
        'weekly_plan_id'
    ];
    protected $casts = [
        'days' => 'array',
        'is_work' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Get the plan that the user plan exercise belongs to.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanExercise::class, 'plan_id');
    }

    /**
     * Get the user that the user plan exercise belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDayNamesAttribute()
    {
        $dayNames = [
            self::SUNDAY => 'Sunday',
            self::MONDAY => 'Monday',
            self::TUESDAY => 'Tuesday',
            self::WEDNESDAY => 'Wednesday',
            self::THURSDAY => 'Thursday',
            self::FRIDAY => 'Friday',
            self::SATURDAY => 'Saturday',
        ];

        return array_map(function ($day) use ($dayNames) {
            return $dayNames[$day];
        }, $this->days);
    }

    /**
     * Get the plan of today
     *
     * @return UserPlanExercise|string
     */
    public static function getPlanOfToday()
    {
        return UserPlanExercise::where('user_id', auth()->id())
            ->whereJsonContains('days', (string)now()->dayOfWeek)
            ->where('is_work', true)
            ->where('status', true)
            ->first();
    }

    /**
     * Get the plan by date
     *
     * @return UserPlanExercise|string
     */
    public static function getPlanByDate($date)
    {
        return UserPlanExercise::where('user_id', auth()->id())
            ->whereJsonContains('days', (string)$date->dayOfWeek)
            ->where('is_work', true)
            ->where('status', true)
            ->first();
    }

    public static function assignPlanToUsers($userIds, $planId, $isWork = 1 , $weekly_plan_id , $days): array
    {
        $userPlans = [];
        foreach ($userIds as $userId) {
            $userPlans[] = self::query()->updateOrCreate(
                [
                    'user_id' => $userId,
                    'plan_id' => $planId
                ],
                [
                    'user_id' => $userId,
                    'plan_id' => $planId,
                    'status' => true,
                    'is_work' => $isWork,
                    'weekly_plan_id' => $weekly_plan_id,
                    'days' => $days,
                ]);
        }

        return $userPlans;
    }

}
