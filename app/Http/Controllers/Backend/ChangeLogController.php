<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ChangeLog;
use App\Models\System;
use Illuminate\Http\Request;

class ChangeLogController extends Controller
{
    public function index()
    {
        $changes = ChangeLog::latest()->paginate(10);
        $app_link = System::find(1)->app_link;
        return view('backend.changelog.index', compact('changes', 'app_link'));
    }

    public function store(Request $request)
    {

        ChangeLog::create([
            'changes' => $request->changes,
            'version' => $request->version
        ]);

        return redirect()->back()->with('success', 'Change log updated successfully!');
    }

    public function updateAppLink(Request $request)
    {
        $system = System::find(1);
        $system->app_link = $request->input('app_link');
        $system->save();
        return redirect()->back()->with('success', 'App Link updated successfully!');
    }

    public function appLink()
    {
        $app_link = System::find(1)->app_link;
        return response()->json(['app-link' => $app_link]);
    }
}
