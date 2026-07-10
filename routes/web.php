<?php

use App\Support\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::get('/', function () {
    return view('welcome', [
        'content' => SiteContent::get(),
    ]);
})->name('home');

Route::get('/admin', function () {
    if (! session('admin_authenticated')) {
        return view('admin.login');
    }

    return view('admin.dashboard', [
        'content' => SiteContent::get(),
        'backups' => SiteContent::backups(),
        'savedPath' => SiteContent::path(),
    ]);
})->name('admin');

Route::post('/admin/login', function (Request $request) {
    $request->validate([
        'password' => ['required', 'string'],
    ]);

    $throttleKey = 'admin-login:'.$request->ip();

    if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
        throw ValidationException::withMessages([
            'password' => 'Túl sok próbálkozás. Kérlek, próbáld újra '.RateLimiter::availableIn($throttleKey).' másodperc múlva.',
        ]);
    }

    $password = config('admin.password');
    $password = is_string($password) ? trim($password) : '';

    // Fail closed when ADMIN_PASSWORD is missing, empty, or incorrect.
    if ($password === '' || ! hash_equals($password, $request->string('password')->toString())) {
        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors(['password' => 'Hibás admin jelszó.'])->onlyInput();
    }

    RateLimiter::clear($throttleKey);
    // Regenerate after login to prevent session fixation.
    $request->session()->regenerate();
    $request->session()->put('admin_authenticated', true);

    return redirect()->route('admin');
})->name('admin.login');

Route::post('/admin', function (Request $request) {
    abort_unless(session('admin_authenticated'), 403);

    $validated = $request->validate([
        'content' => ['nullable', 'array'],
        'content.services' => ['nullable', 'array'],
        'content.services.*.title' => ['nullable', 'string', 'max:80'],
        'content.services.*.description' => ['nullable', 'string', 'max:500'],
        'content.services.*.price' => ['nullable', 'string', 'max:40'],
        'content.services.*.image' => ['nullable', 'string', 'max:160'],
        'content.services.*.alt' => ['nullable', 'string', 'max:160'],
        'content.gallery' => ['nullable', 'array'],
        'content.gallery.*.image' => ['nullable', 'string', 'max:160'],
        'content.gallery.*.alt' => ['nullable', 'string', 'max:160'],
        'content.price_groups' => ['nullable', 'array'],
        'content.price_groups.*.title' => ['nullable', 'string', 'max:80'],
        'content.price_groups.*.items' => ['nullable', 'array'],
        'content.price_groups.*.items.*.name' => ['nullable', 'string', 'max:160'],
        'content.price_groups.*.items.*.price' => ['nullable', 'string', 'max:40'],
        'content.benefits' => ['nullable', 'array'],
        'content.benefits.*.title' => ['nullable', 'string', 'max:80'],
        'content.benefits.*.description' => ['nullable', 'string', 'max:500'],
        'content.testimonials' => ['nullable', 'array'],
        'content.testimonials.*.name' => ['nullable', 'string', 'max:80'],
        'content.testimonials.*.quote' => ['nullable', 'string', 'max:500'],
        'content.faq' => ['nullable', 'array'],
        'content.faq.*.question' => ['nullable', 'string', 'max:160'],
        'content.faq.*.answer' => ['nullable', 'string', 'max:700'],
    ]);

    SiteContent::save($validated['content'] ?? []);

    return redirect()->route('admin')->with('status', 'A módosítások mentve. Biztonsági mentés készült az előző állapotról.');
})->name('admin.save');

Route::post('/admin/restore', function (Request $request) {
    abort_unless(session('admin_authenticated'), 403);

    $validated = $request->validate([
        'backup' => ['required', 'string'],
    ]);

    if (! SiteContent::restore($validated['backup'])) {
        return redirect()->route('admin')->withErrors(['backup' => 'A mentés visszaállítása nem sikerült.']);
    }

    return redirect()->route('admin')->with('status', 'A kiválasztott mentés visszaállítva. A korábbi aktuális tartalomról is készült mentés.');
})->name('admin.restore');

Route::post('/admin/logout', function (Request $request) {
    abort_unless(session('admin_authenticated'), 403);

    $request->session()->forget('admin_authenticated');
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('admin');
})->name('admin.logout');
