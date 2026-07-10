<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('about:brill', function (): void {
    $this->info('Brill Lash and Beauty website');
});
