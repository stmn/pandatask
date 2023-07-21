<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;

class UsersController extends AdminController
{
    public function index(Request $request): Response
    {
        /** @var Collection $items */
        $items = User::query()
            ->search($request->get('search'))
            ->sortByString($request->get('sort'))
            ->with('groups')
            ->paginate($this->perPage());

        $items->append('can');

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

    /**
     * @throws ValidationException
     */
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

    public function impersonate(User $user): RedirectResponse
    {
        session()->put('impersonated', loggedUser()->id);

        auth()->login($user);

        $this->success('You are now impersonating ' . $user->full_name);

        return to_route('dashboard');
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

    /**
     * @throws ValidationException
     */
    protected function save(User $user = new User()): void
    {
        $this->validate(request(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => (!$user->exists ? 'required' : 'nullable') . '|min:6',
            'confirm_password' => (!$user->exists ? 'required' : 'nullable') . '|same:password',
            'groups' => 'nullable|array',
        ]);

        $user->fill(request()->except('password'));

        if (request()->filled('password')) {
            $user->password = Hash::make(request()->password);
        }

        $user->groups()->sync(request()->ids('groups'));

        $user->save();
    }
}
