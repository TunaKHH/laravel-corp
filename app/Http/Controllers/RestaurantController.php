<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\RestaurantPhoto;

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
        //
        Restaurant::create($request->all());
        return redirect()->route('restaurant.index');
    }

    public function uploadImage(Request $request): \Illuminate\Http\RedirectResponse
    {
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
     * @param Restaurant $restaurant
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     */
    public function show(Restaurant $restaurant)
    {
        return view('restaurant.show', ['restaurant'=>$restaurant]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
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
