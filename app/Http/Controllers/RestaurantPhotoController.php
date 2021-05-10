<?php

namespace App\Http\Controllers;

use App\Models\RestaurantPhoto;
use Illuminate\Http\Request;

class RestaurantPhotoController extends Controller
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'restaurant_id' => 'required',
        ]);
        $restaurant_id = $request->get('restaurant_id');

        $imageName = time().'.'.$request->image->extension();

//        $request->image->move(public_path('images'), $imageName);

        /* Store $imageName name in DATABASE from HERE */
        $request->image->storeAs('public/images', $imageName);


        $restaurantPhoto = new RestaurantPhoto;
        $restaurantPhoto->restaurant_id = $restaurant_id;
        $restaurantPhoto->url = '/storage/images/' . $imageName;
        $restaurantPhoto->save();

        return back()->with('success','成功上傳圖片')
            ->with('image',$imageName);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        RestaurantPhoto::destroy($id);
        return  redirect()->back();
    }
}
