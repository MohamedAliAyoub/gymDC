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


}
