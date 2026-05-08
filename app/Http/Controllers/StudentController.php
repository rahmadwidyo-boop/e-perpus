<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    // Daftar kelas yang tersedia
    public static array $classes = [
        'X 1','X 2','X 3','X 4','X 5','X 6','X 7',
        'XI 1','XI 2','XI 3','XI 4','XI 5','XI 6','XI 7',
        'XII 1','XII 2','XII 3','XII 4','XII 5','XII 6','XII 7',
    ];

    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        $students = $query->orderBy('class')->orderBy('name')->paginate(15)->withQueryString();
        $classes  = self::$classes;

        return view('students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = self::$classes;
        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis'     => 'required|string|max:20|unique:students,nis',
            'name'    => 'required|string|max:255',
            'class'   => 'required|in:' . implode(',', self::$classes),
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Student $student)
    {
        $student->load('loans.book');
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classes = self::$classes;
        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nis'     => 'required|string|max:20|unique:students,nis,' . $student->id,
            'name'    => 'required|string|max:255',
            'class'   => 'required|in:' . implode(',', self::$classes),
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $student->update($validated);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        if ($student->activeLoans()->exists()) {
            return back()->with('error', 'Siswa tidak dapat dihapus karena masih memiliki pinjaman aktif.');
        }

        $student->delete();

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    // ── Import Excel ──────────────────────────────────────────────

    public function importForm()
    {
        return view('students.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'Pilih file Excel terlebih dahulu.',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $import = new StudentsImport();
        Excel::import($import, $request->file('file'));

        $errors = $import->errors();

        $message = "Import selesai. {$import->imported} siswa berhasil ditambahkan.";
        if ($import->skipped > 0) {
            $message .= " {$import->skipped} baris dilewati (duplikat NIS, kelas tidak valid, atau data kosong).";
        }

        return redirect()->route('students.index')->with('success', $message);
    }

    public function downloadTemplate()
    {
        // Generate template Excel sederhana menggunakan CSV
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template-import-siswa.csv"',
        ];

        $rows = [
            ['nis', 'nama', 'kelas', 'no_hp', 'alamat'],
            ['2024001', 'Ahmad Fauzi', 'X 1', '081234567890', 'Jl. Merdeka No. 1'],
            ['2024002', 'Siti Rahayu', 'XI 3', '082345678901', 'Jl. Sudirman No. 5'],
            ['2024003', 'Budi Santoso', 'XII 7', '', ''],
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
