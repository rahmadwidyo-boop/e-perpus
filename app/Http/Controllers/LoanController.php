<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        // Update status terlambat otomatis
        Loan::where('status', 'dipinjam')
            ->where('due_date', '<', Carbon::today())
            ->update(['status' => 'terlambat']);

        $query = Loan::with(['student', 'book']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhereHas('student', fn($s) => $s->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('book', fn($b) => $b->where('title', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $loans = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $students = Student::orderBy('name')->get();
        $books    = Book::where('stock', '>', 0)->orderBy('title')->get();
        return view('loans.create', compact('students', 'books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'book_id'    => 'required|exists:books,id',
            'loan_date'  => 'required|date',
            'due_date'   => 'required|date|after_or_equal:loan_date',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->stock < 1) {
            return back()->with('error', 'Stok buku tidak tersedia.')->withInput();
        }

        // Generate kode transaksi
        $validated['code']   = 'TRX-' . strtoupper(uniqid());
        $validated['status'] = 'dipinjam';

        Loan::create($validated);

        // Kurangi stok buku
        $book->decrement('stock');

        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function show(Loan $loan)
    {
        $loan->load(['student', 'book']);
        return view('loans.show', compact('loan'));
    }

    public function returnBook(Loan $loan)
    {
        if ($loan->status === 'dikembalikan') {
            return back()->with('error', 'Buku sudah dikembalikan sebelumnya.');
        }

        $returnDate = Carbon::today();
        $lateDays   = 0;
        $fine       = 0;

        if ($returnDate->gt($loan->due_date)) {
            $lateDays = $returnDate->diffInDays($loan->due_date);
            $fine     = $lateDays * 1000; // Rp 1.000 per hari
        }

        $loan->update([
            'return_date' => $returnDate,
            'status'      => 'dikembalikan',
            'fine'        => $fine,
        ]);

        // Tambah stok buku kembali
        $loan->book->increment('stock');

        $message = 'Buku berhasil dikembalikan.';
        if ($fine > 0) {
            $message .= " Denda keterlambatan: Rp " . number_format($fine, 0, ',', '.');
        }

        return redirect()->route('loans.index')->with('success', $message);
    }

    public function destroy(Loan $loan)
    {
        if ($loan->status !== 'dikembalikan') {
            return back()->with('error', 'Transaksi aktif tidak dapat dihapus.');
        }

        $loan->delete();

        return redirect()->route('loans.index')->with('success', 'Data transaksi berhasil dihapus.');
    }
}
