<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        $data = Role::all();
        $app = $this->getAllRoutes(true);
        $modules = $this->getAllRoutes();

        return Inertia::render('Role/Index', compact('data', 'modules', 'app'));
    }

    public function save(Request $request)
    {
        $role = 'required|unique:roles,title';
        if ($request->id) {
            $role = $role.','.$request->id;
        }
        $request->validate([
            'title' => $role,
        ]);

        $exist = Role::find($request->id);

        $data = [
            'title' => $request->title,
            'slug' => str()->slug($request->title),
            'permissions' => $request->permissions,
        ];

        if ($exist) {
            $data['updated_by'] = auth()->id();
            $exist->update($data);
        } else {
            $data['created_by'] = auth()->id();
            Role::create($data);
        }

        return redirect()->back()->with('message', 'Role created successfully');
    }

    public function getAllRoutes($onlyApp = null)
    {
        $routes = Route::getRoutes()->getRoutes();
        $appRoutes = [];
        foreach ($routes as $route) {
            if (str_starts_with($route->getName(), 'app.')) {
                $permission = explode('.', substr($route->getName(), 4));
                if (array_key_exists(1, $permission)) {
                    $appRoutes[$permission[0]][] = substr(implode('.', $permission), strlen($permission[0]) + 1);
                } else {
                    $appRoutes['app'][] = $permission[0];
                }
            }
        }
        if ($onlyApp) {
            return $appRoutes['app'];
        } else {
            unset($appRoutes['app']);

            return $appRoutes;
        }
    }
}
