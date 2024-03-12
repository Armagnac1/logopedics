<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller

{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Index', [
            'users' => User::orderBy('id', 'asc')
                ->with('roles')
                ->paginate(20)
                ->withQueryString()
        ]);
    }

    public function loginAs(User $user)
    {
        session()->put(['impersonator' => Auth::id()]);
        session()->put(['impersonate' => $user->id]);
        return redirect()->route('profile.show');
    }
    public function impersonateBack()
    {
        session()->forget('impersonate');
        return redirect()->route('profile.show');
    }
}
