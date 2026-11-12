<?php

/*
|--------------------------------------------------------------------------
| 301 redirects for renamed tour slugs
|--------------------------------------------------------------------------
| old slug => current slug. Applied in FrontTourController@show only when the
| old slug no longer exists in the database. Keep targets out of the keys
| (no chains); tests/Feature/TourSlugRedirectTest.php enforces this.
*/

return [
    'merzouga-desert-tour-from-marrakech-2-days'          => '2-day-desert-tour-from-marrakech-to-merzouga',
    'sahara-desert-tour-from-marrakech-3-days-to-chigaga' => '3-day-sahara-desert-tour-from-marrakech-to-chigaga',
    'marrakech-desert-tour-3-days-to-merzouga-dunes'      => '3-day-desert-tour-from-marrakech-to-merzouga-dunes',
    'marrakech-to-fes-desert-tour-3-days-via-sahara'      => '3-day-desert-tour-from-marrakech-to-fes-via-sahara',
    'luxury-sahara-desert-tour-from-marrakech-5-days'     => '5-day-luxury-sahara-desert-tour-from-marrakech',
];
