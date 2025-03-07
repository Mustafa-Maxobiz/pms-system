<?php
namespace App\Http\Controllers;

use App\Models\TaskNotification;
use App\Events\TopTaskCountNotification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getUnreadNotification(Request $request)
    {
        $user = User::with(['rolename'])->where('id', $request->user_id)->first();

        $role_name = $user->rolename->first()->name;

        if (!$user) {
            return response()->json([
                'count'         => 0,
                'notifications' => [],
            ]);
        }

        // Agar Super Admin hai, to saare unread notifications laao
        if ($role_name === 'Super Admin') {
            $unreadNotifications = TaskNotification::where('is_admin', 'unread')->get();
        } else {
            // Normal user ke liye sirf uske notifications
            $unreadNotifications = TaskNotification::where('user_id', $request->user_id)
                ->where('status', 'unread')
                ->get();
        }

        return response()->json([
            'count'         => $unreadNotifications->count(),
            'notifications' => $unreadNotifications,
        ]);
    }

    public function markAsRead(Request $request)
    {
        $user = User::with(['rolename'])->where('id', $request->user_id)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
            ]);
        }

        $role_name = $user->rolename->first()->name;

        if ($role_name === 'Super Admin') {
            // Super Admin ke liye, saare unread notifications ka is_admin column 'read' ho jaye
            TaskNotification::where('is_admin', 'unread')->update(['is_admin' => 'read']);
        } else {
            // Normal user ke liye, sirf uski unread notifications ka status 'read' ho jaye
            TaskNotification::where('user_id', $request->user_id)
                ->where('status', 'unread')
                ->update(['status' => 'read']);
        }

        broadcast(new TopTaskCountNotification())->toOthers();

        return response()->json([
            'success' => true,
        ]);
    }

}
