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

    protected $fillable = [
        'plan_id',
        'user_id',
        'status',
        'is_work',
        'days',
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

}
