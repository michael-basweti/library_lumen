<?php

namespace App\Http\Controllers;

use App\Author;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AuthorController extends Controller
{

/**
     * @OA\GET(
     *     path="/api/v1/authors",
     *     operationId="/sample/category/things",
     *     tags={"all authors"},
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
    public function showAllAuthors(Request $request)
    {
        $authors = Author::with('book');
        if ($request->has('name')) {
            $name = strtolower(htmlentities($request->name));
            $authors->whereRaw('LOWER(name) like (?)', "%{$name}%");
        }
        if ($request->has('offset') && $request->has('limit')) {
            $authors->offset($request->offset)->limit($request->limit);
        }
        $authors = $authors->get();
        return response()->json($authors, 200);
    }

    /**
     * @OA\GET(
     *     path="/api/v1/authors/{id}",
     *     operationId="/sample/category/things",
     *     tags={"get one author"},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="author id",
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
    public function showOneAuthor($id)
    {
        try {
            $author= Author::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => "error", "message" => "Author not found"], 404);
        }
        return response()->json($author, 200);
    }

    /**
     * @OA\GET(
     *     path="/api/v1/authors/{id}/books",
     *     operationId="/sample/category/things",
     *     tags={"get one author's books"},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="author id",
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
    public function showOneAuthorWithBooks($id)
    {
        try {
            $author= Author::with('book')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => "error", "message" => "Author not found"], 404);
        }
        return response()->json($author, 200);
    }

    /**
     * @OA\POST(
     *     path="/api/v1/authors",
     *     operationId="/sample/category/things",
     *     tags={"add author"},
     *security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Tname",
     *         required=true,
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
     *         name="dob",
     *         in="query",
     *         description="date of birth",
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
            'email'=>'required|email|max:255|unique:authors',
            'dob'=>'required'
        ]);

        $author = new Author;
        $author->name= $request->name;
        $author->dob= $request->dob;
        $author->email=$request->email;

        $author->save();

        return response()->json($author, 201);
    }


    /**
     * @OA\PUT(
     *     path="/api/v1/authors/{id}",
     *     operationId="/sample/category/things",
     *     tags={"edit an author"},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The category parameter in path",
     *         required=false,
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
     *         name="dob",
     *         in="query",
     *         description="date of birth",
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
      try {
        $author= Author::findOrFail($id);
    } catch (ModelNotFoundException $e) {
        return response()->json(["status" => "error", "message" => "Author not found"], 404);
    }
        $author->update($request->all());

        return response()->json($author, 200);
    }

    /**
     * @OA\DELETE(
     *     path="/api/v1/authors/{id}",
     *     operationId="/sample/category/things",
     *     tags={"delete a book"},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="author id",
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
    public function delete($id)
    {
        try {
            Author::findOrFail($id)->delete();;
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => "error", "message" => "Author not found"], 404);
        }
        return response('Deleted Successfully', 204);
    }
}
