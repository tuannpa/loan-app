<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    private function getUserData(User $user): array
    {
        // Generate access token.
        $accessToken = $user->createToken('API Token')->accessToken;

        return [ 'accessToken' => $accessToken];
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        // Validate data.
        $validatedData = $request->validate([
            'name' => 'required|max:50',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed',
            'role' => 'required|string'
        ]);

        // Hashing password.
        $validatedData['password'] = bcrypt($request->password);
        $statusCode = Response::HTTP_CREATED;

        try {
            DB::beginTransaction();
            // Create user.
            /** @var User $user */
            $user = User::create($validatedData);
            DB::commit();
            $response = [
                'user' => $user
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response = [
                'error' => $e->getMessage()
            ];
        }

        return response()->json($response, $statusCode);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // Validate login data.
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        // Validate user.
        if (!auth()->attempt($loginData)) {
            return response()->json(['message' => 'Invalid Credentials.'], Response::HTTP_UNAUTHORIZED);
        }

        /** @var User $user */
        $user = auth()->user();

        return response()->json($this->getUserData($user));
    }
}
