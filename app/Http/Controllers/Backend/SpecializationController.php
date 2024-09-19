<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use App\Traits\MediaTraits;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SpecializationController extends Controller
{
    use MediaTraits;

    public function index()
    {
        $data = Specialization::orderBy('id', 'desc')->get();

        return Inertia::render('Admin/Specialization/Index', compact('data'));
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
            $specialization = Specialization::findOrFail($request->id);

            if ($request->hasFile('media')) {
                $opt = [
                    'dir' => 'specialization',
                ];
                if ($specialization) {
                    $opt['delete'] = $specialization->media_id;
                }
                $res = $this->saveMedia($request->file('media'), $opt);
                if ($res->status) {
                    $data['media_id'] = $res->id;

                }
            }

            $data['updated_by'] = auth()->id();
            $specialization->update($data);

            return back()->with('message', 'Specialization updated successfully');
        }

        if ($request->hasFile('media')) {
            $opt = [
                'dir' => 'specialization',
            ];
            $res = $this->saveMedia($request->file('media'), $opt);
            if ($res->status) {
                $data['media_id'] = $res->id;

            }
        }

        $data['created_by'] = auth()->id();
        Specialization::create($data);

        return back()->with('message', 'Specialization created successfully');
    }

    public function delete($id)
    {
        $specialization = Specialization::findOrFail($id);
        $specialization->update([
            'deleted_by' => auth()->id(),
            'deleted_at' => now(),
        ]);

        return back()->with('message', 'Specialization deleted successfully');
    }
}
