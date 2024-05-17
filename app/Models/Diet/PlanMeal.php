<?php

namespace App\Models\Diet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PlanMeal
 *
 * @property int $plan_id
 * @property int $meal_id
 * @property bool $status
 *
 * @package App\Models\Diet
 */
class PlanMeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'meal_id',
        'status',
    ];

    /**
     * Get the plan that the plan meal belongs to.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the meal that the plan meal belongs to.
     */
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
