<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Time;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class TimerController extends Controller
{
    const MESSAGE_STARTED = 'Timer started';
    const MESSAGE_STOPPED = 'Timer stopped';
    const MESSAGE_REJECTED = 'Required at least 60 seconds, time canceled';

    /**
     * Start a new time
     * @param Request $request
     * @return void
     * @throws AuthorizationException
     */
    public function start(Request $request): void
    {
        /** @var Task $task */
        $task = Task::query()->findOrFail($request->get('task'));

        $this->authorize('view', $task->project);

        /** @var Time|null $time */
        $time = loggedUser()->times()->pending()->first();
        $time?->stop();

        /** @var Time $time */
        $time = loggedUser()->times()->create([
            'project_id' => $task->project_id,
            'task_id' => $task->id,
            'start_at' => now()
        ]);

        loggedUser()->update([
            'active_time_id' => $time->id
        ]);

        $this->success(self::MESSAGE_STARTED);
    }

    /**
     * Stop the current time
     * @return void
     */
    public function stop(): void
    {
        /** @var Time $time */
        $time = loggedUser()->times()->pending()->firstOrFail();

        if ($time->stop()) {
            $this->success(self::MESSAGE_STOPPED);
        } else {
            $this->error(self::MESSAGE_REJECTED);
        }
    }
}
