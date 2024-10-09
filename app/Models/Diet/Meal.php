<?php

namespace App\Models\Diet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Meal
 *
 * @property string $name
 * @property bool $status
 *
 * @package App\Models\Diet
 */
class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'calories',
        'fat',
        'carbohydrate',
        'protein',
    ];
    protected $hidden = [
        'pivot'
    ];

    protected $appends = [
        'is_eaten'
    ];

    /**
     * Get the plan that the meal belongs to.
     */
    public function plan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * The items that belong to the meal.
     */
    public function items(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'meal_items');
    }

    /**
     * Get the note for the meal.
     */
    public function note(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Note::class);
    }

    public static function hasEatenMealToday($mealId): bool
    {
        return UserMeal::query()
            ->where('user_id', auth()->id())
            ->where('meal_id', $mealId)
            ->whereDate('created_at', now()->toDateString())
            ->first()->is_eaten ?? false;
    }
    public function getIsEatenDoneAttribute(): bool
    {
        return self::hasEatenMealToday($this->id);
    }

    public function getCountDoneCaloriesAttribute(): int
    {
        return $this->is_eaten_done ? $this->calories : 0;
    }

}
