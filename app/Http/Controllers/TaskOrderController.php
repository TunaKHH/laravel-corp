<?php

namespace App\Http\Controllers;

use App\Models\RestaurantMeal;
use App\Models\TaskOrder;
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
        //

        $restaurant_id = $request->restaurant_id;
        $name = $request->name;
        $amount = $request->amount;

        $restaurantMeal = RestaurantMeal::where('name',$name)
                                            ->where('restaurant_id',$restaurant_id)
                                            ->first();

        if( isEmpty($restaurantMeal) ){// 菜單中沒有就新增
            $restaurantMeal = RestaurantMeal::create([
                'name' => $name,
                'price' => $amount,
                'restaurant_id' => $restaurant_id,
            ]);
        }

        // 寫入這次任務點餐

        $taskOrder = new TaskOrder;
        $taskOrder->restaurant_meal_id = $restaurantMeal->id;
        $taskOrder->task_id = $request->task_id;
        $taskOrder->user_id = $request->user_id;
        $taskOrder->qty = $request->qty;

        $taskOrder->save();

        return redirect()->back();


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
