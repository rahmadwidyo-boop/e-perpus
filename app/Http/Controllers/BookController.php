<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $books      = $query->orderBy('title')->paginate(10)->withQueryString();
        $categories = Book::distinct()->pluck('category')->sort()->values();

        return view('books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Book::distinct()->pluck('category')->sort()->values();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'         => 'required|string|max:50|unique:books,code',
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'author'       => 'required|string|max:255',
            'publisher'    => 'required|string|max:255',
            'publish_year' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'shelf'        => 'required|string|max:100',
            'stock'        => 'required|integer|min:0',
            'cover'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        $book->load('loans.student');
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Book::distinct()->pluck('category')->sort()->values();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'code'         => 'required|string|max:50|unique:books,code,' . $book->id,
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'author'       => 'required|string|max:255',
            'publisher'    => 'required|string|max:255',
            'publish_year' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'shelf'        => 'required|string|max:100',
            'stock'        => 'required|integer|min:0',
            'cover'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            // Hapus cover lama
            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        if ($book->activeLoans()->exists()) {
            return back()->with('error', 'Buku tidak dapat dihapus karena masih dipinjam.');
        }

        if ($book->cover) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}
