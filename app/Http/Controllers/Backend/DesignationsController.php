<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DesignationsController extends Controller
{
    public function index()
    {

        $data = Designation::orderBy('id', 'desc')->get();

        return Inertia::render('Admin/Designation/Index', compact('data'));
    }

    public function save(Request $request)
    {

        $request->validate([
            'name' => 'required',
        ]);

        $data = [

            'name' => $request->name,
            'status' => $request->status ? 1 : 0,
            'settings' => $request->settings,
        ];

        if ($request->id) {

            $designation = Designation::findOrFail($request->id);
            $data['updated_by'] = auth()->id();
            $designation->update($data);

            return redirect()->back()->with('message', 'Designation updated Successfully');

        }

        $data['created_by'] = auth()->id();
        Designation::create($data);

        return redirect()->back()->with('message', 'Designation saved Successfully');

    }

    public function delete($id)
    {

        $designation = Designation::findOrFail($id);

        $designation->update([
            'deleted_by' => auth()->id(),
            'deleted_at' => now(),
        ]);

        return redirect()->back()->with('message', 'Designation deleted Successfully');

    }
}
