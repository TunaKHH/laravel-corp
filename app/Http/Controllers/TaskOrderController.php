<?php

namespace App\Http\Controllers;

use App\Models\RestaurantMeal;
use App\Models\TaskOrder;
use Illuminate\Http\Request;

class TaskOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\Response
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

        if( $restaurantMeal ){// 菜單中沒有就新增
            $restaurantMeal = RestaurantMeal::create([
                'name' => $name,
                'amount' => $amount,
                'restaurant_id' => $restaurant_id,
            ]);
        }

        // 寫入這次任務點餐
        $taskOrder = new TaskOrder;
        $taskOrder->restaurant_id = $restaurant_id;

        $taskOrder->save();

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
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskOrder $taskOrder)
    {
        //
    }
}
