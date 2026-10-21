<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return response()->json(Book::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'integer', 'min:0'],
        ]);

        $book = Book::create($validated);

        return response()->json($book, 201);
    }

    public function show(string $id)
    {
        return response()->json(Book::findOrFail($id), 200);
    }

    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'nullable', 'integer', 'min:0'],
        ]);

        $book->update($validated);

        return response()->json($book, 200);
    }

    public function destroy(string $id)
    {
        Book::findOrFail($id)->delete();

        return response()->noContent();
    }
}
