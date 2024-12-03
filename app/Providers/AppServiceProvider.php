<?php

namespace App\Providers;

use App\Services\Acc\AccTransaction;
use App\Services\SalaryService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\App;
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
       $this->app->bind(TransactionService::class,App::class);
       $this->app->bind(TransactionService::class,AccTransaction::class);
       $this->app->bind(SalaryService::class,TransactionService::class);
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
