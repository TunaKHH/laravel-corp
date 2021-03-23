<?php

namespace App\Http\Controllers;

use App\Models\RestaurantMeal;
use App\Models\TaskOrder;
use App\Models\User;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class TaskOrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $restaurant_id = $request->restaurant_id;
        $user_name = $request->user_name;
        $meal_name = $request->meal_name;
        $meal_price = $request->meal_price;

        // 檢查有沒有這個使用者
        $user = User::where('name', $user_name)->first();
        if( $user === null ){// 沒有這個使用者就退回
            return back()->with('no_user','查無此使用者');
        }

        $restaurantMeal = RestaurantMeal::where('name',$meal_name)
                                            ->where('restaurant_id',$restaurant_id)
                                            ->first();

        if( $restaurantMeal === null ){// 菜單中沒有就新增
            $restaurantMeal = RestaurantMeal::create([
                'name' => $meal_name,
                'price' => $meal_price,
                'restaurant_id' => $restaurant_id,
            ]);
        }

        // 寫入這次任務點餐

        $taskOrder = new TaskOrder;
        $taskOrder->meal_name = $meal_name;
        $taskOrder->meal_price = $meal_price;
        $taskOrder->task_id = $request->task_id;
        $taskOrder->user_id = $user->id;
        $taskOrder->qty = $request->qty;
        $taskOrder->remark = $request->remark;
        $taskOrder->save();

        return back()->with('success','點餐成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaskOrder  $taskOrder
     * @return \Illuminate\Http\Response
     */
    public function show(TaskOrder $taskOrder)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskOrder  $taskOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskOrder $taskOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskOrder  $taskOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskOrder $taskOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskOrder  $taskOrder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TaskOrder $taskOrder)
    {
        //
        $taskOrder->delete();
        return redirect()->back();
    }
}
