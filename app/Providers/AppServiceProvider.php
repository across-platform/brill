<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::directive('svg', function (string $expression): string {
            return "<?php echo view('components.icon', ['name' => {$expression}])->render(); ?>";
        });
    }
}
