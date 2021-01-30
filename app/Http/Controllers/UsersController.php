<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Rules\Password;
use App\Models\User;

class UsersController extends Controller
{

    public function index() {
        $this->authorize('manageUsers', User::class);

        $users = User::all();

        return Inertia::render('Users/Index', compact('users'));
    }

    public function create() {
        $this->authorize('manageUsers', User::class);

        return Inertia::render('Users/Create');
    }

    public function store(Request $request) {
        $this->authorize('manageUsers', User::class);

        User::create(request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', new Password, 'confirmed'],
        ]));

        return redirect('/users');
    }
}
