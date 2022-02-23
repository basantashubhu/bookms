<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Repo\BookRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class BookApiController extends Controller
{
    public function allBooks(BookRepo $bookRepo): JsonResponse
    {
        return response()->json([
            'data' => $bookRepo->getAllWithUser()
        ]);
    }

    public function downloadPdf($file, BookRepo $bookRepo)
    {
        try {
            $id = Crypt::decryptString($file);
            $book = $bookRepo->findOrFail($id);
            $filename = storage_path("uploads/{$book->filename}");
            throw_if(!file_exists($filename), new \Exception('Pdf version of the book is not available.'));
            return response()->download($filename);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage(), ['ctx' => 'download book pdf', 'user_id' => auth()->id()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
