<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;

class GroupsController extends AdminController
{
    public function index(Request $request): Response
    {
        $items = Group::query()
            ->search($request->search)
            ->sortByString($request->sort)
            ->paginate($this->perPage());

        $items->append('can');

        return Inertia::render('Admin/Groups/List', [
            'items' => $items,
            'title' => 'Groups'
        ]);
    }

    public function edit(Group $group): Modal
    {
        return $this->form([
            'group' => $group,
        ]);
    }

    public function update(Group $group): void
    {
        $this->save($group);
        $this->afterUpdate();
    }


    public function destroy(Group $group): void
    {;
        $this->authorize('delete', $group);
        $group->delete();
        $this->afterDestroy();
    }

    protected function form(array $data = [
        'user' => new Group(),
    ]): Modal
    {
        return Inertia::modal('Admin/Groups/Form', $data + [])
            ->baseRoute('admin.groups.index');
    }

    protected function save(Group $group = new Group()): void
    {
        $this->validate(request(), [
            'name' => 'required',
            'description' => 'max:255',
            'color' => 'required',
        ]);

        $group->fill(request()->all());
        $group->save();
    }
}
