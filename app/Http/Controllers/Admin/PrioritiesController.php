<?php

namespace App\Http\Controllers\Admin;

use App\Models\Priority;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;

class PrioritiesController extends AdminController
{
    public function index(Request $request): Response
    {
        $items = Priority::query()
            ->search($request->search)
            ->sortByString($request->sort)
            ->paginate($this->perPage());

        return Inertia::render('Admin/Priorities/List', [
            'items' => $items,
            'title' => 'Priorities'
        ]);
    }

    public function edit(Priority $priority): Modal
    {
        return $this->form([
            'group' => $priority,
        ]);
    }

    public function update(Priority $priority): void
    {
        $this->save($priority);
        $this->afterUpdate();
    }


    public function destroy(Priority $priority): void
    {
        $this->authorize('delete', $priority);
        $priority->delete();
        $this->afterDestroy();
    }

    protected function form(array $data = ['user' => new Priority()]): Modal
    {
        return Inertia::modal('Admin/Priorities/Form', $data + [])
            ->baseRoute('admin.groups.index');
    }

    protected function save(Priority $priority = new Priority()): void
    {
        $this->validate(request(), [
            'name' => 'required',
            'color' => 'required',
        ]);

        $priority->fill(request()->all());
        $priority->save();
    }
}
