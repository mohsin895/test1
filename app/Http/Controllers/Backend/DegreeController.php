<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DegreeController extends Controller
{
    public function index()
    {
        $data = Degree::orderBy('id', 'desc')->get();

        return Inertia::render('Admin/Degree/Index', compact('data'));
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
            $doc_type = Degree::findOrFail($request->id);
            $data['updated_by'] = auth()->id();
            $doc_type->update($data);

            return back()->with('message', 'Degree updated successfully');
        }

        $data['created_by'] = auth()->id();
        Degree::create($data);

        return back()->with('message', 'Degree created successfully');
    }

    public function delete($id)
    {
        $docType = Degree::findOrFail($id);
        $docType->update([
            'deleted_by' => auth()->id(),
            'deleted_at' => now(),
        ]);

        return back()->with('message', 'Degree deleted successfully');
    }
}
