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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public static function assignPlanToUsers($userIds, $planId , $isWork = 1)
    {
        $userPlans = [];
        foreach ($userIds as $userId) {
            $userPlans[] = self::create([
                'user_id' => $userId,
                'plan_id' => $planId,
                'status' => true,
                'is_work' => $isWork,
            ]);
        }

        return $userPlans;
    }
}
