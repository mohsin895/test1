<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Traits\MediaTraits;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    use MediaTraits;

    public function index()
    {
        $data = Settings::first();

        return Inertia::render('Admin/Settings/Index', compact('data'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'logo_light' => 'nullable|image',
            'logo_dark' => 'nullable|image',
            'fave_icon' => 'nullable|image',
        ]);

        $settings = Settings::first();

        $data = [
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
            'youtube' => $request->youtube,
        ];

        if ($request->hasFile('logo_light')) {
            $data['logo_light'] = $this->uploadSettingsMedia($request, 'logo_light', @$settings->logo_light);
        }
        if ($request->hasFile('logo_dark')) {
            $data['logo_dark'] = $this->uploadSettingsMedia($request, 'logo_dark', @$settings->logo_dark);
        }
        if ($request->hasFile('fave_icon')) {
            $data['fave_icon'] = $this->uploadSettingsMedia($request, 'fave_icon', @$settings->fave_icon);
        }

        if ($settings) {
            $settings->update($data);

            return redirect()->back()->with('message', 'Settings updated successfully!');

        } else {
            Settings::create($data);
        }

        return redirect()->back()->with('message', 'Settings saved successfully!');
    }

    private function uploadSettingsMedia(Request $request, $input_name, $id = null)
    {
        $id = null;
        if ($request->hasFile($input_name)) {
            $options = [
                'dir' => 'settings',
            ];
            if ($id) {
                $options['delete'] = $id;
            }
            $res = $this->saveMedia($request->file($input_name), $options);
            if ($res->status) {
                $id = $res->id;
            }
        }

        return $id;
    }
}
