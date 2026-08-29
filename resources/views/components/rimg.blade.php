{{--
    Responsive <img>. Emits a srcset of the pre-generated "-<w>w.avif/webp"
    variants produced by tools/gen-responsive.cjs.

    Usage:
        <x-rimg src="assets/images/hero/foo.webp" alt="…" :w="960" :h="640"
                sizes="(max-width: 991px) 100vw, 50vw" loading="lazy" />

    Falls back to a plain <img> with the original src when no variant files are
    present on disk, so a missing/partial generation run can never break a page.

    Params:
      src      asset-relative path (no leading slash)
      alt      required; pass "" for decorative images
      w, h     intrinsic size, for aspect-ratio reservation (prevents CLS)
      sizes    CSS sizes attribute; defaults to 100vw
      widths   which variant widths to reference (default 400,800,1200)
      priority true = eager + fetchpriority=high (use for the LCP image only)
--}}
@props([
    'src',
    'alt' => '',
    'w' => null,
    'h' => null,
    'sizes' => '100vw',
    'widths' => [400, 800, 1200],
    'priority' => false,
    'class' => null,
])

@php
    $rel      = ltrim($src, '/');
    $dir      = trim(dirname($rel), './');
    $base     = pathinfo($rel, PATHINFO_FILENAME);
    $variants = ['avif' => [], 'webp' => []];

    foreach ((array) $widths as $vw) {
        foreach (['avif', 'webp'] as $fmt) {
            $candidate = ($dir ? $dir . '/' : '') . $base . '-' . $vw . 'w.' . $fmt;
            if (is_file(public_path($candidate))) {
                $variants[$fmt][] = asset($candidate) . ' ' . $vw . 'w';
            }
        }
    }

    $imgAttrs = $attributes
        ->merge(['class' => $class])
        ->merge($priority
            ? ['fetchpriority' => 'high', 'decoding' => 'async']
            : ['loading' => 'lazy', 'decoding' => 'async']);
@endphp

@if ($variants['avif'] || $variants['webp'])
    <picture>
        @if ($variants['avif'])
            <source type="image/avif" sizes="{{ $sizes }}" srcset="{{ implode(', ', $variants['avif']) }}">
        @endif
        @if ($variants['webp'])
            <source type="image/webp" sizes="{{ $sizes }}" srcset="{{ implode(', ', $variants['webp']) }}">
        @endif
        <img src="{{ asset($rel) }}" alt="{{ $alt }}"
             @if ($w) width="{{ $w }}" @endif
             @if ($h) height="{{ $h }}" @endif
             {{ $imgAttrs }}>
    </picture>
@else
    <img src="{{ asset($rel) }}" alt="{{ $alt }}"
         @if ($w) width="{{ $w }}" @endif
         @if ($h) height="{{ $h }}" @endif
         {{ $imgAttrs }}>
@endif
