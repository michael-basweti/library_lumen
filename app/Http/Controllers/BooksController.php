<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BooksController extends Controller
{


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

    public function showOneBook($id)
    {
        try {
            $book = Book::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => "error", "message" => "book not available"], 404);
        }
        return response()->json($book, 200);
    }

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
