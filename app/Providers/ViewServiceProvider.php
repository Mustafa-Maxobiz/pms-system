<?php
namespace App\Providers;

use App\Helpers\Helper;
use App\Models\UserLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $userId = Auth::id();

                // Get today's logs
                $todayLogs = UserLog::where('user_id', $userId)
                    ->whereDate('created_at', Carbon::today())
                    ->orderBy('created_at', 'asc')
                    ->pluck('created_at');

                $todayTotalSeconds = 0;
                if ($todayLogs->count() > 0) {
                    for ($i = 0; $i < $todayLogs->count() - 1; $i++) {
                        $start = Carbon::parse($todayLogs[$i]);
                        $end   = Carbon::parse($todayLogs[$i + 1]);
                        $todayTotalSeconds += $start->diffInSeconds($end);
                    }

                    // If user is still logged in, add live session time
                    if ($todayLogs->count() % 2 == 1) {
                        $lastLogin = Carbon::parse($todayLogs->last());
                        $now       = Carbon::now();
                        $todayTotalSeconds += $lastLogin->diffInSeconds($now);
                    }
                }

                $todayTotalMinutes = floor($todayTotalSeconds / 60);
                $todayTime         = Helper::formatTime($todayTotalMinutes);

                // Share with all views
                $view->with('todayTime', $todayTime)
                    ->with('todayTotalSeconds', $todayTotalSeconds);
            }
        });
    }
}
