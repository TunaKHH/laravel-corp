<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
    {
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
        $sum_money = 0;
        $task_id = $task->getAttribute('id');
        $users = User::all();

        $task_totals = $task->getTaskTotals();

        foreach ( $task_totals as $task_total ){
            $sum_money += $task_total->meal_price * $task_total->qty_sum;
        }

        return view('task.show', ['task'=>$task, 'users'=>$users, 'task_totals'=>$task_totals, 'sum_money'=>$sum_money]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
        $task_totals = $task->getTaskTotals();
        return view('task.edit', ['task'=>$task, 'task_totals'=>$task_totals]);
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
        //改餐廳餐點金額、這次任務餐點金額
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

        return  redirect()->route('task.show', $id);
    }

    public function unlock(Request $request)
    {
        $id = $request->id;
        $task = Task::find($id);
        $task->is_open = 1;
        $task->save();

        return  redirect()->route('task.index');
    }

    public function finish(Task $task)
    {
        $task->is_open = 0;
        $task->save();
        return  redirect()->route('task.index');
    }




}
