<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Conversation;
class ConversationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_conversation' => 'required|string|max:255',
            'description_conversation' => 'required|string',
            'attachments_conversation.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:2048',
        ]);
        // Create the Source with validated data
        if ($validator->passes()) {
                // Handle file uploads
            $attachmentDetails = [];
            if ($request->hasFile('attachments_conversation')) {
                foreach ($request->file('attachments_conversation') as $file) {
                    $attachmentDetails[] = [
                        'path' => $file->store('attachments-conversation', 'public'),
                        'original_name' => $file->getClientOriginalName(),
                    ];
                }
            }
            // Save conversation details
            $conversation = Conversation::create([
                'name' => $request->input('title_conversation'),
                'description' => $request->input('description_conversation'),
                'attachments' => json_encode($attachmentDetails),
                'author' => Auth::user()->id,
                'task_id'=> $request->input('task_id'),
            ]);

            // Return a JSON response for AJAX
            return response()->json([
                'message' => 'Conversation added successfully!',
                'conversation' => $conversation,
            ], 201);
        } else {
            return response()->json([
                'message' => 'Something went wrong!',
                'errors' => $validator->errors(),
            ], 422);
        }
    }

    public function loadConversations($project, $task)
    {
        $conversations = Conversation::select(
            'conversations.*',
            'users.name as author_name'
        )
        ->join('users', 'users.id', '=', 'conversations.author')
        ->where('conversations.task_id', $task)
        ->latest()
        ->get();

        return view('projects.partials.tasks.conversations.list', compact('conversations'));
    }
}