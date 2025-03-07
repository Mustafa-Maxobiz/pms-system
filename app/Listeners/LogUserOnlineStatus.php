<?php
namespace App\Listeners;

use App\Events\MemberStatus;
use App\Events\TimeProgress;
use Illuminate\Auth\Events\Login;
use App\Models\UserLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Events\UserStatusUpdated;

class LogUserOnlineStatus
{
    public function __construct()
    {
        //
    }

    public function handle(Login $event): void
    {
        // Log the login event
        Log::info("User {$event->user->id} logged in");

        // Log the user's status as 'Online'
        UserLog::create([
            'user_id' => $event->user->id,
            'user_status_id' => 8, // Online status
            'status' => 'Online',
            'status_time' => Carbon::now()->format('H:i:s'),
        ]);

        broadcast(new UserStatusUpdated($event->user->name, 'logged in'))->toOthers();
        broadcast(new MemberStatus($event->user->team_id));
        broadcast(new TimeProgress());

    }
}
