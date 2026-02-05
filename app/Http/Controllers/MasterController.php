<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Pda;

class MasterController extends Controller
{
    /* =========================
     * MASTER MENU
     * ========================= */
    public function index()
    {
        return view('master.index');
    }

    /* =========================
     * MASTER EMPLOYEE (LIST + SEARCH)
     * ========================= */
    public function employees(Request $request)
    {
        $query = Employee::query();

        // 🔍 SEARCH NIK / NAMA
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('nik', 'like', "%{$keyword}%")
                  ->orWhere('nama', 'like', "%{$keyword}%");
            });
        }

        $employees = $query
            ->orderBy('nik')
            ->paginate(10)
            ->withQueryString();

        return view('master.employees', compact('employees'));
    }

    /* =========================
     * FORM TAMBAH EMPLOYEE
     * ========================= */
    public function createEmployee()
    {
        return view('master.employee-create');
    }

    /* =========================
     * SIMPAN EMPLOYEE
     * ========================= */
    public function storeEmployee(Request $request)
    {
        $request->validate([
            'nik'  => 'required|string|max:50|unique:employees,nik',
            'nama' => 'required|string|max:100',
        ]);

        Employee::create([
            'nik'    => $request->nik,
            'nama'   => $request->nama,
            'status' => 'aktif',
        ]);

        return redirect()
            ->route('master.employees')
            ->with('success', 'Employee berhasil ditambahkan');
    }

    /* =========================
     * FORM EDIT EMPLOYEE
     * ========================= */
    public function editEmployee(Employee $employee)
    {
        return view('master.employee-edit', compact('employee'));
    }

    /* =========================
     * UPDATE EMPLOYEE
     * ========================= */
    public function updateEmployee(Request $request, Employee $employee)
    {
        $request->validate([
            'nik'    => 'required|string|max:50|unique:employees,nik,' . $employee->id,
            'nama'   => 'required|string|max:100',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $employee->update([
            'nik'    => $request->nik,
            'nama'   => $request->nama,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('master.employees')
            ->with('success', 'Data employee berhasil diperbarui');
    }

    /* =========================
     * MASTER PDA (LIST + SEARCH)
     * ========================= */
    public function pdas(Request $request)
    {
        $query = Pda::query();

        // 🔍 SEARCH PDA NO
        if ($request->filled('keyword')) {
            $query->where('pda_no', 'like', "%{$request->keyword}%");
        }

        $pdas = $query
            ->orderBy('pda_no')
            ->paginate(10)
            ->withQueryString();

        return view('master.pdas', compact('pdas'));
    }

    /* =========================
     * FORM TAMBAH PDA
     * ========================= */
    public function createPda()
    {
        return view('master.pda-create');
    }

    /* =========================
     * SIMPAN PDA
     * ========================= */
    public function storePda(Request $request)
    {
        $request->validate([
            'pda_no' => 'required|string|max:50|unique:pdas,pda_no',
        ]);

        Pda::create([
            'pda_no' => $request->pda_no,
            'status' => 'available', // default bisa dipinjam
        ]);

        return redirect()
            ->route('master.pdas')
            ->with('success', 'PDA berhasil ditambahkan');
    }

    /* =========================
     * FORM EDIT PDA
     * ========================= */
    public function editPda(Pda $pda)
    {
        return view('master.pda-edit', compact('pda'));
    }

    /* =========================
     * UPDATE PDA
     * ========================= */
    public function updatePda(Request $request, Pda $pda)
    {
        $request->validate([
            'pda_no' => 'required|string|max:50|unique:pdas,pda_no,' . $pda->id,
            'status' => 'required|in:available,borrowed,nonaktif',
        ]);

        $pda->update([
            'pda_no' => $request->pda_no,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('master.pdas')
            ->with('success', 'Data PDA berhasil diperbarui');
    }
}
