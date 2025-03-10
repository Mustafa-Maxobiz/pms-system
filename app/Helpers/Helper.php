<?php
// app/Helpers/Helper.php

namespace App\Helpers;

use App\Models\TaskNotification;
use App\Events\TaskNotification as EventTaskNotification;
use App\Events\DasbhoardTask as EventDasbhoardTask;
use App\Events\TopTaskCountNotification as EventTopTaskCountNotification;
use App\Models\UserLog;
use App\Models\UserStatus;
use App\Models\Setting;
use DB;
use Illuminate\Support\Facades\Auth;

class Helper
{
    /**
     * Get all user statuses ordered by the order_by field.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    
     public static function getSetting()
    {
        return Setting::first();
    }
     public static function getUserStatuses()
    {
        return UserStatus::orderBy('order_by')->get();
    }

    public static function getLastStatusID()
    {
        $lastStatus = UserLog::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return $lastStatus ? $lastStatus->user_status_id : null; // Ensure it returns null if no status found
    }
    public static function getLastStatusValue()
    {
        $lastStatus = UserLog::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return $lastStatus ? $lastStatus->status : null; // Ensure it returns null if no status found
    }

    public static function unSeenCount()
    {
        $userChannelId = DB::table('ch_channel_user')
            ->where('user_id', Auth::id())
            ->value('channel_id');

        if (! $userChannelId) {
            return 0; // No channel assigned
        }

        $whereRawCondition = "(seen IS NULL OR JSON_UNQUOTE(seen) = '[]')";

        return DB::table('ch_messages')
            ->where('to_channel_id', $userChannelId)
            ->where('from_id', '!=', Auth::id())
            ->whereNotNull('from_id')
            ->whereRaw($whereRawCondition)
            ->count();
    }

    public static function createTaskNotification($userId, $taskId, $message, $is_admin = '')
    {
        return TaskNotification::create([
            'user_id' => $userId,
            'task_id' => $taskId,
            'type'    => 'task_assigned',
            'is_admin'    => 'read',
            'message' => $message,
        ]);
    }

    public static function updateTaskNotification($userId, $taskId, $message = "You have been assigned a new task.")
    {
        return TaskNotification::create([
            'user_id' => $userId,
            'task_id' => $taskId,
            'type'    => 'task_reassigned',
            'message' => $message,
        ]);
    }

    public static function broadcastTaskNotifications($message, $assignId)
    {
        broadcast(new EventTaskNotification($message, $assignId))->toOthers();
        broadcast(new EventDasbhoardTask())->toOthers();
        broadcast(new EventTopTaskCountNotification())->toOthers();
    }

    public static function formatTime($totalMinutes)
    {
        $hours   = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        if ($hours > 0 && $minutes > 0) {
            // return "{$hours} H {$minutes} M";
            return "{$hours} H";
        } elseif ($hours > 0) {
            return "{$hours} H";
        } else {
            return "{$minutes} M";
        }
    }

}
