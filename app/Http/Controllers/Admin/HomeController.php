<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;

use App\Models\portfolioManager;
use Illuminate\Contracts\Database\Eloquent\Builder;

class HomeController
{
    public function index()
    {
        $users = User::whereHas('roles', function (Builder $query) {
            $query->where('id', '=', 2);
        })->count();
        $portfolio_manager = portfolioManager::pluck('id')->count();
        return view('home', compact('users','portfolio_manager'));
    }
}
