<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $tasks = Task::all()->sortBy('created_at')->reverse();
//        dd($tasks->restaurants);
        $restaurants = Restaurant::all();
        return view('task.index', ['tasks'=>$tasks, 'restaurants'=> $restaurants]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {;
        Task::create($request->all());
        return redirect()->route('task.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Task $task)
    {
//        dd($task->taskOrder());
        $users = User::all();
        return view('task.show', ['task'=>$task, 'users'=>$users]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
        Task::destroy($id);
        return  redirect()->route('task.index');

    }

    public function lock(Request $request)
    {
        $id = $request->id;
        $task = Task::find($id);
        $task->is_open = 2;
        $task->save();

        return  redirect()->route('task.index');
    }

    public function unlock(Request $request)
    {
        $id = $request->id;
        $task = Task::find($id);
        $task->is_open = 1;
        $task->save();

        return  redirect()->route('task.index');
    }


}
