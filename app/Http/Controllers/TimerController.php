<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Time;
use Illuminate\Http\Request;
use Inertia\Response;

class TimerController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function start(Request $request)
    {
        $time = Time::query()->where('author_id', loggedUser()->id)->whereNull('end_at')->first();
        if ($time) {
            if ($time->start_at->diffInMinutes(now()) < 1) {
                $time->delete();

                request()->session()->flash('message', [
                    'type' => 'error',
                    'message' => 'Required at least 60 seconds, time canceled'
                ]);
            } else {
                $time->update([
                    'end_at' => now()
                ]);
            }
        }

        $task = Task::query()->findOrFail($request->get('task'));

        $time = Time::query()->create([
            'author_id' => 1,
            'project_id' => $task->project_id,
            'task_id' => $request->get('task'),
            'start_at' => now()
        ]);

        loggedUser()->update([
            'active_time_id' => $time->id
        ]);

        request()->session()->flash('message', [
            'type' => 'success',
            'message' => 'Timer started'
        ]);
    }

    public function stop()
    {
        $time = Time::query()->where('author_id', 1)->whereNull('end_at')->first();
        if ($time) {
            if ($time->start_at->diffInMinutes(now()) < 1) {
                $time->delete();

                request()->session()->flash('message', [
                    'type' => 'error',
                    'message' => 'Required at least 60 seconds, time canceled'
                ]);
            } else {
                $time->update([
                    'end_at' => now()
                ]);

                request()->session()->flash('message', [
                    'type' => 'success',
                    'message' => 'Timer stopped'
                ]);
            }
        }

        loggedUser()->update([
            'active_time_id' => null
        ]);
    }
}
