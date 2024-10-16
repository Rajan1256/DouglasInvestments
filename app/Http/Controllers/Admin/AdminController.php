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

class AdminController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::with(['roles', 'manager'])->get();
        
        return view('admin.admins.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('admin_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $managers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.admins.create', compact('managers', 'roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->sync([3]);
        return redirect()->route('admin.admins.index');
    }

    public function edit(User $admin)
    {
        $user = $admin;
        Session::put('aid', $user->id);
        abort_if(Gate::denies('admin_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::pluck('title', 'id');
        $managers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $user->load('roles', 'manager');
        return view('admin.admins.edit', compact('managers', 'roles', 'user'));
    }

    public function update(UpdateUserRequest $request, User $admin)
    {
        Session::forget('aid');
        $user = $admin;
        $user->update($request->all());
        return redirect()->route('admin.admins.index');
    }

    public function show(User $admin)
    {
        abort_if(Gate::denies('admin_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user = $admin;
        $user->load('roles', 'manager');

        return view('admin.admins.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('admin_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
