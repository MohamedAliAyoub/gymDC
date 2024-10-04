<?php

namespace App\Models;

use App\Enums\FormStatusEnum;
use App\Enums\PackagesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * Class UserDetails
 *
 * This class represents the additional details for a user in the system.
 *
 * @package App\Models
 */
class UserDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subscription_status',
        'packages',
        'form_status',
        'age',
        'weight',
        'height',
        'vib',
        'nutrition_coach_id',
        'work_out_coach_id',
        'in_body_image',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'subscription_status' => 'integer',
        'packages' => 'integer',
        'form_status' => 'integer',
        'age' => 'integer',
        'weight' => 'float',
        'height' => 'float',
        'vib' => 'integer',
        'nutrition_coach_id' => 'integer',
        'work_out_coach_id' => 'integer',
        'in_body_image' => 'string',
    ];

    protected $appends = ['in_body_url'];

    /**
     * Get the nutrition coach associated with the user details.
     *
     * @return BelongsTo Returns the relationship object for the nutrition coach.
     */
    public function nutritionCoach() : BelongsTo
    {
        return $this->belongsTo(User::class, 'nutrition_coach_id');
    }

    /**
     * Get the workout coach associated with the user details.
     *
     * @return BelongsTo Returns the relationship object for the workout coach.
     */
    public function workoutCoach()  : BelongsTo
    {
        return $this->belongsTo(User::class, 'work_out_coach_id');
    }

    /**
     * Get the user associated with the user details.
     *
     * @return BelongsTo Returns the relationship object for the user.
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getInBodyUrlAttribute() : ?string
    {
        return $this->in_body_image ? Storage::disk('public')->url($this->in_body_image) : null;
    }

    public function getPackageValueAttribute(): ?string
    {
        return PackagesEnum::fromValue($this->packages)?->name;
    }

    public function getSubscriptionValueAttribute(): ?string
    {
        return FormStatusEnum::fromValue($this->form_status)?->name;
    }


}
