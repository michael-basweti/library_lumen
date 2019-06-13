<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BooksController extends Controller
{


    /**
     * @OA\GET(
     *     path="/api/v1/books",
     *     operationId="/sample/category/things",
     *     tags={"Get All Books"},
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
    public function showAllBooks(Request $request)
    {
        $books = Book::with('author');

        if ($request->has('sort_asc')) {
            $books->orderBy('title', 'asc')->get();
        }
        if ($request->has('sort_asc')) {
            $books->orderBy('title', 'desc')->get();
        }
        if ($request->has('search')) {
            $title = strtolower($request->search);
            $books->whereRaw('LOWER(title) like (?)', "%$title%");
        }
        if ($request->has('author')) {
            $books->where(function ($query) use ($request) {
                $query->whereHas('author', function ($query) use ($request) {
                    $name = strtolower(htmlentities($request->author));
                    $query->whereRaw('LOWER(name) like (?)', "%$name%");
                });
            });
        }
        if ($request->has('offset') && $request->has('limit')) {
            $books->offset($request->offset)->limit($request->limit);
        }

        $books=$books->get();
        return response()->json($books, 200);
    }

    /**
     * @OA\GET(
     *     path="/api/v1/books/{id}",
     *     operationId="/sample/category/things",
     *     tags={"Get One Book"},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="book id",
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
    public function showOneBook($id)
    {
        try {
            $book = Book::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => "error", "message" => "book not available"], 404);
        }
        return response()->json($book, 200);
    }

    /**
     * @OA\POST(
     *     path="/api/v1/books",
     *     operationId="/sample/category/things",
     *     tags={"Add a book"},
     *security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="title",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="publisher",
     *         in="query",
     *         description="publisher",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="year_of_publication",
     *         in="query",
     *         description="year of publication",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *@OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="description of the book",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *@OA\Parameter(
     *         name="author_id",
     *         in="query",
     *         description="author id",
     *         required=true,
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
            'title' => 'required|unique:books',
            'publisher'=>'required',
            'year_of_publication'=>'required',
            'description'=>'required',
            'author_id'=>'required|integer'
        ]);
        $books = Book::create($request->all());

        return response()->json($books, 201);
    }


    /**
     * @OA\PUT(
     *     path="/api/v1/books/{id}",
     *     operationId="/sample/category/things",
     *     tags={"Edit a book"},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="book id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="publisher",
     *         in="query",
     *         description="publisher",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="year_of_publication",
     *         in="query",
     *         description="year of publication",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *@OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
      *@OA\Parameter(
     *         name="author_id",
     *         in="query",
     *         description="author id",
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
            $book = Book::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => "error", "message" => "book not available"], 404);
        }
        $book->update($request->all());

        return response()->json($book, 200);
    }

    /**
     * @OA\DELETE(
     *     path="/api/v1/books/{id}",
     *     operationId="/sample/category/things",
     *     tags={"delete a book "},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="book id",
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
        try {
            Book::findOrFail($id)->delete();
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => "error", "message" => "book not available"], 404);
        }
        return response('Deleted Successfully', 204);
    }
}
