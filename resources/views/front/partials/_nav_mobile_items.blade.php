{{-- Shared mobile menu items (same depth as desktop). Used by _header
     and _header2. Each city expands to its full tour list. --}}

{{-- Tours -> city -> tours --}}
<li class="menuNav__item -has-submenu js-has-submenu">
    <a role="button" tabindex="0" aria-haspopup="true" aria-expanded="false">
        Tours
        <i class="icon-chevron-right"></i>
    </a>
    <ul class="submenu">
        <li class="submenu__item js-nav-list-back"><a role="button" tabindex="0" aria-label="Back to the previous menu">Back</a></li>
        <li class="submenu__item">
            <a href="{{ route('front.tours.multiDay') }}">All Tours</a>
        </li>
        @foreach ($navMultiDayByCity as $city => $tours)
            <li class="submenu__item -has-submenu js-has-submenu">
                <a role="button" tabindex="0" aria-haspopup="true" aria-expanded="false">
                    From {{ \Illuminate\Support\Str::before($city, ',') }}
                    <i class="icon-chevron-right"></i>
                </a>
                <ul class="submenu">
                    <li class="submenu__item js-nav-list-back"><a role="button" tabindex="0" aria-label="Back to the previous menu">Back</a></li>
                    <li class="submenu__item">
                        <a href="{{ route('front.tours.multiDayFrom', \Illuminate\Support\Str::slug($city)) }}">
                            All From {{ \Illuminate\Support\Str::before($city, ',') }}
                        </a>
                    </li>
                    @foreach ($tours as $tour)
                        <li class="submenu__item">
                            <a href="{{ route('front.tours.show', $tour->slug) }}">{{ $tour->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</li>

{{-- Day Trips -> city -> tours --}}
<li class="menuNav__item -has-submenu js-has-submenu">
    <a role="button" tabindex="0" aria-haspopup="true" aria-expanded="false">
        Day Trips
        <i class="icon-chevron-right"></i>
    </a>
    <ul class="submenu">
        <li class="submenu__item js-nav-list-back"><a role="button" tabindex="0" aria-label="Back to the previous menu">Back</a></li>
        <li class="submenu__item">
            <a href="{{ route('front.tours.dayTrips') }}">All Day Trips</a>
        </li>
        @foreach ($navDayTripsByCity as $city => $tours)
            <li class="submenu__item -has-submenu js-has-submenu">
                <a role="button" tabindex="0" aria-haspopup="true" aria-expanded="false">
                    {{ \Illuminate\Support\Str::before($city, ',') }} excursions
                    <i class="icon-chevron-right"></i>
                </a>
                <ul class="submenu">
                    <li class="submenu__item js-nav-list-back"><a role="button" tabindex="0" aria-label="Back to the previous menu">Back</a></li>
                    <li class="submenu__item">
                        <a href="{{ route('front.tours.dayTripsFrom', \Illuminate\Support\Str::slug($city)) }}">
                            All {{ \Illuminate\Support\Str::before($city, ',') }} excursions
                        </a>
                    </li>
                    @foreach ($tours as $tour)
                        <li class="submenu__item">
                            <a href="{{ route('front.tours.show', $tour->slug) }}">{{ $tour->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</li>

{{-- Activities -> full list --}}
<li class="menuNav__item -has-submenu js-has-submenu">
    <a role="button" tabindex="0" aria-haspopup="true" aria-expanded="false">
        Activities
        <i class="icon-chevron-right"></i>
    </a>
    <ul class="submenu">
        <li class="submenu__item js-nav-list-back"><a role="button" tabindex="0" aria-label="Back to the previous menu">Back</a></li>
        <li class="submenu__item">
            <a href="{{ route('front.activities.index') }}">All Activities</a>
        </li>
        @foreach ($navActivities as $activity)
            <li class="submenu__item">
                <a href="{{ route('front.activities.show', $activity->slug) }}">{{ $activity->title }}</a>
            </li>
        @endforeach
    </ul>
</li>

{{-- Trekking -> treks --}}
<li class="menuNav__item -has-submenu js-has-submenu">
    <a role="button" tabindex="0" aria-haspopup="true" aria-expanded="false">
        Trekking
        <i class="icon-chevron-right"></i>
    </a>
    <ul class="submenu">
        <li class="submenu__item js-nav-list-back"><a role="button" tabindex="0" aria-label="Back to the previous menu">Back</a></li>
        <li class="submenu__item">
            <a href="{{ route('front.trekking.index') }}">All Treks</a>
        </li>
        @foreach ($navTrekkings as $trek)
            <li class="submenu__item">
                <a href="{{ route('front.trekking.show', $trek->slug) }}">{{ $trek->title }}</a>
            </li>
        @endforeach
    </ul>
</li>

{{-- About (dropdown) --}}
<li class="menuNav__item -has-submenu js-has-submenu">
    <a role="button" tabindex="0" aria-haspopup="true" aria-expanded="false">
        About
        <i class="icon-chevron-right"></i>
    </a>
    <ul class="submenu">
        <li class="submenu__item js-nav-list-back"><a role="button" tabindex="0" aria-label="Back to the previous menu">Back</a></li>
        <li class="submenu__item">
            <a href="{{ route('front.about') }}">About</a>
        </li>
        <li class="submenu__item">
            <a href="{{ route('front.help-center') }}">Help Center</a>
        </li>
        <li class="submenu__item">
            <a href="{{ route('front.terms') }}">Terms & Conditions</a>
        </li>
    </ul>
</li>

<li class="menuNav__item">
    <a href="{{ route('blog.index') }}">Blog</a>
</li>
<li class="menuNav__item">
    <a href="{{ route('front.contact') }}">Contact</a>
</li>
<li class="menuNav__item">
    <a href="{{ route('front.locations.index') }}">Destinations</a>
</li>
