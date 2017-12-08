<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index', [
            'users' => User::get(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $webid = $user->integrations()->where('service_name', '=', 'webid')->first();
        $github = $user->integrations()->where('service_name', '=', 'github')->first();

        return view('users.show', [
            'user' => $user,
            'webid' => $webid,
            'github' => $github,
        ]);
    }
}