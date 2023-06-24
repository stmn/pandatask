<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;

class UsersController extends AdminController
{
    public function index(Request $request): Response
    {
        $items = User::query()
            ->search($request->search)
            ->sortByString($request->sort)
            ->with('groups')
            ->get();

        return Inertia::render('Admin/Users/List', [
            'items' => $items,
            'title' => 'Users'
        ]);
    }

    public function edit(User $user): Modal
    {
        $user->load('groups:id');

        return $this->form([
            'user' => $user,
        ]);
    }

    public function update(User $user): void
    {
        $this->save($user);
        $this->afterUpdate();
    }


    public function destroy(User $user): void
    {
        $user->delete();
        $this->afterDestroy();
    }

    protected function form(array $data = [
        'user' => new User(),
    ]): Modal
    {
        return Inertia::modal('Admin/Users/Form', $data + [
            'groups' => Group::all(),
            ])
            ->baseRoute('admin.users.index');
    }

    protected function save(User $user = new User()): void
    {
        $this->validate(request(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'confirm_password' => 'nullable|same:password',
            'groups' => 'nullable|array',
        ]);

        $user->fill(request()->all());

        if (request()->filled('password')) {
            $user->password = Hash::make(request()->password);
        }

        $user->groups()->sync(request()->ids('groups'));

        $user->save();
    }
}
