<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\RestaurantMeal;
use App\Models\Task;
use App\Models\TaskOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $restaurants = Restaurant::all();
        return view('task.index', ['tasks' => $tasks, 'restaurants' => $restaurants]);
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
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);
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
        $users = User::all();

        $task_totals = $task->getTaskTotals();

        foreach ($task_totals as $task_total) {
            $sum_money += $task_total->meal_price * $task_total->qty_sum;
        }

        return view('task.show', ['task' => $task, 'users' => $users, 'task_totals' => $task_totals, 'sum_money' => $sum_money]);
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
        return view('task.edit', ['task' => $task, 'task_totals' => $task_totals]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Task $task)
    {
        foreach ($request->meal_id as $key => $meal_id) {
            //改餐廳餐點金額
            $meal = RestaurantMeal::find($meal_id);
            if (isset($meal)) {
                $meal->price = $request->meal_price[$key];
                $meal->save();
            }

            //改這次任務餐點金額
            $meal = TaskOrder::where('task_id', $task->id)->where('meal_id', $meal_id)->first();
            $meal->meal_price = $request->meal_price[$key];
            $meal->save();
        }

        return redirect()->route('task.show', $task->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        //
        Task::destroy($id);
        return redirect()->route('task.index');

    }

    public function lock(Request $request)
    {
        $id = $request->id;
        $task = Task::find($id);
        $task->is_open = 2;
        $task->step = 2;
        $task->save();

        return redirect()->route('task.show', $id);
    }

    public function unlock(Request $request)
    {
        $id = $request->id;
        $task = Task::find($id);
        $task->is_open = 1;
        $task->step = 1;
        $task->save();

        return redirect()->route('task.index');
    }

    public function prefinish(Task $task)
    {
        // 結單畫面
        $task->step = 3;
        $task->save();
        return redirect()->route('task.show', $task->id);
    }

    // 結單並自動扣款
    public function finish(Task $task)
    {
        // 自動扣款
        $task_orders = $task->taskOrder()->get();
        foreach ($task_orders as $task_order) {
            $remark = '[餐點自動扣款]' . $task_order->meal_name . $task_order->remark;
            // 找餐點價格
            // 扣玩家的錢
            $task_order->user->reduceMoney($task_order->meal_price, $remark, Auth::id());
        }
        // 關閉任務
        $task->is_open = 0;
        $task->step = 4;
        $task->save();

        return back();
    }

}
