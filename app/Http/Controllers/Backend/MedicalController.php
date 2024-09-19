<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Medical;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MedicalController extends Controller
{
    public function index()
    {

        $data = Medical::orderBy('id', 'desc')->get();

        return Inertia::render('Admin/Medical/Index', compact('data'));
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

            $medical = Medical::findOrFail($request->id);
            $data['updated_by'] = auth()->id();
            $medical->update($data);

            return redirect()->back()->with('message', 'Medical updated Successfully');

        }

        $data['created_by'] = auth()->id();
        Medical::create($data);

        return redirect()->back()->with('message', 'Medical saved Successfully');

    }

    public function delete($id)
    {

        $medical = Medical::findOrFail($id);

        $medical->update([
            'deleted_by' => auth()->id(),
            'deleted_at' => now(),
        ]);

        return redirect()->back()->with('message', 'Medical deleted Successfully');

    }
}
