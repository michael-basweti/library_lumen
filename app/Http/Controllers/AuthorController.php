<?php

namespace App\Http\Controllers;

use App\Author;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthorController extends Controller
{


    // public function showAllAuthors()
    // {
    //     return response()->json(Author::all());
    // }

    // public function showOneAuthor($id)
    // {
    //     return response()->json(Author::find($id));
    // }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);
        // $Author = Author::create($request->all());

        $author = new Author;
        $author->name= $request->name;
        $author->dob= $request->dob;
        $author->email=$request->email;

        $author->save();

        return response()->json($author, 201);
    }

    // public function update($id, Request $request)
    // {
    //     $Author = Author::findOrFail($id);
    //     $Author->update($request->all());

    //     return response()->json($Author, 200);
    // }

    // public function delete($id)
    // {
    //     Author::findOrFail($id)->delete();
    //     return response('Deleted Successfully', 200);
    // }
}
