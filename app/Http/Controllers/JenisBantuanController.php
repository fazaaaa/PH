<?php

namespace App\Http\Controllers;

use App\Models\JenisBantuan;
use App\Models\Penduduk;
use Illuminate\Http\Request;

class JenisBantuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisbantuan = JenisBantuan::with('penduduk')->get();
        return view('jenisbantuan.index', compact('jenisbantuan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisbantuan = JenisBantuan::all();
        return view('jenisbantuan.create', compact('jenisbantuan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bantuan' => 'required',
        ]);

        try {
            JenisBantuan::create($request->all());
            return redirect()->route('jenisbantuan.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jenisbantuan = JenisBantuan::find($id);
        $penduduk = Penduduk::all();
        return view('jenisbantuan.edit', compact('jenisbantuan', 'penduduk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        JenisBantuan::find($id)->update([
            'nama_bantuan' => $request->nama_bantuan,
        ]);

        return redirect()->route('jenisbantuan.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        JenisBantuan::destroy($id);
        return redirect()->route('jenisbantuan.index')->with('success', 'Data berhasil dihapus');
    }
}
