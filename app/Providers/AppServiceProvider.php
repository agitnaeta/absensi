<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('rupiah', function ( $expression ) {
            return "Rp. <?php echo number_format($expression,0,',','.'); ?>";
        });


        LogViewer::auth(function ($request) {
            if(backpack_auth()->user()){
                return true;
            }
            return false;
        });
    }
}
