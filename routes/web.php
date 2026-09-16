<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Http\Request;

/**
 * Home Route
 */
Route::get('/', [HomeController::class, 'index'])->name('home');

// Basic pages — Vietnamese slugs
Route::get('/ve-chung-toi', function () {
    return view('pages.about');
})->name('about');

Route::get('/dich-vu', function () {
    return view('pages.services');
})->name('services');

Route::get('/san-pham', function () {
    return view('pages.products');
})->name('san-pham');

Route::get('/du-an', function () {
    return view('pages.projects');
})->name('projects');

Route::get('/lien-he', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/thanh-toan', function () {
    return view('pages.book-table');
})->name('thanh-toan');

Route::get('/cau-hoi-thuong-gap', function () {
    return view('pages.faqs');
})->name('faqs');

Route::get('/dieu-khoan-su-dung', function () {
    return view('pages.terms-of-service');
})->name('terms-of-service');

Route::get('/chinh-sach-bao-mat', function () {
    return view('pages.privacy-policy');
})->name('privacy-policy');

Route::get('/so-do-trang', function () {
    return view('pages.sitemap');
})->name('sitemap');

Route::permanentRedirect('/about', '/ve-chung-toi');
Route::permanentRedirect('/services', '/dich-vu');
Route::permanentRedirect('/projects', '/du-an');
Route::permanentRedirect('/contact', '/lien-he');
Route::permanentRedirect('/book-table', '/thanh-toan');
Route::permanentRedirect('/faqs', '/cau-hoi-thuong-gap');
Route::permanentRedirect('/terms-of-service', '/dieu-khoan-su-dung');
Route::permanentRedirect('/privacy-policy', '/chinh-sach-bao-mat');
Route::permanentRedirect('/sitemap', '/so-do-trang');

// Contact form POST handler
$sendContact = function (Request $request) {
    $data = $request->validate([
        'name' => 'nullable|string|max:191',
        'email' => 'required|email|max:191',
        'phone' => 'nullable|string|max:30',
        'message' => 'nullable|string|max:2000',
        'intent' => 'nullable|in:contact,newsletter',
    ]);

    $intent = $data['intent'] ?? 'contact';

    if ($intent === 'contact') {
        $request->validate([
            'name' => 'required|string|max:191',
            'message' => 'required|string|max:2000',
        ]);
    }

    $ok = $intent === 'newsletter'
        ? 'Đã ghi nhận email. Tiệm sẽ gửi tin khi có mùa vụ mới.'
        : 'Cảm ơn bạn. Tiệm Nhà Duy đã nhận thư và sẽ phản hồi sớm.';

    return back()->with('status', $ok);
};

Route::post('/lien-he/gui', $sendContact)->name('contact.send');
Route::post('/contact/send', $sendContact);


/**
 * Placeholder Routes (to be implemented)
 * These routes are referenced in the Blade templates
 */

// Search
Route::get('/search', function () {
    return view('home'); // Placeholder
})->name('search');

// Products
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/{id}', function ($id) {
        return redirect()->route('home'); // Placeholder
    })->name('show');
    
    Route::get('/featured', function () {
        return redirect()->route('home'); // Placeholder
    })->name('featured');
    
    Route::get('/flash-sale', function () {
        return redirect()->route('home'); // Placeholder
    })->name('flash-sale');
});

// Shop
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/grid', function () {
        return redirect()->route('home'); // Placeholder
    })->name('grid');
    
    Route::get('/list', function () {
        return redirect()->route('home'); // Placeholder
    })->name('list');
});

// Categories
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/{slug}', function ($slug) {
        return redirect()->route('home'); // Placeholder
    })->name('show');
});

// Collections
Route::prefix('collections')->name('collections.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('home'); // Placeholder
    })->name('index');
    
    Route::get('/{id}', function ($id) {
        return redirect()->route('home'); // Placeholder
    })->name('show');
});

// Cart
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('home'); // Placeholder
    })->name('index');
});

// Wishlist
Route::prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('home'); // Placeholder
    })->name('index');
    
    Route::get('/grid', function () {
        return redirect()->route('home'); // Placeholder
    })->name('grid');
    
    Route::get('/list', function () {
        return redirect()->route('home'); // Placeholder
    })->name('list');
});

// Profile
Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('home'); // Placeholder
    })->name('show');
});

// Messages
Route::prefix('messages')->name('messages.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('home'); // Placeholder
    })->name('index');
});

// Notifications
Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('home'); // Placeholder
    })->name('index');
});

// Pages
Route::prefix('pages')->name('pages.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('home'); // Placeholder
    })->name('index');
});

// Offline Page
Route::get('/offline', function () {
    return view('pages.offline');
})->name('offline');

// Settings
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('home'); // Placeholder
    })->name('index');
});

// Auth Routes (placeholders - will be implemented with Laravel Breeze/Fortify)
Route::get('/login', function () {
    return redirect()->route('home'); // Placeholder
})->name('login');

Route::get('/register', function () {
    return redirect()->route('home'); // Placeholder
})->name('register');

Route::post('/logout', function () {
    return redirect()->route('home'); // Placeholder
})->name('logout');
