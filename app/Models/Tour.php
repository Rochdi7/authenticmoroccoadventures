<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Tour extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'overview',
        'highlights',
        'duration',
        'group_size',
        'age_range',
        'base_price',
        'bestseller_flag',
        'free_cancellation_flag',
        'booked_count',
        'map_frame',
        'category_id',
        'location_id',
        'included',
        'excluded',
        'itinerary',
        'languages',
    ];

    protected $casts = [
        'included' => 'array',
        'excluded' => 'array',
        'itinerary' => 'array',
        'languages' => 'array',
        'bestseller_flag' => 'boolean',
        'free_cancellation_flag' => 'boolean',
    ];

    /**
     * Ensure highlights always returns an array
     */
    public function getHighlightsArrayAttribute()
    {
        $val = $this->highlights;

        if (is_null($val)) return [];

        if (is_array($val)) return $val;

        $decoded = json_decode($val, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return array_filter(
            preg_split('/\r\n|\r|\n/', $val),
            fn ($line) => trim($line) !== ''
        );
    }

    /**
     * Return languages as a formatted string
     */
    public function getLanguagesFormattedAttribute()
    {
        $lang = $this->languages;
        if (is_array($lang) && count($lang) > 0) {
            return implode(', ', $lang);
        }
        return 'English';
    }

    public function category()
    {
        return $this->belongsTo(TourCategory::class, 'category_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        // Cover image collection (single file)
        $this->addMediaCollection('cover')
            ->singleFile()
            ->useDisk('public');

        // Gallery collection (multiple images)
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
     * Register media conversions
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
