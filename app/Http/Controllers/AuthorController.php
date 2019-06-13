<?php

namespace App\Http\Controllers;

use App\Author;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AuthorController extends Controller
{


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

    public function showOneAuthor($id)
    {
        try {
            $author= Author::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => "error", "message" => "Author not found"], 404);
        }
        return response()->json($author, 200);
    }

    public function showOneAuthorWithBooks($id)
    {
        try {
            $author= Author::with('book')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => "error", "message" => "Author not found"], 404);
        }
        return response()->json($author, 200);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email'=>'required|email|max:255|unique:authors',
            'dob'=>'required'
        ]);
        // $Author = Author::create($request->all());

        $author = new Author;
        $author->name= $request->name;
        $author->dob= $request->dob;
        $author->email=$request->email;

        $author->save();

        return response()->json($author, 201);
    }

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
