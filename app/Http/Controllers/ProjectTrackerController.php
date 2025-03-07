<?php

namespace App\Http\Controllers;

use App\Models\ProjectTracker;
use Illuminate\Http\Request;
use DataTables;

class ProjectTrackerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ProjectTracker::query();

            return DataTables::of($query)
                ->make(true);
        }

        return view('projectTracker.list');
    }
}
