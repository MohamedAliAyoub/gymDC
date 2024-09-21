<?php

namespace App\Models\Diet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    protected $appends = [
        'total_calories',
        'total_carbohydrate',
        'total_protein',
        'total_fat'
    ];


    public function getTotalCaloriesAttribute()
    {
        return $this->meals->sum('calories');
    }

    public function getTotalCarbohydrateAttribute()
    {
        return $this->meals->sum('carbohydrate');
    }

    public function getTotalProteinAttribute()
    {
        return $this->meals->sum('protein');
    }

    public function getTotalFatAttribute()
    {
        return $this->meals->sum('fat');
    }


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

    public function userPlans(): HasMany
    {
        return $this->hasMany(UserPlan::class);
    }

    public function userPlan  (): HasOne
    {
        return $this->hasOne(UserPlan::class);
    }


    public function loadPlanDetails(): Plan
    {
        return $this->load(['userPlan','meals' => function ($q) {
            $q->with(['items' => function ($q) {
                $q->with(['standard' => function ($q) {
                    $q->with('standardType');
                }]);
            }]);
        }]);
    }


    //scope search by name
    public function scopeSearchByName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    public function scopeFilterByAttributes($query, $attributes)
    {
        $filteredPlans = $query->get()->filter(function ($plan) use ($attributes) {
            foreach ($attributes as $attribute => $value) {
                if ($plan->$attribute != $value) {
                    return false;
                }
            }
            return true;
        });

        return self::query()->whereIn('id', $filteredPlans->pluck('id'));
    }
}
