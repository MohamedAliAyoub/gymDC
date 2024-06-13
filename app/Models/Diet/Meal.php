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
        'calories'
    ];
    protected $hidden = [
        'pivot'
    ];

    /**
     * Get the plan that the meal belongs to.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * The items that belong to the meal.
     */
    public function items()
    {
        return $this->belongsToMany(Item::class , 'meal_items' );
    }

    /**
     * Get the note for the meal.
     */
    public function note()
    {
        return $this->hasOne(Note::class);
    }

    public static function hasEatenMealToday($mealId)
    {
        return UserMeal::query()
            ->where('user_id', auth()->id())
            ->where('meal_id', $mealId)
            ->whereDate('created_at', now()->toDateString())
            ->first()->is_eaten ?? false;
    }

}
