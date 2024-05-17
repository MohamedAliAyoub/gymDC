<?php

namespace App\Models\Diet;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Note
 *
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property bool $status
 * @property int $plan_id
 * @property int $meal_id
 *
 * @package App\Models\Diet
 */
class Note extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'status',
        'plan_id',
        'meal_id',
    ];

    /**
     * Get the user that owns the note.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan that the note belongs to.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the meal that the note belongs to.
     */
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
