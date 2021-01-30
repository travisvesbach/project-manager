<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Rules\Password;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    public function index() {
        $this->authorize('manageUsers', User::class);

        $users = User::orderBy('name')->get();

        return Inertia::render('Users/Index', compact('users'));
    }

    public function create() {
        $this->authorize('manageUsers', User::class);

        return Inertia::render('Users/Edit');
    }

    public function store(Request $request) {
        $this->authorize('manageUsers', User::class);

        User::create(request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', new Password, 'confirmed'],
            'role' => ['required', 'string'],
        ]));

        return redirect('/users');
    }

    public function edit(User $user) {
        $this->authorize('manageUsers', User::class);

        return Inertia::render('Users/Edit', ['editing' => $user]);
    }

    public function update(Request $request, User $user) {
        $this->authorize('manageUsers', User::class);

        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['string', new Password, 'confirmed', 'nullable'],
            'role' => ['required', 'string'],
        ]);

        $user->name = request()->input('name');
        $user->email = request()->input('email');
        $user->role = request()->input('role');
        if($request->filled('password')) {
            $user->password = Hash::make(request()->input('password'));
        }

        $user->save();

        return redirect('/users');
    }

    public function destroy(User $user) {
        $this->authorize('manageUsers', User::class);

        $user->delete();

        return redirect('/users');
    }
}
