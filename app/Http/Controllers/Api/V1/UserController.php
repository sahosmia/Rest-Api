<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(protected UserRepository $userRepository)
    {
        //
    }

    public function index(Request $request)
    {
        try {
            $users = $this->userRepository->paginate(
                $request->query('per_page', 10),
                $request->query('search'),
                $request->query('with')
            );
            return new UserCollection($users);
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to retrieve users.');
        }
    }

    public function show(User $user)
    {
        try {
            return $this->successResponse(new UserResource($user), 'User retrieved successfully.');
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to retrieve user.');
        }
    }

    public function list()
    {
        try {
            $data = $this->userRepository->allForList('name');
            return $this->successResponse($data, 'Users list retrieved successfully.');
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->errorResponse('Failed to retrieve users list.');
        }
    }
}
