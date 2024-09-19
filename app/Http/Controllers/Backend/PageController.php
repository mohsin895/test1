<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Traits\MediaTraits;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    use MediaTraits;

    public function index()
    {
        $data = Page::all();

        return Inertia::render('Admin/Page/Index', compact('data'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'type' => 'required',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => str()->slug($request->title),
            'description' => $request->description,
            'status' => $request->status ? 1 : 0,
            'type' => $request->type,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_image' => $request->meta_image,
            'settings' => $request->settings,

        ];

        if ($request->id) {

            $page = Page::findOrFail($request->id);

            if ($request->hasFile('meta_image')) {
                $path = $this->uploadImage($request, 'meta_image', 'meta_image');
                if ($page->meta_image && $page->meta_image != '' && file_exists($page->meta_image)) {
                    unlink($page->meta_image);
                }
                if ($path) {
                    $data['meta_image'] = $path;
                }
            }

            $page->update($data);

            return back()->with('message', 'Page updated successfully');

        }

        Page::create($data);

        return redirect()->back()->with('message', 'Page created successfully');
    }

    public function delete(Page $page)
    {
        $page->delete();

        return redirect()->back()->with('message', 'Page deleted successfully');
    }
}
