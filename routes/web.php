<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController as FrontPostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\FrontTourController;
use App\Http\Controllers\FrontActivityController;
use App\Http\Controllers\FrontTrekkingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TourCategoryController;
use App\Http\Controllers\Admin\TourController as AdminTourController;
use App\Http\Controllers\Admin\LocationController as AdminLocationController;
use App\Http\Controllers\Admin\ActivityCategoryController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\TrekkingCategoryController;
use App\Http\Controllers\Admin\TrekkingController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\SpecialOfferController;
use App\Http\Controllers\Admin\RatingCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TourReservationController;
use App\Http\Controllers\ActivityReservationController;
use App\Http\Controllers\TrekkingReservationController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FrontLocationController;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

// Tours
Route::prefix('tours')->name('front.tours.')->group(function () {
    Route::get('/', [FrontTourController::class, 'index'])->name('index');

    // Clean, crawlable URLs for the two tour types that the main navigation
    // links to. These replace /tours?type=multi_day and /tours?type=day_trip so
    // the nav no longer points crawlers at query-parameter URLs. Both merge the
    // type into the request and reuse FrontTourController@index unchanged.
    Route::get('/multi-day', function (Request $request, FrontTourController $controller) {
        $request->merge(['type' => 'multi_day']);
        return $controller->index($request);
    })->name('multiDay');

    Route::get('/day-trips', function (Request $request, FrontTourController $controller) {
        $request->merge(['type' => 'day_trip']);
        return $controller->index($request);
    })->name('dayTrips');

    // Same, one level deeper: /tours/multi-day/from/marrakech
    Route::get('/multi-day/from/{location_slug}', function (Request $request, FrontTourController $controller, $location_slug) {
        $request->merge(['type' => 'multi_day', 'location_slug' => $location_slug]);
        return $controller->index($request);
    })->name('multiDayFrom');

    Route::get('/day-trips/from/{location_slug}', function (Request $request, FrontTourController $controller, $location_slug) {
        $request->merge(['type' => 'day_trip', 'location_slug' => $location_slug]);
        return $controller->index($request);
    })->name('dayTripsFrom');

    Route::get('/{slug}', [FrontTourController::class, 'show'])->name('show');
    Route::post('/{slug}/leave-review', [FrontTourController::class, 'leaveReview'])->name('leaveReview')->middleware('recaptcha:leave_review');
    Route::post('/{slug}/reserve', [TourReservationController::class, 'store'])->name('reserve')->middleware('recaptcha:reserve');
});

// Activities
Route::prefix('activities')->name('front.activities.')->group(function () {
    Route::get('/', [FrontActivityController::class, 'index'])->name('index');
    Route::get('/{slug}', [FrontActivityController::class, 'show'])->name('show');
    Route::post('/{slug}/leave-review', [FrontActivityController::class, 'leaveReview'])->name('leaveReview')->middleware('recaptcha:leave_review');
    Route::post('/{slug}/reserve', [ActivityReservationController::class, 'store'])->name('reserve')->middleware('recaptcha:reserve');
});

// Trekking
Route::prefix('trekking')->name('front.trekking.')->group(function () {
    Route::get('/', [FrontTrekkingController::class, 'index'])->name('index');
    Route::get('/{slug}', [FrontTrekkingController::class, 'show'])->name('show');
    Route::post('/{slug}/leave-review', [FrontTrekkingController::class, 'leaveReview'])->name('leaveReview')->middleware('recaptcha:leave_review');
    Route::post('/{slug}/reserve', [TrekkingReservationController::class, 'store'])->name('reserve')->middleware('recaptcha:reserve');
});

// Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [FrontPostController::class, 'index'])->name('index');
    Route::get('/search', [FrontPostController::class, 'search'])->name('search');
    Route::get('/category/{slug}', [FrontPostController::class, 'category'])->name('category');
    Route::get('/tag/{slug}', [FrontPostController::class, 'tag'])->name('tag');
    Route::get('/{slug}', [FrontPostController::class, 'show'])->name('show');
    Route::post('/{slug}/leave-review', [FrontPostController::class, 'leaveReview'])->name('leaveReview')->middleware('recaptcha:leave_review');
});

// Locations (frontend)
Route::prefix('locations')->name('front.locations.')->group(function () {
    Route::get('/', [FrontLocationController::class, 'index'])->name('index');
    Route::get('/{slug}', [FrontLocationController::class, 'show'])->name('show');
});

// Reviews
Route::post('/review/{review}/helpful', [ReviewController::class, 'markHelpful'])->name('review.helpful');
Route::post('/review/{review}/not-helpful', [ReviewController::class, 'markNotHelpful'])->name('review.notHelpful');

// Static Pages
Route::view('/contact', 'front.contact')->name('front.contact');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send')->middleware('recaptcha:contact');

Route::view('/about', 'front.about')->name('front.about');
Route::view('/terms', 'front.terms')->name('front.terms');
Route::view('/privacy', 'front.privacy')->name('front.privacy');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe')->middleware('recaptcha:newsletter');

Route::view('/404', 'errors.404')->name('error.404');
Route::view('/help-center', 'front.help-center')->name('front.help-center');

// Authenticated user profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::get('/locations/{location}', [LocationController::class, 'show'])->name('locations.show');
});


Route::get('/test-mail', function () {
    \Illuminate\Support\Facades\Mail::raw('Test email from Laravel.', function ($message) {
        $message->to('localmoroccotour@gmail.com')
                ->subject('Test Email');
    });

    return 'Mail sent!';
});

// Admin routes
Route::middleware(['auth'])->prefix('adminPanel')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/posts/upload-image', [AdminPostController::class, 'uploadImage'])->name('posts.upload-image');

    Route::resources([
        'tour-categories' => TourCategoryController::class,
        'tours' => AdminTourController::class,
        'locations' => AdminLocationController::class,
        'activity-categories' => ActivityCategoryController::class,
        'activities' => ActivityController::class,
        'trekking-categories' => TrekkingCategoryController::class,
        'trekking' => TrekkingController::class,
        'blog-categories' => BlogCategoryController::class,
        'posts' => AdminPostController::class,
        'tags' => TagController::class,
        'special-offers' => SpecialOfferController::class,
        'rating-categories' => RatingCategoryController::class,
        'users' => AdminUserController::class,
    ]);
});

// Optional: Fallback route for unmatched URLs
Route::fallback(function () {
    return redirect()->route('error.404');
});
