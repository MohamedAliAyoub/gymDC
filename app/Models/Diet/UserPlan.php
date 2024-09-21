<?php

namespace App\Models\Diet;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'is_work',
    ];
    protected $casts = [
        'status' => 'boolean',
        'is_work' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public static function assignPlanToUsers($userId, $planId, $isWork = 1)
    {
            $userPlans = self::create([
                'user_id' => $userId,
                'plan_id' => $planId,
                'status' => true,
                'is_work' => $isWork,
            ]);

        return $userPlans;
    }

    // In your UserPlan model
    public function loadUserPlanDetails(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->with(['plan' => function ($q) {
            $q->with(['meals' => function ($q) {
                $q->with(['items' => function ($q) {
                    $q->with(['standard' => function ($q) {
                        $q->with('standardType');
                    }]);
                }]);
            }]);
        }]);
    }
}
