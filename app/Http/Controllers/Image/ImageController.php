<?php

namespace App\Http\Controllers\Image;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{   
    public User $user;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);

        $this->user = Auth::user();
    }
}
