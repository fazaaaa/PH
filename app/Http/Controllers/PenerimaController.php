<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerima;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;

class PenerimaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penban = Penerima::orderBy('created_at','desc')->get();
        return view('backend.penban.index', compact('penban'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $penban = Penerima::all();
        return view('backend.penban.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'foto_diri' => 'required|mimes:jpeg,jpg,png,gif|max:2048'
        ]);
        $penban = new Penerima();
        $penban->nik = $request->nik;
        $penban->no_kk = $request->no_kk;
        $penban->nama = $request->nama;
        $penban->status_pkj = $request->status_pkj;
        $penban->jk = $request->jk;
        $penban->jb = $request->jb;
        if ($request->hasFile('foto_diri')) {
            $file = $request->file('foto_diri');
            $path = public_path() . '/assets/img/';
            $filename = Str::random(6) . '_' . $file->getClientOriginalName();// Ganti str_random dengan Str::random
            $file->move($path, $filename); // Anda tidak perlu menyimpan hasil move ke variabel jika tidak digunakan
            $penban->foto_diri = $filename;
        }    
        $penban->save();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil Menyimpan <b>" . $penban->penban . "</b>"
        ]);
        return redirect()->route('penban.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penban = Penerima::findOrFail($id);
        return view('backend.penban.show', compact('penban'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $penban = Penerima::findOrFail($id);
        return view('backend.penban.edit', compact('penban'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $penban = Penerima::findOrFail($id);
        $penban->nik = $request->nik;
        $penban->no_kk = $request->no_kk;
        $penban->nama = $request->nama;
        $penban->status_pkj = $request->status_pkj;
        $penban->jk = $request->jk;
        $penban->jb = $request->jb;
        if ($request->hasFile('foto_diri')) {
            $file = $request->file('foto_diri');
            $path = public_path('/assets/img/');
            $filename = Str::random(6) . '_' . $file->getClientOriginalName();
            $file->move($path, $filename);

            if ($penban->foto_diri){
                $filepath = public_path('/assets/img/') . $penban->foto_diri;
                try {
                    File::delete($filepath);
                } catch (FileNotFoundException $e) {
                    // Anda bisa menambahkan log atau penanganan kesalahan di sini jika diperlukan
                }
            }
            $penban->foto_diri = $filename;
        }    
        $penban->save();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil Mengedit <b>" . $penban->penban . "</b>"
        ]);
        return redirect()->route('penban.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penban = Penerima::findOrFail($id);
        if ($penban->foto_diri) {
            $filepath = public_path() . '/assets/img/' . $penban->foto_diri;
            try {
                File::delete($filepath);
            } catch (FileNotFoundException $e) {
                // File sudah dihapus/tidak ada
            }
        }
        $penban->delete();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil Menghapus <b>" . $penban->penban . "</b>"
        ]);
        return redirect()->route('penban.index');
    }
}
