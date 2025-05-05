<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        return Inertia::render('Admin/Index', [
            'users' => $this->userRepository->getForIndex(),
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
