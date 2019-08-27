<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function createSubscription(Request $request){
        $data = $request->post();
        $user = User::updateOrCreate(['_id' => $data['_id']],[
            'endpoint' => $data['subscription']['endpoint'],
            'key' => $data['subscription']['keys']['p256dh'],
            'token' => $data['subscription']['keys']['auth'],
            'active' => 1
        ]);
        return response()->json($user);
    }

    public function deactivateSubscription(Request $request){
        $data = $request->except('_token');
        $user = User::where('_id',$data['_id'])->first();
        $user->update(['active' => 0]);

        return response()->json($user,201);
    }

    public function getUser($userId){
        return response()->json(User::where('_id',$userId)->firstOrFail());
    }
}
