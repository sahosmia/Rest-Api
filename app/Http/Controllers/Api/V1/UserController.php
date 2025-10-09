<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Traits\ApiStatus;

class UserController extends Controller
{
    use ApiStatus;

    public function index()
    {
        return UserResource::collection(User::all())->additional($this->StatusResource());
    }

    public function show(User $user)
    {
        return (new UserResource($user))->additional($this->StatusResource());
    }
}