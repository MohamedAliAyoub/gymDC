<?php

namespace App\Models\Exercise;

use App\Models\Diet\Plan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Exercise
 *
 * @property string $name
 * @property bool $status
 *
 * @package App\Models\Exercise
 */
class Exercise extends Model
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
    public function plan():BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
