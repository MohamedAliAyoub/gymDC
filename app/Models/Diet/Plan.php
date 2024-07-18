<?php

namespace App\Models\Diet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Plan
 *
 * @property string $name
 * @property bool $status
 *
 * @package App\Models\Diet
 */
class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];
    protected $hidden = [
        'pivot'
    ];

    public function meals(): BelongsToMany
    {
        return $this->belongsToMany(Meal::class, 'plan_meals');
    }

    /**
     * Get the note for the plan.
     */
    public function note()
    {
        return $this->hasOne(Note::class);
    }

    public function userPlans():HasMany
    {
        return $this->hasMany(UserPlan::class);
    }


    public function loadPlanDetails(): Plan
    {
        return $this->load(['meals' => function ($q) {
            $q->with(['items' => function ($q) {
                $q->with(['standard' => function ($q) {
                    $q->with('standardType');
                }]);
            }]);
        }]);
    }



}
