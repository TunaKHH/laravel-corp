<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class Lunch extends Component
{
    public function render()
    {
        $users = User::all()->sortBy('deposit')->reverse();
        return view('livewire.lunch',['users'=>$users]);
    }



    public function submit()
    {
        $users = User::all()->sortBy('deposit')->reverse();
        dd($users);
//        return view('livewire.lunch',['users'=>$users]);
    }


}
