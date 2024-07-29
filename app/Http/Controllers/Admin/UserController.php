<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreeRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('user_index'), 403);
        $users = User::with(['roles'])->orderByDesc('id')->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('user_create'), 403);
        $roles = Role::query()->pluck('name', 'id');
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreeRequest $request)
    {
        abort_if(Gate::denies('user_create'), 403);
        $request->validated();
        $user = User::query()->create([
            'name' => $request->input('name', false),
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->roles()->sync($request->roles);

        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $posts = $user->load('posts');

        return view('admin.users.show', compact('user', 'posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        abort_if(Gate::denies('user_update'), 403);
        $roles = Role::query()->pluck('name', 'id');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        abort_if(Gate::denies('user_update'), 403);

        $request->validated();

        $user->update([
            'name' => $request->
            input('name', false),
            'email' => $request->email,
            'password' => bcrypt($request->password),
            $user->roles()->sync($request->roles)
        ]);

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), 403);

        $user->delete();

        return redirect()->route('admin.users.index');
    }
}
