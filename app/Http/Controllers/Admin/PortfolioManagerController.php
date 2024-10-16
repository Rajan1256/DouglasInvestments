<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Gate;
use Session;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PortfolioManagerController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('manager_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::with(['roles', 'manager'])->get();

        return view('admin.managers.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('manager_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $managers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.managers.create', compact('managers', 'roles'));
    }

    public function store(StoreUserRequest $request)
    {
        if ($request->hasFile('profile_images')) {
            $fileName = time() . '.' . $request->profile_images->extension();
            $request->profile_images->move(public_path('profile_pics'), $fileName);
            $request->merge(['profile_image' => $fileName]);
        }
        $user = User::create($request->all());
        $user->roles()->sync([4]);
        return redirect()->route('admin.managers.index');
    }

    public function edit(User $manager)
    {
        $user = $manager;
        Session::put('aid', $user->id);
        abort_if(Gate::denies('manager_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::pluck('title', 'id');
        $managers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $user->load('roles', 'manager');
        return view('admin.managers.edit', compact('managers', 'roles', 'user'));
    }

    public function update(UpdateUserRequest $request, User $manager)
    {
        Session::forget('aid');
        $user = $manager;
        if ($request->hasFile('profile_images')) {
            $fileName = time() . '.' . $request->profile_images->extension();
            $request->profile_images->move(public_path('profile_pics'), $fileName);
            $user->update(['profile_image' => $fileName]);
        }
        $user->update($request->all());
        return redirect()->route('admin.managers.index');
    }

    public function show(User $manager)
    {
        abort_if(Gate::denies('manager_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user = $manager;
        $user->load('roles', 'manager');

        return view('admin.managers.show', compact('user'));
    }

    public function destroy(User $manager)
    {
        $user = $manager;
        abort_if(Gate::denies('manager_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user->delete();
        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        $users = User::find(request('ids'));

        foreach ($users as $user) {
            $user->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
