<?php

namespace App\Http\Controllers;

use App\Models\RestaurantMeal;
use Illuminate\Http\Request;

class RestaurantMealController extends Controller
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RestaurantMeal  $restaurantMeal
     * @return \Illuminate\Http\Response
     */
    public function show(RestaurantMeal $restaurantMeal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RestaurantMeal  $restaurantMeal
     * @return \Illuminate\Http\Response
     */
    public function edit(RestaurantMeal $restaurantMeal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RestaurantMeal  $restaurantMeal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RestaurantMeal $restaurantMeal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RestaurantMeal  $restaurantMeal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(RestaurantMeal $restaurantMeal)
    {

        $restaurantMeal = RestaurantMeal::find($restaurantMeal->id);
//        dd($restaurantMeal);
        $restaurantMeal->delete();
//        RestaurantMeal::destroy($restaurantMeal);
        return redirect()->back();

    }
}
