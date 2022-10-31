<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Books;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $dados = Books::all();
        return response()->json($dados, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $book = Books::create($request->all());   
        return response()->json($book, 201);    
    }

    public function show($id)
    {
        $book = Books::find($id);
        return response()->json($book, 200);
    }

    public function update($id, Request $request)
    {
        $book = Books::find($id);
        $book->update($request->all());
        return response()->json($book, 200);
    }

    public function destroy($id)
    {
        $book = Books::find($id);
        $book->delete();
        return response(status:204);
    }
}
