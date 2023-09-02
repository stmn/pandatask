<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Project;

use App\Models\Page;
use App\Models\Project;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class WikiController extends ProjectController
{
    /**
     * @throws AuthorizationException
     */
    public function index(Project $project, ?Page $page = null): Response
    {
        $this->authorize('viewAny', [Page::class, $project]);

        return Inertia::render('Project/Wiki/Wiki', [
            'activeTab' => 'pages',
            'page' => function () use ($project, $page) {
                if ($page) {
                    $page->load('updatedBy');
                    return $page;
                }

                return $project->pages()
                    ->with(['updatedBy'])
                    ->orderByRaw("slug_title = '" . Page::HOME . "' desc")
                    ->orderBy('title')
                    ->first();
            },
            'pages' => fn() => $project->pages()
                ->orderByRaw("slug_title = '" . Page::HOME . "' desc")
                ->orderBy('title')
                ->get(),
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function save(Request $request, Project $project, ?Page $page = null)
    {
        $this->authorize('update', [Page::class, $project]);

        $validated = $request->validate([
            'title' => ['required', Rule::unique('pages')->ignore($page?->id)->where('project_id', $project->id)],
            'content' => [],
        ]);

        $values = collect($validated);

        if ($page?->slug_title === Page::HOME) {
            $values->forget('title');
        } else {
            $values->put('slug_title', Str::slug($values['title']));
        }
        $values->put('updated_by', auth()->id());

        $page = $project->pages()->updateOrCreate([
            'id' => $page?->id,
        ], $values->toArray());

        assert($page instanceof Page);

        $this->success($page->wasRecentlyCreated ? 'Page added' : 'Page updated');

        return redirect()->route('project.pages.show', ['project' => $project, 'page' => $page]);
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(Project $project, Page $page): RedirectResponse
    {
        $this->authorize('delete', [Page::class, $project]);

        $page->delete();

        $homePage = $project->pages()->where('slug_title', 'home')->first();

        $this->success('Page deleted');

        return redirect()->route('project.pages.show', ['project' => $project, 'page' => $homePage]);
    }
}
