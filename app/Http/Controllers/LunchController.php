<?php

namespace App\Http\Controllers;

use App\Models\MoneyRecords;
use App\Models\Record;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LunchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        // 扣款操作畫面
        $users = User::all()->sortBy('deposit')->reverse();
        return view('lunch.index', ['users' => $users]);
    }

    public function record()
    {
        // 扣款紀錄畫面
        $records = MoneyRecords::all()->sortBy('created_at')->reverse();
//        dd($records);
        return view('lunch.record', ['records' => $records]);
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
        foreach ($request->user_cost as $key => $cost) {
            if (is_numeric($cost) && $cost > 0 && $cost < 900000) {
                $user = User::find($request->user_id[$key]);
                $user->reduceMoney($cost, $request->user_remark[$key], Auth::id());
            }
        }

        if (isset($request->user_save)) {
            foreach ($request->user_save as $key => $save) {
                if (is_numeric($save) && $save > 0 && $save < 900000) {
                    $user = User::find($request->user_id[$key]);
                    $user->addMoneyAndRecord($save, $request->user_remark[$key], Auth::id());
                }
            }
        }
//        return response('200');

        return redirect()->route('record');
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
