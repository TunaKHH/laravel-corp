<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     */
    public function index()
    {
        //
        $restaurants = Restaurant::all();
        return view('restaurant.index', ['restaurants' => $restaurants]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
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
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        // 驗證
        $messages = [
            'name.required' => '未填寫餐廳名稱',
            'name.unique' => '重複的餐廳名稱',
            'name.max' => '字數不得超過255',
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:restaurants|max:255',
        ], $messages);

        // 驗證失敗回傳錯誤訊息
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        // 新增餐廳
        Restaurant::create($request->all());
        return redirect()->route('restaurant.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Restaurant $restaurant
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     */
    public function show(Restaurant $restaurant)
    {
        return view('restaurant.show', ['restaurant' => $restaurant]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Restaurant $restaurant
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     */
    public function edit(Restaurant $restaurant)
    {
        return view('restaurant.edit', ['restaurant' => $restaurant]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);
        $restaurant->update($request->all());
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
