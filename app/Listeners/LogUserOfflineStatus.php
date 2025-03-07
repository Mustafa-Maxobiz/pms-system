<?php
namespace App\Listeners;

use App\Events\MemberStatus;
use App\Events\TimeProgress;
use Illuminate\Auth\Events\Logout;
use App\Models\UserLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Events\UserStatusUpdated;

class LogUserOfflineStatus
{
    public function __construct()
    {
        //
    }

    public function handle(Logout $event): void
    {
        // Log the logout event
        Log::info("User {$event->user->id} logged out");

        // Log the user's status as 'Offline'
        UserLog::create([
            'user_id' => $event->user->id,
            'user_status_id' => 10, // Offline status
            'status' => 'Offline',
            'status_time' => Carbon::now()->format('H:i:s'),
        ]);

        broadcast(new UserStatusUpdated($event->user->name, 'logged out'))->toOthers();
        broadcast(new MemberStatus($event->user->team_id));
        broadcast(new TimeProgress());
    }
}
