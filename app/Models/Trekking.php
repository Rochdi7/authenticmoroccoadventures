<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;

class Trekking extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'trekking';

    protected $fillable = [
        'title',
        'slug',
        'overview',
        'highlights',
        'duration',
        'group_size',
        'age_range',
        'base_price',
        'difficulty_level',
        'max_altitude',
        'bestseller_flag',
        'free_cancellation_flag',
        'booked_count',
        'map_frame',
        'included',
        'excluded',
        'itinerary',
        'languages',
        'category_id',
        'location_id',
    ];

    protected $casts = [
        'highlights' => 'array',
        'included' => 'array',
        'excluded' => 'array',
        'itinerary' => 'array',
        'languages' => 'array',
    ];

    /**
     * Get the trekking category.
     */
    public function category()
    {
        return $this->belongsTo(TrekkingCategory::class, 'category_id');
    }

    /**
     * Get the location for the trekking.
     */
    public function location()
    {
        return $this->belongsTo(\App\Models\Location::class, 'location_id');
    }

    /**
     * Reviews associated with this trekking.
     */
    public function reviews()
    {
        return $this->morphMany(\App\Models\Review::class, 'reviewable');
    }

    /**
     * Get languages as comma-separated string.
     *
     * @return string
     */
    public function getLanguagesFormattedAttribute(): string
    {
        $languages = $this->languages;

        if (is_array($languages) && count($languages) > 0) {
            return implode(', ', $languages);
        }

        return 'English'; // default if empty
    }

    /**
     * Register Media Collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->acceptsFile(function ($file) {
                return in_array($file->mimeType, [
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                ]);
            })
            ->useDisk('public');
    }

    /**
     * Register Media Conversions.
     *
     * @param Media|null $media
     * @return void
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('slider')
            ->width(1200)
            ->height(800)
            ->sharpen(10)
            ->nonQueued();

        // Card-sized WebP variants for responsive srcset on listing/grid cards.
        // Cards render at ~560x400 CSS px, so 560w covers 1x and 1120w covers 2x
        // DPR. Serving these instead of the full-size original is the single
        // biggest image-weight saving on listing pages.
        $this->addMediaConversion('card')
            ->format('webp')
            ->width(560)
            ->height(400)
            ->quality(82)
            ->nonQueued();

        $this->addMediaConversion('card2x')
            ->format('webp')
            ->width(1120)
            ->height(800)
            ->quality(78)
            ->nonQueued();
    }
}
