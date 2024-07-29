<?php

namespace App\Models\CheckIn;

use App\Models\BodyImage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class FirstCheckInForm extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['in_body_image_url', 'injuries_image_url'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bodyImages(): HasMany
    {
        return $this->hasMany(BodyImage::class, 'first_check_in_form_id');
    }

    public function getInBodyImageUrlAttribute(): ?string
    {
        return $this->in_body_image ? Storage::disk('public')->url($this->in_body_image) : null;
    }

    public function getInjuriesImageUrlAttribute(): ?string
    {
        return $this->injuries_image ? Storage::disk('public')->url($this->injuries_image) : null;
    }
}
