<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;

class Activity extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
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
        'languages',
        'included',
        'excluded',
        'itinerary',
    ];

    protected $casts = [
        'included'    => 'array',
        'excluded'    => 'array',
        'itinerary'   => 'array',
        'highlights'  => 'array',
        'languages'   => 'array',
        'bestseller_flag' => 'boolean',
        'free_cancellation_flag' => 'boolean',
    ];

    protected $attributes = [
        'included'   => '[]',
        'excluded'   => '[]',
        'itinerary'  => '[]',
        'highlights' => '[]',
        'languages'  => '[]',
    ];

    /**
     * Relationships
     */
    public function category()
    {
        return $this->belongsTo(ActivityCategory::class, 'category_id');
    }

    public function location()
    {
        return $this->belongsTo(\App\Models\Location::class, 'location_id');
    }

    public function reviews()
    {
        return $this->morphMany(\App\Models\Review::class, 'reviewable');
    }

    /**
     * Media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile()
            ->useDisk('public');

        $this->addMediaCollection('gallery')
            ->useDisk('public');
    }

    /**
     * Media conversions
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

    /**
     * Accessors
     */

    public function getLanguagesFormattedAttribute(): string
    {
        if (is_array($this->languages) && count($this->languages) > 0) {
            return implode(', ', $this->languages);
        }
        return 'English';
    }

    public function getHighlightsArrayAttribute(): array
    {
        if (empty($this->highlights)) {
            return [];
        }

        if (is_array($this->highlights)) {
            return $this->highlights;
        }

        return array_filter(
            preg_split('/\r\n|\r|\n/', $this->highlights),
            fn($line) => trim($line) !== ''
        );
    }
}
