<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\User;
use Illuminate\Http\Request;

class LunchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all()->sortBy('deposit')->reverse();
        return view('lunch.index',['users'=>$users]);
    }

    public function record()
    {
        //
        $records = Record::all()->sortBy('created_at')->reverse();
        return view('lunch.record',['records'=>$records]);
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
        foreach ( $request->user_cost as $key => $cost ){
            if( is_numeric($cost) && $cost > 0 ){
                $record = new Record;
                $record->user_id = $request->user_id[$key];
                $record->amount = $cost * -1;
                $record->remark = $request->user_remark[$key];
                $record->save();

                $user = User::find($record->user_id);
                $user->deposit = ( $user->deposit - $cost );
                $user->save();
            }
        }

        if( isset($request->user_save) ){
            foreach ( $request->user_save as $key => $save ){
                if( is_numeric($save) && $save > 0 ){
                    $record = new Record;
                    $record->user_id = $request->user_id[$key];
                    $record->amount = $save;
                    $record->remark = $request->user_remark[$key];
                    $record->save();

                    $user = User::find($record->user_id);
                    $user->deposit = ( $user->deposit - $save );
                    $user->save();
                }
            }
        }
        return response('200');

//        return redirect()->route('record');
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
