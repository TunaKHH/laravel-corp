<?php

namespace App\Http\Controllers;

use App\Models\RestaurantMeal;
use App\Models\TaskOrder;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

/**
 * Class TaskOrderController
 * @package App\Http\Controllers
 */
class TaskOrderController extends Controller
{
    /**
     * 新增點餐 // TODO 這邊要重構改成line點餐可能無餐廳的情形
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $messages = [
            'user_name.required' => '未輸入使用者',
            'meal_name.required' => '未輸入餐點名稱',
            'meal_price.required' => '未輸入餐點金額',
            'qty.required' => '未輸入餐點數量',
            'task_id.required' => '未輸入任務id',
            'qty.integer' => '數量請輸入整數',
            'qty.min' => '數量不得低於1',
            'meal_price.integer' => '餐點金額請輸入整數',
            'meal_price.between' => '餐點金額最低為1元，上限為900000',
            'user_name.max' => '字數不得超過255',
            'remark.max' => '字數不得超過255',
        ];
        $validator = Validator::make($request->all(), [
            'task_id' => 'required',
            'user_name' => 'required|max:255',
            'remark' => 'max:255',
            'meal_name' => 'required',
            'meal_price' => 'required|integer|between:1,9000000',
            'qty' => 'required|integer|min:1',
        ], $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $restaurant_id = $request->restaurant_id ?? null; // 如果沒有餐廳id就是null
        $user_name = $request->user_name;
        $meal_name = $request->meal_name;
        $meal_price = $request->meal_price;
        $qty = $request->qty;
        $task_id = $request->task_id;
        $remark = $request->remark;

        // 檢查有沒有這個使用者
        $user = User::where('name', $user_name)->first();
        if ($user === null) { // 沒有這個使用者就退回
            return back()->with('no_user', '查無此使用者');
        }

        // 檢查有沒有這個餐廳的菜單
        if (isset($restaurant_id)) {
            $restaurantMeal = RestaurantMeal::where('name', $meal_name)
                ->where('restaurant_id', $restaurant_id)
                ->first();
            if ($restaurantMeal === null) { // 菜單中沒有就新增
                $restaurantMeal = RestaurantMeal::create([
                    'name' => $meal_name,
                    'price' => $meal_price,
                    'restaurant_id' => $restaurant_id,
                ]);
            }
        }

        // 寫入這次任務點餐
        $taskOrder = new TaskOrder;
        if (isset($restaurantMeal)) { // 如果有餐廳id就寫入餐廳id
            $taskOrder->meal_id = $restaurantMeal->id;
        }
        $taskOrder->meal_name = $meal_name;
        $taskOrder->meal_price = $meal_price;
        $taskOrder->task_id = $task_id;
        $taskOrder->user_id = $user->id;
        $taskOrder->qty = $qty;
        $taskOrder->remark = $remark;
        $taskOrder->save();

        return back()->with('success', '點餐成功');
    }

    /**
     * Display the specified resource.
     *
     * @param TaskOrder $taskOrder
     * @return Response
     */
    public function show(TaskOrder $taskOrder)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param TaskOrder $taskOrder
     * @return Response
     */
    public function edit(TaskOrder $taskOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param TaskOrder $taskOrder
     * @return RedirectResponse
     */
    public function update(Request $request, TaskOrder $taskOrder)
    {
        $user = User::where('name', $request->user_name)->first();

        if ($user === null) {
            return back()->with('no_user', '查無此使用者');
        }
        $taskOrder->user_id = $user->id;
        $taskOrder->meal_name = $request->meal_name;
        $taskOrder->meal_price = $request->meal_price;
        $taskOrder->qty = $request->qty;
        $taskOrder->remark = $request->remark;
        $taskOrder->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TaskOrder $taskOrder
     * @return RedirectResponse
     */
    public function destroy(TaskOrder $taskOrder)
    {
        //
        $taskOrder->delete();
        return redirect()->back();
    }
}
