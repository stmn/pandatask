<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin;

use App\Models\Status;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class StatusesController extends AdminController
{
    public function index(Request $request): Response
    {
        /** @var Collection $items */
        $items = Status::query()
            ->search($request->get('search'))
            ->sortByString($request->get('sort', 'order_number'))
            ->paginate($this->perPage());

        return Inertia::render('Admin/Statuses/List', [
            'items' => $items,
            'title' => 'Statuses'
        ]);
    }

    public function edit(Status $status): Modal
    {
        return $this->form([
            'status' => $status,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Status $status): void
    {
        $this->save($status);
        $this->afterUpdate();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ValidationException
     */
    public function reorder(Status $status): void
    {
        $this->validate(request(), [
            'position' => 'required|integer',
        ]);

        $status->moveTo(request()->get('position'));
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Status $status): void
    {
        $this->authorize('delete', $status);
        $status->delete();
        $this->afterDestroy();
    }

    protected function form(array $data = [
        'user' => new Status]): Modal
    {
        return Inertia::modal('Admin/Statuses/Form', $data + [])
            ->baseRoute('admin.statuses.index');
    }

    /**
     * @throws ValidationException
     */
    protected function save(Status $status = new Status()): void
    {
        $this->validate(request(), [
            'name' => 'required',
            'color' => 'required',
        ]);

        $status->fill(request()->all());
        $status->save();
    }
}
