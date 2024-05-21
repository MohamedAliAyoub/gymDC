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
}
