<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin;

use App\Models\Priority;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PrioritiesController extends AdminController
{
    public function index(Request $request): Response
    {
        /** @var Collection $items */
        $items = Priority::query()
            ->search($request->get('search'))
            ->sortByString($request->get('sort', 'order_number'))
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

    /**
     * @throws ValidationException
     */
    public function update(Priority $priority): void
    {
        $this->save($priority);
        $this->afterUpdate();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ValidationException
     */
    public function reorder(Priority $priority): void
    {
        $this->validate(request(), [
            'position' => 'required|integer',
        ]);

        $priority->moveTo(request()->get('position'));
    }

    /**
     * @throws AuthorizationException
     */
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

    /**
     * @throws ValidationException
     */
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
