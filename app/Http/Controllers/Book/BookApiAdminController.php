<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Repo\BookRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookApiAdminController extends Controller
{
    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_name' => 'required',
            'published_date' => 'sometimes|required|digits:4',
            'authors' => 'sometimes|required',
            'description' => 'sometimes|required',
            'file' => 'sometimes|required|file|mimes:pdf'
        ]);
        return $validator;
    }

    public function store(Request $request, BookRepo $bookRepo): JsonResponse
    {
        $validator = $this->validateRequest($request);
        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
        }
        try {
            $book = $bookRepo->save($request->only(['book_name', 'published_date', 'authors', 'description']));
            if($request->file('file')) {
                $filename = $bookRepo->uploadFile($request->file('file'));
                $bookRepo->save(['filename' => $filename]);
            }
            return response()->json(['message' => 'Book successfully created.', 'data' => $book], 201);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage(), ['ctx' => 'create book', 'user_id' => auth()->id()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function findBook($book, BookRepo $bookRepo): JsonResponse
    {
        return response()->json(['data' => $bookRepo->findOrFail($book)]);
    }

    public function update(Request $request, $book, BookRepo $bookRepo): JsonResponse
    {
        $validator = $this->validateRequest($request);
        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
        }
        try {
            $bookRepo->findOrFail($book);
            $updatedBook = $bookRepo->save($request->only(['book_name', 'published_date', 'authors', 'description']));
            if($request->file('file')) {
                $filename = $bookRepo->uploadFile($request->file('file'));
                $bookRepo->save(['filename' => $filename]);
            }
            return response()->json(['message' => 'Book successfully updated.', 'data' => $updatedBook]);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage(), ['ctx' => 'update book', 'user_id' => auth()->id()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function delete($book, BookRepo $bookRepo): JsonResponse
    {
        try {
            $bookRepo->findOrFail($book);
            $bookRepo->softDelete();
            return response()->json(['message' => 'Book successfully deleted.']);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage(), ['ctx' => 'update delete', 'user_id' => auth()->id()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
