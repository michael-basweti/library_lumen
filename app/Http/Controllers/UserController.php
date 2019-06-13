<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * @OA\GET(
     *     path="/api/v1/users",
     *     operationId="/sample/category/things",
     *     tags={"Get All Users"},
     *security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response="200",
     *         description="Returns some sample category things",
     *         @OA\JsonContent()
     *     ),
     *
     * )
     */
    public function showAllUsers()
    {
        return response()->json(User::all());
    }

     /**
     * @OA\GET(
     *     path="/api/v1/users/{id}",
     *     operationId="/sample/category/things",
     *     tags={"Get One User"},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="user id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns some sample category things",
     *         @OA\JsonContent()
     *     ),
     *
     * )
     */
    public function showOneUser($id)
    {
        return response()->json(User::find($id));
    }

        /**
     * @OA\PUT(
     *     path="/api/v1/users",
     *     operationId="/sample/category/things",
     *     tags={"Register a user"},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="user id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="email",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="password",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns some sample category things",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'password'=>'required|string|min:6'
        ]);
        // $user = User::create($request->all());

        $user = new User;
        $user->name= $request->name;
        $user->email = $request->email;
        $user->password= Hash::make($request->password);

        $user->save();

        return response()->json($user, 201);
    }

    /**
     * @OA\PUT(
     *     path="/api/v1/users/{id}",
     *     operationId="/sample/category/things",
     *     tags={"Edit a user"},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="user id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="email",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="password",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns some sample category things",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json($User, 200);
    }

     /**
     * @OA\DELETE(
     *     path="/api/v1/users/{id}",
     *     operationId="/sample/category/things",
     *     tags={"delete a user "},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="user id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Returns some sample category things",
     *         @OA\JsonContent()
     *     ),
     *
     * )
     */
    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return response('Deleted Successfully', 204);
    }
}
