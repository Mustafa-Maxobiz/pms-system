<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ExternalStatusController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\SubDepartmentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskPriorityController;
use App\Http\Controllers\TaskStageController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TaskTypeController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamTargetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLogController;
use App\Http\Controllers\UserStatusController;
use App\Http\Controllers\UserTaskController;
use App\Http\Controllers\ProjectTrackerController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});



Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('notificationsCount', [DashboardController::class, 'notificationsCount'])->name('notificationsCount');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/create', [PermissionController::class, 'create'])->name('create');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [PermissionController::class, 'show']);
    });
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        Route::get('/{role}', [RoleController::class, 'show'])->name('show'); // Updated
    });

    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
        Route::get('/{project}/details', [ProjectController::class, 'details'])->name('details');
        Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('show');

        // Task routes
        Route::prefix('/{project}/tasks')->name('tasks.')->group(function () {
            Route::get('/', [TaskController::class, 'index'])->name('index');
            Route::get('/create', [TaskController::class, 'create'])->name('create');
            Route::post('/store', [TaskController::class, 'store'])->name('store');
            Route::post('/save-time-log', [TaskController::class, 'save_time_log'])->name('save-time-log'); // Create a new conversation message
            Route::get('/{task}', [TaskController::class, 'show'])->name('show');
            Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
            Route::get('/{task}/details', [TaskController::class, 'details'])->name('details');
            Route::put('/{task}', [TaskController::class, 'update'])->name('update');
            Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
            Route::delete('/{task}/delete-attachment', [TaskController::class, 'deleteAttachment'])->name('delete-attachment');

            // Task Conversation routes
            Route::prefix('/{task}/conversations')->name('conversations.')->group(function () {
                Route::get('/', [ConversationController::class, 'index'])->name('index');       // List all conversations for a task
                Route::post('/store', [ConversationController::class, 'store'])->name('store'); // Create a new conversation message
                Route::get('/load-conversations', [ConversationController::class, 'loadConversations'])->name('loadConversations');
                Route::get('/{conversation}', [ConversationController::class, 'show'])->name('show'); // Show a specific conversation
                //Route::put('/{conversation}', [ConversationController::class, 'update'])->name('update'); // Update a specific conversation
                //Route::delete('/{conversation}', [ConversationController::class, 'destroy'])->name('destroy'); // Delete a specific conversation

            });
        });

        // Payment routes
        Route::prefix('/{project}/payments')->name('payments.')->group(function () {
            Route::get('/', [PaymentController::class, 'index'])->name('index');
            Route::get('/create', [PaymentController::class, 'create'])->name('create');
            Route::post('/store', [PaymentController::class, 'store'])->name('store');
            Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
            Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
            Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
            Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
        });
    });

    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [ClientController::class, 'show'])->name('show');
    });

    Route::prefix('sources')->name('sources.')->group(function () {
        Route::get('/', [SourceController::class, 'index'])->name('index');
        Route::get('/create', [SourceController::class, 'create'])->name('create');
        Route::post('/', [SourceController::class, 'store'])->name('store');
        Route::get('/{source}/edit', [SourceController::class, 'edit'])->name('edit');
        Route::put('/{source}', [SourceController::class, 'update'])->name('update');
        Route::delete('/{source}', [SourceController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [SourceController::class, 'show'])->name('show');
    });

    Route::prefix('knowledge-base')->name('knowledge-base.')->group(function () {
        Route::get('/', [KnowledgeBaseController::class, 'index'])->name('index');
        Route::get('/create', [KnowledgeBaseController::class, 'create'])->name('create');
        Route::post('/', [KnowledgeBaseController::class, 'store'])->name('store');
        Route::get('/{KnowledgeBase}/edit', [KnowledgeBaseController::class, 'edit'])->name('edit');
        Route::put('/{KnowledgeBase}', [KnowledgeBaseController::class, 'update'])->name('update');
        Route::delete('/{KnowledgeBase}', [KnowledgeBaseController::class, 'destroy'])->name('destroy');
        Route::delete('/{id}/delete-attachment', [KnowledgeBaseController::class, 'deleteAttachment'])->name('delete-attachment');
        Route::get('/{id}', [KnowledgeBaseController::class, 'show'])->name('show');
    });

    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index');
        Route::get('/create', [DepartmentController::class, 'create'])->name('create');
        Route::post('/', [DepartmentController::class, 'store'])->name('store');
        Route::get('/{department}/edit', [DepartmentController::class, 'edit'])->name('edit');
        Route::put('/{department}', [DepartmentController::class, 'update'])->name('update');
        Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [DepartmentController::class, 'show'])->name('show');
    });

    Route::prefix('sub-departments')->name('subdepartments.')->group(function () {
        Route::get('/', [SubDepartmentController::class, 'index'])->name('list');
        Route::get('/create', [SubDepartmentController::class, 'create'])->name('create');
        Route::post('/', [SubDepartmentController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SubDepartmentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SubDepartmentController::class, 'update'])->name('update');
        Route::delete('/{id}', [SubDepartmentController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [SubDepartmentController::class, 'show'])->name('show');
    });
    Route::get('/subdepartments/ajax', [SubDepartmentController::class, 'ajaxSubdepartments'])->name('subdepartments.ajaxSubdepartments');

    Route::prefix('teams')->name('teams.')->group(function () {
        Route::get('/', [TeamController::class, 'index'])->name('index');
        Route::get('/create', [TeamController::class, 'create'])->name('create');
        Route::post('/', [TeamController::class, 'store'])->name('store');
        Route::get('/ajax-teams', [TeamController::class, 'ajaxTeams'])->name('ajaxTeams');
        Route::get('/get-teams', [TeamController::class, 'getTeams'])->name('getTeams');
        Route::get('/{team}/edit', [TeamController::class, 'edit'])->name('edit');
        Route::put('/{team}', [TeamController::class, 'update'])->name('update');
        Route::delete('/{team}', [TeamController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [TeamController::class, 'show'])->name('show');
    });

    Route::prefix('target')->name('target.')->group(function () {
        Route::get('/', [TeamTargetController::class, 'target'])->name('index');
        Route::post('/store', [TeamTargetController::class, 'store'])->name('store');
        Route::get('/{target_id}/edit', [TeamTargetController::class, 'edit'])->name('edit');
        Route::post('/update', [TeamTargetController::class, 'update'])->name('update');
        Route::post('/save-all-targets', [TeamTargetController::class, 'saveAllTargets'])->name('save.all.targets');
        Route::get('/get-target-teams', [TeamTargetController::class, 'getTargetTeams'])->name('get.target.teams');
        Route::get('/get-dep-teams', [TeamTargetController::class, 'getDepTeams'])->name('get.dep.teams');
        Route::get('/show', [TeamTargetController::class, 'show'])->name('show');
        Route::get('/get-team-progress', [TeamTargetController::class, 'getTeamProgress'])->name('get.team.progress');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/ajax-ssers', [UserController::class, 'ajaxUsers'])->name('ajaxUsers');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::delete('/{id}/profile-picture', [UserController::class, 'deleteProfilePicture'])->name('profile.picture.delete');
        Route::post('/update-status', [UserController::class, 'updateStatus'])->name('status');
        Route::post('/{id}/update-departments', [UserController::class, 'updateUserDepartments'])->name('updateUserDepartments');
        Route::get('/{id}', [UserController::class, 'show']);
    });

    Route::prefix('task-types')->name('task-types.')->group(function () {
        Route::get('/', [TaskTypeController::class, 'index'])->name('index');
        Route::get('/create', [TaskTypeController::class, 'create'])->name('create');
        Route::post('/', [TaskTypeController::class, 'store'])->name('store');
        Route::get('/{taskType}/edit', [TaskTypeController::class, 'edit'])->name('edit');
        Route::put('/{taskType}', [TaskTypeController::class, 'update'])->name('update');
        Route::delete('/{taskType}', [TaskTypeController::class, 'destroy'])->name('destroy');
        Route::get('/{taskType}', [TaskTypeController::class, 'show'])->name('show');
    });

    Route::prefix('task-status')->name('task-status.')->group(function () {
        Route::get('/', [TaskStatusController::class, 'index'])->name('index');
        Route::get('/create', [TaskStatusController::class, 'create'])->name('create');
        Route::post('/', [TaskStatusController::class, 'store'])->name('store');
        Route::get('/{taskStatus}/edit', [TaskStatusController::class, 'edit'])->name('edit');
        Route::put('/{taskStatus}', [TaskStatusController::class, 'update'])->name('update');
        Route::delete('/{taskStatus}', [TaskStatusController::class, 'destroy'])->name('destroy');
        Route::get('/{taskStatus}', [TaskStatusController::class, 'show'])->name('show');
    });

    Route::prefix('task-stages')->name('task-stages.')->group(function () {
        Route::get('/', [TaskStageController::class, 'index'])->name('index');
        Route::get('/create', [TaskStageController::class, 'create'])->name('create');
        Route::post('/', [TaskStageController::class, 'store'])->name('store');
        Route::get('/{taskStages}/edit', [TaskStageController::class, 'edit'])->name('edit');
        Route::put('/{taskStages}', [TaskStageController::class, 'update'])->name('update');
        Route::delete('/{taskStages}', [TaskStageController::class, 'destroy'])->name('destroy');
        Route::get('/{taskStages}', [TaskStageController::class, 'show'])->name('show');
    });

    Route::prefix('task-priorities')->name('task-priorities.')->group(function () {
        Route::get('/', [TaskPriorityController::class, 'index'])->name('index');
        Route::get('/create', [TaskPriorityController::class, 'create'])->name('create');
        Route::post('/', [TaskPriorityController::class, 'store'])->name('store');
        Route::get('/{taskPriorities}/edit', [TaskPriorityController::class, 'edit'])->name('edit');
        Route::put('/{taskPriorities}', [TaskPriorityController::class, 'update'])->name('update');
        Route::delete('/{taskPriorities}', [TaskPriorityController::class, 'destroy'])->name('destroy');
        Route::get('/{taskPriorities}', [TaskPriorityController::class, 'show'])->name('show');
    });
    Route::prefix('external-status')->name('external-status.')->group(function () {
        Route::get('/', [ExternalStatusController::class, 'index'])->name('index');
        Route::get('/create', [ExternalStatusController::class, 'create'])->name('create');
        Route::post('/', [ExternalStatusController::class, 'store'])->name('store');
        Route::get('/{externalStatus}/edit', [ExternalStatusController::class, 'edit'])->name('edit');
        Route::put('/{externalStatus}', [ExternalStatusController::class, 'update'])->name('update');
        Route::delete('/{externalStatus}', [ExternalStatusController::class, 'destroy'])->name('destroy');
        Route::get('/{externalStatus}', [ExternalStatusController::class, 'show'])->name('show');
    });
    Route::prefix('user-status')->name('user-status.')->group(function () {
        Route::get('/', [UserStatusController::class, 'index'])->name('index');
        Route::get('/create', [UserStatusController::class, 'create'])->name('create');
        Route::post('/', [UserStatusController::class, 'store'])->name('store');
        Route::get('/{userStatus}/edit', [UserStatusController::class, 'edit'])->name('edit');
        Route::put('/{userStatus}', [UserStatusController::class, 'update'])->name('update');
        Route::delete('/{userStatus}', [UserStatusController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [UserStatusController::class, 'show'])->name('user-status.show');
    });
    Route::prefix('user-logs')->name('user-logs.')->group(function () {
        Route::get('/', [UserLogController::class, 'index'])->name('index');
        Route::get('/{logId}/details', [UserLogController::class, 'show'])->name('show');
        Route::post('/store/{userId}/{userStatusId}', [UserLogController::class, 'store'])->name('store');
        Route::delete('/{logId}', [UserLogController::class, 'destroy'])->name('destroy');
    });

    Route::get('/my-tasks', [UserTaskController::class, 'myTasks'])->name('myTasks');
    Route::get('/my-tasks?tl=true', [UserTaskController::class, 'myTasks'])->name('ltTasks');
    Route::get('/my-tasks?csr=true', [UserTaskController::class, 'myTasks'])->name('csrTasks');
    Route::get('/my-tasks/{id}/details', [UserTaskController::class, 'taskDetails'])->name('taskDetails');
    Route::post('/my-tasks/{id}/update-status', [UserTaskController::class, 'updateTaskStatus'])->name('updateStatus');

    //Task Notification Routes
    Route::get('/get-unread-notification', [NotificationController::class, 'getUnreadNotification'])->name('get.unread.notification');
    Route::get('/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark.as.read');

    //Route::get('/projects/{id}/details', [UserTaskController::class, 'getProjectDetails']);

    Route::get('/members', [MemberController::class, 'index'])->name('members');
    Route::get('/get-teams', [MemberController::class, 'getTeams'])->name('getTeams');
    Route::get('/get-member-stats', [MemberController::class, 'getMemberStats'])->name('getMemberStats');
    Route::post('/re-assign-task', [TaskController::class, 're_assign_task'])->name('re-assign-task');

    Route::get('/task-types-search', [TaskTypeController::class, 'search'])->name('task-types-search');
    Route::get('/get-avg-time', [TaskTypeController::class, 'getAvgTime'])->name('get.avg.time');
    Route::get('/project-tracker', [ProjectTrackerController::class, 'index'])->name('project-tracker.index');

    Route::get('/get-time-progress', [DashboardController::class, 'getTimeProgress'])->name('get.time.progress');
    Route::get('/get-task-progress', [DashboardController::class, 'getTaskProgress'])->name('get.task.progress');
    Route::get('/get-today-work-time', [DashboardController::class, 'getOnlineToOfflineTime'])->name('get.today.work.time');



    Route::get('/chat', [App\Http\Controllers\vendor\Chatify\MessagesController::class, 'index'])->name('chat');
    Route::get('/get-all-users', [App\Http\Controllers\vendor\Chatify\MessagesController::class, 'getAllUsers'])->name('getAllUsers');
    Route::get('/red-flag', [ReportingController::class, 'red_flag'])->name('red-flag');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportingController::class, 'index'])->name('index');
        Route::get('/daily-reports', [ReportingController::class, 'index'])->name('daily-reports');
        Route::get('/get-team', [ReportingController::class, 'getTeam'])->name('get.team');
        Route::get('/get-member', [ReportingController::class, 'getMember'])->name('get.member');
        Route::get('/get-project', [ReportingController::class, 'getProject'])->name('get.project');
        Route::get('/see-my-report', [ReportingController::class, 'seeMyReport'])->name('see.my.report');
        Route::get('/my-progress', [ReportingController::class, 'myProgress'])->name('my.progress');
    });

    Route::get('/unassigned-tasks', [DashboardController::class, 'getUnassignedTasks']);
    Route::get('/delayed-tasks', [DashboardController::class, 'getDelayedTasks']);
    Route::get('/projects-without-tasks', [DashboardController::class, 'getProjectsWithoutTasks']);
    Route::get('/pending-payments', [DashboardController::class, 'getPendingPayments']);
});

Route::any('clear-all', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear'); // Added config clear for consistency
    Artisan::call('optimize:clear');
    return redirect()->to('dashboard')->with('success', __('Cache cleared successfully.'));
})->name('clear.all');


require __DIR__ . '/auth.php';
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/register', function () {
    return redirect('/login')->with('error', 'New registrations are currently disabled.');
})->name('register');
