<?php

namespace App\Models\Diet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public  function meals()
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


}
