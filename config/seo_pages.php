<?php

/*
|--------------------------------------------------------------------------
| Per-page SEO (title, meta description, optional H1)
|--------------------------------------------------------------------------
| Source of every keyword: docs/seo/keyword-research/04-keyword-mapping.csv
| (Authentic Morocco Adventures only). Titles stay <= 60 characters.
|
| Lookup is by the PRODUCTION slug. When an entry is missing, the views fall
| back to the database title/overview exactly as before, so a new tour added
| in the admin panel still renders correctly without touching this file.
|
| 'h1' is optional: only set where the database title does not carry the
| page's researched keyword. Card titles on listing pages are unchanged.
*/

return [

    // Old (pre-rename) slugs that still exist in some databases -> production slug,
    // so the SEO entries below also apply where the old slug is still in use.
    'aliases' => [
        'tours' => [
            'merzouga-desert-tour-from-marrakech-2-days'          => '2-day-desert-tour-from-marrakech-to-merzouga',
            'sahara-desert-tour-from-marrakech-3-days-to-chigaga' => '3-day-sahara-desert-tour-from-marrakech-to-chigaga',
            'marrakech-desert-tour-3-days-to-merzouga-dunes'      => '3-day-desert-tour-from-marrakech-to-merzouga-dunes',
            'marrakech-to-fes-desert-tour-3-days-via-sahara'      => '3-day-desert-tour-from-marrakech-to-fes-via-sahara',
            'luxury-sahara-desert-tour-from-marrakech-5-days'     => '5-day-luxury-sahara-desert-tour-from-marrakech',
        ],
    ],

    /* ------------------------------------------------------------------ TOURS */
    'tours' => [
        // --- Multi-day from Marrakech
        '11-day-morocco-desert-tour-from-marrakech' => [
            'title'       => '11 Day Morocco Tour from Marrakech | Private Desert Trip',
            'description' => 'Private 11-day Morocco tour from Marrakech: the medina, the High Atlas, Aït Benhaddou, Ouarzazate, the Dades and Todra gorges and the Sahara desert.',
        ],
        '2-day-desert-tour-from-marrakech-to-agadir' => [
            'title'       => 'Marrakech to Agadir Tour | 2-Day Private Trip',
            'description' => 'Private 2-day Marrakech to Agadir tour: Kasbah Agadir Oufella, the Atlantic fishing port, Souk El Had and the medina artisans, with a driver-guide.',
        ],
        '2-day-desert-tour-from-marrakech-to-agadir-essaouira' => [
            'title'       => '2 Day Tour to Agadir & Essaouira from Marrakech',
            'description' => 'Private 2-day tour from Marrakech to Agadir and Essaouira: the Agadir Kasbah panorama, an argan oil women\'s cooperative and the UNESCO medina of Essaouira.',
        ],
        '2-day-desert-tour-from-marrakech-to-ouarzazate-ait-benhaddou' => [
            'title'       => 'Marrakech to Ouarzazate Tour | 2 Days via Aït Benhaddou',
            'description' => 'Private 2-day Marrakech to Ouarzazate tour over the Tizi n\'Tichka pass: the UNESCO ksar of Aït Benhaddou, Taourirt Kasbah and the Atlas film studios.',
        ],
        '2-day-marrakech-desert-tour-to-casablanca-rabat' => [
            'title'       => 'Marrakech to Casablanca Tour | 2 Days with Rabat',
            'description' => 'Private 2-day Marrakech to Casablanca and Rabat tour: the Hassan II Mosque, the Corniche, the Kasbah of the Udayas and the Hassan Tower, with a driver-guide.',
        ],
        '2-day-marrakech-desert-tour-to-essaouira' => [
            'title'       => '2 Day Trip from Marrakech to Essaouira | Private Tour',
            'description' => 'Private 2-day trip from Marrakech to Essaouira: the old Mogador fort, an argan oil cooperative, the UNESCO medina and Moulay El-Hassan Square.',
        ],
        '2-day-desert-tour-from-marrakech-to-merzouga' => [
            'title'       => '2 Day Desert Tour from Marrakech to Merzouga',
            'description' => 'Private 2-day desert tour from Marrakech to Merzouga: across the High Atlas and the Dades Valley to the Sahara dunes, returning via Agdz with a driver-guide.',
        ],
        '2-day-sahara-desert-tour-from-marrakech-to-zagora' => [
            'title'       => 'Zagora Desert Tour from Marrakech | 2 Days',
            'description' => 'Private 2-day Zagora desert tour from Marrakech via Ouarzazate: the Draa Valley and a camel ride in Zagora, with hotel pick-up and a driver-guide.',
        ],
        '3-day-sahara-desert-tour-from-marrakech-to-chigaga' => [
            'title'       => 'Erg Chigaga Desert Tour from Marrakech | 3 Days',
            'h1'          => '3-Day Erg Chigaga Desert Tour from Marrakech',
            'description' => 'Private 3-day Erg Chigaga tour from Marrakech: Aït Benhaddou, Ouarzazate, Zagora, Tamegroute and M\'hamid to the wild Chigaga dunes, back via Foum Zguid.',
        ],
        '3-day-desert-tour-from-marrakech-to-merzouga-dunes' => [
            'title'       => '3 Day Desert Tour from Marrakech to Merzouga',
            'description' => 'Private 3-day desert tour from Marrakech to the Erg Chebbi dunes of Merzouga: Aït Benhaddou, the Todra Gorge and a camel trek at sunset with a local guide.',
        ],
        '3-day-desert-tour-from-marrakech-to-fes-via-sahara' => [
            'title'       => 'Marrakech to Fes Desert Tour | 3 Days via Sahara',
            'description' => 'Private 3-day Marrakech to Fes desert tour: the High Atlas, Ouarzazate, the Dades and Todra gorges, a Merzouga night in the Sahara, then Midelt to Fes.',
        ],
        '4-day-desert-tour-from-marrakech-to-merzouga' => [
            'title'       => '4 Day Desert Tour from Marrakech to Merzouga',
            'description' => 'Private 4-day desert tour from Marrakech: the Dades and Todra gorges, Merzouga and Rissani, then N\'kob, Zagora, Ouarzazate and Aït Benhaddou on the way back.',
        ],
        '4-day-desert-tour-from-marrakech-via-sahara-to-chefchaouen' => [
            'title'       => 'Marrakech to Chefchaouen Tour | 4 Days via Sahara',
            'description' => 'Private 4-day Marrakech to Chefchaouen tour via the Sahara: Aït Benhaddou, the Dades and Todra gorges, Merzouga, Ifrane and Fes on the way to the Blue City.',
        ],
        '5-day-luxury-sahara-desert-tour-from-marrakech' => [
            'title'       => 'Luxury Sahara Desert Tour from Marrakech | 5 Days',
            'description' => 'Private 5-day luxury Sahara desert tour from Marrakech: Tizi n\'Tichka, Aït Benhaddou, the Dades Valley, the Todra Gorge and a Merzouga camel ride.',
        ],
        '8-days-marrakech-zagora-merzouga' => [
            'title'       => '8-Day Sahara Desert Circuit from Marrakech',
            'description' => 'Private 8-day Sahara desert circuit from Marrakech: the Atlas, Aït Benhaddou, Ouarzazate, Agdz and Zagora, then the Erg Chebbi dunes, with a driver-guide.',
        ],
        '8-day-marrakech-sahara-desert-tour-to-fes-chefchaouen' => [
            'title'       => '8 Day Morocco Itinerary: Marrakech, Sahara & Fes',
            'description' => '8-day Morocco itinerary on a private tour: Marrakech, the Dades and Todra gorges, Merzouga, the Ziz Valley, Midelt, a guided Fes medina tour and Chefchaouen.',
        ],
        '4-days-atlas-zagora-desert-tour' => [
            'title'       => 'Atlas Mountains & Sahara Desert Tour | 4 Days',
            'description' => 'Private 4-day Atlas Mountains and Sahara desert tour: a trek from Imlil to the Toubkal refuge, then Aït Benhaddou, Ouarzazate and a Zagora camel trek.',
        ],
        '5-days-toubkal-merzouga-tour' => [
            'title'       => '5 Day Toubkal Trek & Sahara Desert Tour',
            'description' => 'Private 5-day Toubkal trek and Sahara desert tour: Imlil to the Toubkal refuge, then Aït Benhaddou, the Dades Gorges, Erfoud and a camel trek in Merzouga.',
        ],
        '15-day-spain-morocco-private-tour' => [
            'title'       => 'Spain and Morocco Tour | 15-Day Private Itinerary',
            'description' => 'Private 15-day Spain and Morocco tour: Madrid and Andalusia, the Gibraltar crossing, Chefchaouen, Fes, the Merzouga Sahara, Aït Benhaddou and Marrakech.',
        ],

        // --- From Fes
        '2-days-fes-to-chefchaouen' => [
            'title'       => 'Fes to Chefchaouen Tour | 2 Days in the Blue City',
            'description' => 'Private 2-day Fes to Chefchaouen tour: the Rif Mountains, Ouazzane and a night in the Blue City, with time for the medina, the local market and photo spots.',
        ],
        '2-days-fes-to-sahara-desert-back' => [
            'title'       => 'Desert Trip from Fes | 2 Days to Merzouga & Back',
            'description' => 'Private 2-day desert trip from Fes: Ifrane and the Azrou cedar forest, a camel trek into the Erg Chebbi dunes and a desert camp night with Berber music.',
        ],
        '3-days-fes-to-merzouga-desert' => [
            'title'       => 'Fes to Merzouga Desert Tour | 3 Days',
            'description' => 'Private 3-day Fes to Merzouga tour: the Middle Atlas, Ifrane and the Azrou cedar forest, a camel trek, Gnaoua music in Khamlia and a night under the stars.',
        ],
        '3-day-fes-to-marrakech-desert-tour-via-sahara' => [
            'title'       => 'Fes to Marrakech Desert Tour | 3 Days via Sahara',
            'description' => 'Private 3-day Fes to Marrakech desert tour: the Merzouga dunes and a camel trek, a desert camp, the Todra Gorge, Aït Benhaddou and the Tizi n\'Tichka pass.',
        ],
        '4-day-fes-to-marrakech-desert-tour-via-merzouga' => [
            'title'       => 'Fes to Marrakech Desert Tour | 4 Days via Merzouga',
            'description' => 'Private 4-day Fes to Marrakech desert tour: the Erg Chebbi dunes, a camel trek, Gnaoua music in Khamlia, the Todra Gorge, Aït Benhaddou and the High Atlas.',
        ],
        '5-day-fes-to-marrakech-desert-tour-via-sahara' => [
            'title'       => '5 Day Morocco Tour: Fes to Marrakech via Sahara',
            'description' => 'Private 5-day Morocco tour from Fes to Marrakech: Ifrane and Midelt, the Merzouga desert, the Todra and Dades valleys, Skoura and Ouarzazate.',
        ],
        'fes-to-atlas-mountains-day-trip' => [
            'title'       => 'Ifrane & Azrou Day Trip from Fes | Middle Atlas',
            'h1'          => 'Fes to Ifrane & Azrou Day Trip (Middle Atlas)',
            'description' => 'Private day trip from Fes to the Middle Atlas: Ifrane, the "Switzerland of Morocco", and the Azrou cedar forest, with transfers and a local guide.',
        ],
        'fes-to-chefchaouen-day-trip' => [
            'title'       => 'Fes to Chefchaouen Day Trip | Private Tour',
            'description' => 'Private Fes to Chefchaouen day trip: a long day to the Blue City in the Rif Mountains, with air-conditioned transfers and a local guide, back in Fes by night.',
        ],
        'meknes-volubilis-day-trip-from-fes' => [
            'title'       => 'Volubilis Tour from Fes | Meknes & Roman Ruins',
            'description' => 'Private Volubilis tour from Fes: the Roman ruins of Volubilis and the imperial city of Meknes in one day, with air-conditioned transfers and a local guide.',
        ],
        'rabat-day-trip-from-fes' => [
            'title'       => 'Fes to Rabat Day Trip | Private Tour of the Capital',
            'description' => 'Private Fes to Rabat day trip: a long day in Morocco\'s capital on the Atlantic coast, with air-conditioned transfers and a local guide, back to Fes by night.',
        ],
        'guided-medina-tour-in-fes' => [
            'title'       => 'Fes Medina Tour | Guided Half-Day Walking Tour',
            'description' => 'Guided Fes medina tour: a half-day walk through the souks and lanes of Fes el-Bali with a local guide, with transfers in an air-conditioned vehicle.',
        ],

        // --- From Tangier
        '12-day-morocco-desert-tours-from-tangier' => [
            'title'       => '12 Day Morocco Itinerary from Tangier | Private Tour',
            'description' => '12-day Morocco itinerary on a private tour from Tangier: Chefchaouen, a guided day in Fes, the Sahara and the south, with hotel transfers and a driver-guide.',
        ],
        '2-day-morocco-desert-tour-from-tangier-to-chefchaouen' => [
            'title'       => 'Tangier to Chefchaouen | 2-Day Private Tour',
            'description' => 'Private 2-day trip from Tangier to Chefchaouen: the Rif Mountains, the Blue City medina and Kasbah museum, and a night in a riad.',
        ],
        '3-day-morocco-desert-tour-from-tangier-to-chefchaouen-fes' => [
            'title'       => 'Tangier to Fes via Chefchaouen | 3-Day Private Tour',
            'description' => 'Private 3-day tour from Tangier to Fes: Chefchaouen, the Fes UNESCO medina, the Roman ruins of Volubilis, imperial Meknes, Rabat and the Asilah coast.',
        ],
        '4-day-morocco-desert-tour-from-tangier-to-fes-via-atlantic-coast' => [
            'title'       => 'Tangier to Fes & Rabat Tour | 4 Days',
            'description' => 'Private 4-day tour from Tangier: Chefchaouen and Fes, a guided day in Fes, then Meknes, Rabat and Asilah on the Atlantic coast back to Tangier.',
        ],
        '6-day-morocco-desert-tour-from-tangier-to-sahara-marrakech' => [
            'title'       => 'Tangier to Marrakech via the Sahara | 6-Day Tour',
            'description' => 'Private 6-day tour from Tangier to Marrakech: Chefchaouen, a guided day in Fes, Ifrane and Midelt to the Merzouga desert, then south to Marrakech.',
        ],
        '9-day-morocco-desert-tour-from-tangier-to-merzouga' => [
            'title'       => 'Tangier to Merzouga Desert Tour | 9 Days',
            'description' => 'Private 9-day tour from Tangier to the Merzouga desert: Chefchaouen, a guided day in Fes, the Sahara dunes and the south, with a driver-guide throughout.',
        ],

        // --- From Agadir
        '2-days-agadir-to-zagora-desert' => [
            'title'       => 'Agadir to Zagora Desert Tour | 2 Days',
            'description' => 'Private 2-day Agadir to Zagora tour: Anti-Atlas villages, the Draa Valley palm grove, Tamegroute pottery and library, a camel ride and a desert camp.',
        ],
        '3-days-agadir-to-chigaga-desert' => [
            'title'       => 'Agadir to Erg Chigaga Desert Tour | 3 Days',
            'description' => 'Private 3-day Agadir desert tour to Erg Chigaga: Taroudant, Taliouine saffron country, Aït Benhaddou, a camel ride and a Berber bivouac in the dunes.',
        ],
        '4-days-agadir-to-merzouga-desert' => [
            'title'       => 'Agadir to Merzouga Desert Tour | 4 Days',
            'description' => 'Private 4-day Agadir to Merzouga tour: Taroudant, the Draa Valley oases, Aït Benhaddou, the Erg Chegaga and Merzouga dunes, ending in Marrakech.',
        ],

        // --- From Chefchaouen
        '5-day-morocco-desert-tour-from-chefchaouen-to-sahara' => [
            'title'       => 'Desert Tour from Chefchaouen to Merzouga | 5 Days',
            'description' => 'Private 5-day desert tour from Chefchaouen to Merzouga: Ouazzane and Fes, Ifrane and Midelt to the Sahara, then the Todra and Dades gorges.',
        ],
        '6-day-morocco-desert-tour-from-chefchaouen-to-marrakech' => [
            'title'       => 'Chefchaouen to Marrakech via Sahara | 6-Day Tour',
            'description' => 'Private 6-day tour from Chefchaouen to Marrakech: a guided day in the Fes medina, Ifrane and Midelt, a Merzouga camel trek and the road south.',
        ],
        '7-day-morocco-desert-tour-from-chefchaouen-around-morocco' => [
            'title'       => '7 Day Morocco Tour from Chefchaouen to Marrakech',
            'description' => 'Private 7-day tour around Morocco from Chefchaouen: Fes, Ifrane and Midelt, the Merzouga desert, the Todra and Dades gorges and on to Marrakech.',
        ],

        // --- From Ouarzazate
        '2-days-ouarzazate-to-merzouga' => [
            'title'       => 'Ouarzazate to Merzouga Desert Tour | 2 Days',
            'description' => 'Private 2-day Ouarzazate to Merzouga tour: the Dades Valley to the Sahara dunes, then Rissani, Agdz and the Draa Valley back to Ouarzazate.',
        ],
        '3-days-ouarzazate-merzouga-fes' => [
            'title'       => 'Ouarzazate to Fes via Merzouga | 3-Day Tour',
            'description' => 'Private 3-day Ouarzazate to Fes tour: Skoura, the Dades and Todra gorges, Erfoud, a night in the Merzouga desert and Midelt on the way to Fes.',
        ],
        '3-days-ouarzazate-to-merzouga-desert' => [
            'title'       => 'Ouarzazate to Merzouga Tour | 3 Days in the Sahara',
            'description' => 'Private 3-day Ouarzazate to Merzouga tour: Skoura, the Dades Gorges, Todra and Erfoud to the dunes, back through Agdz and the Draa Valley.',
        ],

        // --- From Casablanca
        '11-days-grand-morocco-tour' => [
            'title'       => 'Morocco Grand Tour | 11 Days from Casablanca',
            'h1'          => '11-Day Grand Morocco Tour from Casablanca',
            'description' => 'Private 11-day Morocco grand tour from Casablanca: the imperial cities, Chefchaouen, the Merzouga dunes, a camel trek and Aït Benhaddou.',
        ],
        '14-days-grand-morocco-tour' => [
            'title'       => '14 Day Morocco Itinerary | Grand Tour from Casablanca',
            'description' => '14-day Morocco itinerary on a private grand tour from Casablanca: the imperial cities, Chefchaouen, the Erg Chebbi dunes, a camel trek and Essaouira.',
        ],
        '2-days-casablanca-to-marrakech' => [
            'title'       => 'Casablanca to Marrakech Tour | 2 Days',
            'description' => 'Private 2-day Casablanca to Marrakech tour: the Bahia Palace, Menara Garden, Saadian Tombs, Jemaa el-Fna and the souks, with an optional Agafay desert visit.',
        ],
        '2-days-casablanca-to-chefchaouen' => [
            'title'       => 'Casablanca to Chefchaouen | 2-Day Private Trip',
            'description' => 'Private 2-day trip from Casablanca to Chefchaouen and back: a night in the Blue City, with hotel pick-up, an A/C vehicle and an English-speaking guide.',
        ],
        '3-days-casablanca-chefchaouen-fes' => [
            'title'       => 'Casablanca to Fes Tour via Chefchaouen | 3 Days',
            'description' => 'Private 3-day Casablanca to Fes tour: Rabat and Chefchaouen, then Ouazzane to Fes, with an optional guided Fes medina excursion.',
        ],
        '3-days-casablanca-marrakech-essaouira' => [
            'title'       => 'Casablanca to Essaouira via Marrakech | 3 Days',
            'description' => 'Private 3-day tour from Casablanca: Marrakech, then the Atlantic town of Essaouira, and back to Casablanca, with hotel pick-up and a driver-guide.',
        ],
        '5-days-casablanca-sahara-desert' => [
            'title'       => 'Sahara Desert Tour from Casablanca | 5 Days',
            'description' => 'Private 5-day Sahara desert tour from Casablanca: Rabat and Fes, a guided day in Fes, Midelt to the Merzouga dunes, then the Todra and Dades gorges.',
        ],
        '7-days-sahara-imperial-cities' => [
            'title'       => 'Morocco Imperial Cities Tour | 7 Days with Sahara',
            'description' => 'Private 7-day Morocco imperial cities tour from Casablanca: Rabat, Chefchaouen, Meknes and Fes, then Midelt and Erfoud to the Merzouga desert.',
        ],
        '9-days-private-tour-from-casablanca' => [
            'title'       => '9 Day Morocco Tour from Casablanca | Private',
            'description' => 'Private 9-day Morocco tour from Casablanca: Rabat, Chefchaouen, a guided day in Fes, Merzouga, Rissani and the Todra Gorge, with a driver-guide.',
        ],
        'casablanca-to-fes-day-trip' => [
            'title'       => 'Casablanca to Fes Day Trip | Private Tour',
            'description' => 'Private Casablanca to Fes day trip: a long day in the imperial city and its medina, with hotel pick-up, an air-conditioned vehicle and a local driver.',
        ],
        'casablanca-to-marrakech-day-trip' => [
            'title'       => 'Casablanca to Marrakech Day Trip | Private Tour',
            'description' => 'Private Casablanca to Marrakech day trip: a long day in the Red City, with hotel pick-up, an air-conditioned vehicle and a local driver, back by night.',
        ],
        'casablanca-to-rabat-day-trip' => [
            'title'       => 'Casablanca to Rabat Day Trip | Private Tour',
            'description' => 'Private Casablanca to Rabat day trip to Morocco\'s capital, with hotel pick-up, an air-conditioned vehicle and a local driver, back the same day.',
        ],

        // --- Day trips from Marrakech
        'marrakech-to-essaouira-day-trip' => [
            'title'       => 'Day Trip to Essaouira from Marrakech | Private',
            'description' => 'Private day trip to Essaouira from Marrakech: the Atlantic coast, the Portuguese fort, the fishing harbour, the UNESCO medina and a camel ride on the beach.',
        ],
        'agafay-desert-tour-from-marrakech' => [
            'title'       => 'Agafay Desert Tour from Marrakech | Private Day Trip',
            'h1'          => 'Agafay Desert Tour from Marrakech (Day Trip)',
            'description' => 'Private Agafay desert day trip from Marrakech: Lalla Takerkoust lake, the Kik plateau, Moulay Brahim village and a Berber lunch, back the same day.',
        ],
        'marrakech-to-ait-benhaddou-day-trip' => [
            'title'       => 'Ait Ben Haddou Tour | Day Trip from Marrakech',
            'h1'          => 'Marrakech to Ait Ben Haddou Day Trip',
            'description' => 'Private Ait Ben Haddou day trip from Marrakech: the Tizi n\'Tichka pass, the Kasbah of Telouet, the UNESCO ksar and the Ouarzazate film studios.',
        ],
        'marrakech-to-ourika-valley-day-trip' => [
            'title'       => 'Ourika Valley Day Trip from Marrakech | Atlas',
            'description' => 'Private Ourika Valley day trip from Marrakech into the Atlas Mountains, with hotel pick-up, an air-conditioned vehicle and a local driver-guide.',
        ],
        'marrakech-to-ouzoud-waterfalls-day-trip' => [
            'title'       => 'Ouzoud Waterfalls Day Trip from Marrakech',
            'description' => 'Private Ouzoud waterfalls day trip from Marrakech to the falls in the Middle Atlas, with hotel pick-up, an air-conditioned vehicle and a driver-guide.',
        ],
    ],

    /* ------------------------------------------------------------- ACTIVITIES */
    'activities' => [
        'marrakech-horse-riding-tour' => [
            'title'       => 'Horse Riding Marrakech | 2-Hour Palmeraie Ride',
            'description' => 'Horse riding in Marrakech: a 2-hour ride on gentle, well-trained horses through the Palmeraie, with Atlas views and an experienced guide. All levels.',
        ],
        'agafay-desert-tour-from-marrakech-luxury-camp' => [
            'title'       => 'Agafay Luxury Camp: Overnight Stay from Marrakech',
            'h1'          => 'Agafay Luxury Camp: Overnight Desert Stay from Marrakech',
            'description' => 'Spend a night at an Agafay luxury camp near Marrakech: private A/C transfer, dinner and breakfast, and a sunset over the stone desert and the Atlas.',
        ],
        'marrakech-camel-ride-palmeraie' => [
            'title'       => 'Marrakech Camel Ride in the Palmeraie | 2 Hours',
            'description' => 'Marrakech camel ride through the Palmeraie palm groves with a local guide, followed by traditional Moroccan mint tea. A 2-hour experience.',
        ],
        'chez-ali-fantasia-show' => [
            'title'       => 'Chez Ali Marrakech | Fantasia Dinner Show',
            'description' => 'Chez Ali Marrakech fantasia show with dinner: hotel transfer, a reserved table, a Moroccan feast and the horse-riding fantasia spectacle.',
        ],
        'marrakech-hot-air-balloon' => [
            'title'       => 'Marrakech Hot Air Balloon Ride at Sunrise',
            'description' => 'Marrakech hot air balloon ride at sunrise, with hotel pick-up in a private air-conditioned vehicle and a local guide. About 3 hours in total.',
        ],
        'marrakech-cooking-class' => [
            'title'       => 'Marrakech Cooking Class | Moroccan Cuisine',
            'description' => 'Marrakech cooking class: learn to cook classic Moroccan dishes, with hotel pick-up and stories about the city and its food. A 4-hour experience.',
        ],
        'marrakech-food-tour-medina-street-food' => [
            'title'       => 'Marrakech Food Tour | Medina Street Food',
            'description' => 'Marrakech food tour through the medina\'s street food stalls with a local guide, with hotel pick-up and stories about the city and its food. About 4 hours.',
        ],
        'marrakech-hammam-moroccan-massage' => [
            'title'       => 'Hammam Marrakech | Traditional Scrub & Massage',
            'description' => 'Traditional hammam in Marrakech: a black soap scrub and a soothing massage tailored to you, in a relaxing 2-hour session.',
        ],
        'marrakech-jewish-heritage-tour-full-day' => [
            'title'       => 'Marrakech Jewish Tour | Full-Day Heritage Tour',
            'description' => 'Marrakech Jewish heritage tour: a full day in the Mellah and the city\'s Jewish history with a local guide, with hotel pick-up in a private A/C vehicle.',
        ],
        'marrakech-medina-tour-half-day' => [
            'title'       => 'Marrakech Medina Tour | Half-Day Walking Tour',
            'description' => 'Marrakech medina tour: a half-day walking tour of the souks, palaces and squares of the old city with a knowledgeable local guide. Hotel pick-up available.',
        ],
        'marrakech-quad-biking-buggy-ride' => [
            'title'       => 'Quad Biking Marrakech | Quad & Buggy Ride',
            'description' => 'Quad biking in Marrakech: a 2-hour quad or buggy ride with an experienced guide, a safety briefing and all equipment included.',
        ],
        'marrakech-gardens-tour-majorelle-menara' => [
            'title'       => 'Marrakech Gardens Tour | Majorelle & Menara',
            'description' => 'Marrakech gardens tour: a half day at the Majorelle and Menara gardens with a knowledgeable guide, entry fees included.',
        ],
    ],

    /* ---------------------------------------------------------------- TREKKING */
    'trekking' => [
        '2-days-toubkal-trekking' => [
            'title'       => '2 Day Toubkal Trek from Marrakech | Summit 4,167 m',
            'description' => '2-day Toubkal trek from Imlil (1,740 m) to the summit of Mount Toubkal (4,167 m), with a night at the refuge, a mountain guide, a cook and all meals.',
        ],
        '3-days-berber-villages-trek' => [
            'title'       => 'Atlas Mountains Berber Villages Trek | 3 Days',
            'h1'          => '3-Day Berber Villages Trek in the Atlas Mountains',
            'description' => '3-day trek through Atlas Mountains Berber villages from Imlil, via Tinerhourhine and the Mazik pass (2,500 m), with a mountain guide, a cook and all meals.',
        ],
        '3-days-toubkal-summit-trek' => [
            'title'       => 'Toubkal Trek | 3-Day Mount Toubkal Summit',
            'h1'          => '3-Day Toubkal Trek to the Summit of Mount Toubkal',
            'description' => '3-day Toubkal trek from Imlil via Tamsoult to the Toubkal refuge and the summit of Mount Toubkal, with a mountain guide, a cook and all meals.',
        ],
    ],

    /* --------------------------------------------------------------- LOCATIONS */
    'locations' => [
        'marrakech-morocco' => [
            'title'       => 'Marrakech Tours & Things to Do in Marrakesh',
            'h1'          => 'Marrakech Tours & Things to Do',
            'description' => 'Marrakech tours and things to do: private desert tours, day trips to the Atlas and Essaouira, and activities from balloon rides to hammams and cooking classes.',
        ],
        'fes-morocco' => [
            'title'       => 'Fez Tours & Things to Do in Fez, Morocco',
            'h1'          => 'Fez Tours & Things to Do',
            'description' => 'Fez tours and things to do: a guided medina tour, day trips to Chefchaouen, Volubilis and the Middle Atlas, and private desert tours from Fes to Merzouga.',
        ],
        'casablanca-morocco' => [
            'title'       => 'Casablanca Tours | Day Trips & Desert Tours',
            'h1'          => 'Casablanca Tours & Things to Do',
            'description' => 'Casablanca tours: private day trips to Rabat, Marrakech and Fes, and multi-day tours from Casablanca to Chefchaouen, the imperial cities and the Sahara.',
        ],
        'tangier-morocco' => [
            'title'       => 'Things to Do in Tangier | Tangier Tours',
            'h1'          => 'Things to Do in Tangier',
            'description' => 'Things to do in Tangier and beyond: private tours from Tangier to Chefchaouen, Fes, the Atlantic coast, the Merzouga dunes and Marrakech.',
        ],
        'agadir-morocco' => [
            'title'       => 'Things to Do in Agadir | Desert Tours from Agadir',
            'h1'          => 'Things to Do in Agadir',
            'description' => 'Things to do in Agadir: private desert tours from Agadir to Zagora, Erg Chigaga and Merzouga through Taroudant, the Draa Valley and Aït Benhaddou.',
        ],
        'chefchaouen-morocco' => [
            'title'       => 'Things to Do in Chefchaouen | Chefchaouen Tours',
            'h1'          => 'Things to Do in Chefchaouen',
            'description' => 'Things to do in Chefchaouen, the Blue City: private tours from Chefchaouen through Fes and the Middle Atlas to the Merzouga desert and Marrakech.',
        ],
        'ouarzazate-morocco' => [
            'title'       => 'Things to Do in Ouarzazate | Ouarzazate Tours',
            'h1'          => 'Things to Do in Ouarzazate',
            'description' => 'Things to do in Ouarzazate: private desert tours from Ouarzazate to Merzouga and Fes through Skoura, the Dades and Todra gorges and the Draa Valley.',
        ],
        // Generic catch-all location: not a keyword target, kept out of the index.
        'morocco' => [
            'robots' => 'noindex, follow',
        ],
    ],

    /* ------------------------------------------------------------------- POSTS */
    'posts' => [
        'best-places-to-visit-in-morocco-in-september' => [
            'title'       => 'Morocco Weather in September & Best Places to Visit',
            'h1'          => 'Morocco Weather in September & Best Places to Visit',
            'description' => 'Morocco weather in September by region: hot early-month days in Marrakech and the Sahara, a mild Atlantic coast and good Atlas trekking, plus where to go.',
        ],
    ],

    /* ---------------------------------------------------- TOUR LISTING PAGES
     | Key: "{type}|{location_slug}" (type: multi_day, day_trip or empty).
     | Unlisted type+city combinations with tours fall back to a generic unique
     | title; combinations with no tours are noindexed by the controller. */
    'listings' => [
        '|' => [
            'title'       => 'Tours in Morocco | Private Guided Tours & Day Trips',
            'h1'          => 'Private Guided Tours in Morocco',
            'description' => 'Private guided tours in Morocco: Sahara desert trips, imperial cities, Atlas treks and day trips from Marrakech, Fes, Casablanca, Tangier and Agadir.',
            'intro'       => 'Browse :count private guided tours in Morocco, from day trips to two-week circuits, each with a local driver-guide.',
        ],
        'multi_day|' => [
            'title'       => 'Sahara Desert Tours & Multi-Day Morocco Trips',
            'h1'          => 'Multi-Day Sahara Desert Tours in Morocco',
            'description' => 'Private multi-day Sahara desert tours in Morocco: 2 to 15 days from Marrakech, Fes, Casablanca, Tangier and more, with desert camps and a local guide.',
            'intro'       => ':count private multi-day tours, most of them Sahara routes to the Merzouga and Erg Chigaga dunes, Zagora and the Atlas, starting from seven Moroccan cities.',
        ],
        'day_trip|' => [
            'title'       => 'Private Day Trips & Excursions in Morocco',
            'h1'          => 'Private Day Trips & Excursions in Morocco',
            'description' => 'Private day trips and excursions from Marrakech, Fes and Casablanca: Agafay desert, Ourika Valley, Ouzoud waterfalls, Chefchaouen, Volubilis and Rabat.',
            'intro'       => ':count private day trips with hotel pick-up and a local driver-guide, back at your hotel the same evening.',
        ],
        'multi_day|marrakech-morocco' => [
            'title'       => 'Marrakech Desert Tours | Private Sahara Trips',
            'h1'          => 'Marrakech Desert Tours',
            'description' => 'Private desert tours from Marrakech: 2 to 11 days to Merzouga, Zagora and Erg Chigaga over the Atlas via Aït Benhaddou, with camel treks and desert camps.',
            'intro'       => ':count private desert tours from Marrakech, from a 2-day Zagora trip to the Merzouga and Erg Chigaga dunes and longer circuits to Fes and Chefchaouen.',
            'location_link' => 'More about Marrakech',
        ],
        'day_trip|marrakech-morocco' => [
            'title'       => 'Marrakech Excursions | Private Day Tours',
            'h1'          => 'Private Excursions from Marrakech',
            'description' => 'Private day tours from Marrakech to the Agafay desert, Ourika Valley, Ouzoud waterfalls, Ait Ben Haddou and Essaouira, with hotel pick-up and a driver-guide.',
            'intro'       => ':count private excursions from Marrakech to the desert, the Atlas Mountains, the waterfalls and the Atlantic coast, back the same evening.',
            'location_link' => 'More about Marrakech',
        ],
        'multi_day|fes-morocco' => [
            'title'       => 'Desert Tours from Fes | Merzouga & Sahara Trips',
            'h1'          => 'Desert Tours from Fes',
            'description' => 'Private desert tours from Fes: 2 to 5 days to the Merzouga dunes and back, or across the Sahara to Marrakech, plus a 2-day trip to Chefchaouen.',
            'intro'       => ':count private tours from Fes: through the Middle Atlas to the Merzouga dunes and back, across the Sahara to Marrakech, or north to Chefchaouen.',
            'location_link' => 'More about Fes',
        ],
        'day_trip|fes-morocco' => [
            'title'       => 'Day Trips from Fes | Private Excursions',
            'h1'          => 'Private Day Trips from Fes',
            'description' => 'Private day trips from Fes to Chefchaouen, Meknes and Volubilis, Rabat and the Middle Atlas cedar forests, plus a guided tour of the Fes medina.',
            'intro'       => ':count private day trips from Fes, to the Blue City, the Roman ruins of Volubilis, Rabat and the Middle Atlas, plus a guided medina tour.',
            'location_link' => 'More about Fes',
        ],
        'multi_day|casablanca-morocco' => [
            'title'       => 'Desert Tours from Casablanca | Private Trips',
            'h1'          => 'Desert & Multi-Day Tours from Casablanca',
            'description' => 'Private tours from Casablanca: 2 to 14 days to Marrakech, Chefchaouen, Fes, Essaouira and the Sahara, including grand Morocco circuits and imperial cities.',
            'intro'       => ':count private tours from Casablanca, from 2-day trips to Marrakech or Chefchaouen to Sahara desert tours and 11- and 14-day grand tours.',
            'location_link' => 'More about Casablanca',
        ],
        'day_trip|casablanca-morocco' => [
            'title'       => 'Day Trips from Casablanca | Rabat, Marrakech, Fes',
            'h1'          => 'Private Day Trips from Casablanca',
            'description' => 'Private day trips from Casablanca to Rabat, Marrakech and Fes, with hotel pick-up, a local driver-guide and return the same evening.',
            'intro'       => ':count private day trips from Casablanca to Rabat, Marrakech and Fes, back at your hotel the same evening.',
            'location_link' => 'More about Casablanca',
        ],
        'multi_day|tangier-morocco' => [
            'title'       => 'Tours from Tangier | Chefchaouen, Fes & Sahara',
            'h1'          => 'Private Tours from Tangier',
            'description' => 'Private tours from Tangier: 2 to 12 days to Chefchaouen, Fes, the Atlantic coast, the Merzouga dunes and Marrakech, with a local driver-guide.',
            'intro'       => ':count private tours from Tangier, from a 2-day Chefchaouen trip to 12-day circuits through Fes, the Sahara and Marrakech.',
            'location_link' => 'More about Tangier',
        ],
        // Thin filter pages (3 tours each, all listed on the location page):
        // unique title, kept out of the index, linked to the location page.
        'multi_day|agadir-morocco' => [
            'title'       => 'Tours from Agadir | Sahara Desert Trips',
            'h1'          => 'Private Tours from Agadir',
            'description' => 'Private desert tours from Agadir to Zagora, Erg Chigaga and Merzouga, through Taroudant, the Draa Valley and Aït Benhaddou.',
            'robots'      => 'noindex, follow',
            'location_link' => 'Things to do in Agadir',
        ],
        'multi_day|chefchaouen-morocco' => [
            'title'       => 'Tours from Chefchaouen | Sahara Desert Trips',
            'h1'          => 'Private Tours from Chefchaouen',
            'description' => 'Private desert tours from Chefchaouen through Fes to the Merzouga desert and Marrakech.',
            'robots'      => 'noindex, follow',
            'location_link' => 'Things to do in Chefchaouen',
        ],
        'multi_day|ouarzazate-morocco' => [
            'title'       => 'Tours from Ouarzazate | Merzouga Desert Trips',
            'h1'          => 'Private Tours from Ouarzazate',
            'description' => 'Private desert tours from Ouarzazate to Merzouga and Fes, through Skoura, the Dades and Todra gorges and the Draa Valley.',
            'robots'      => 'noindex, follow',
            'location_link' => 'Things to do in Ouarzazate',
        ],
        'multi_day|morocco' => [
            'title'       => 'Morocco & Spain Tours',
            'h1'          => 'Morocco & Spain Tours',
            'description' => 'A private 15-day tour combining Spain and Morocco, from Madrid and Andalusia to Chefchaouen, Fes, the Sahara and Marrakech.',
            'robots'      => 'noindex, follow',
        ],
    ],
];
