<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kondisi;
use App\Models\Penerima;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;

class KondisiRumahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kondisiRumah = Kondisi::orderBy('created_at', 'desc')->get();
        return view('backend.konmah.index', compact('kondisiRumah'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penban = Penerima::all();
        return view('backend.konmah.create', compact('penban'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'id_penerima' => 'required',
            'foto_rumah' => 'required|mimes:jpeg,jpg,png,gif|max:2048'
        ]);
        $kondisiRumah = new Kondisi();
        $kondisiRumah->nik = $request->nik;
        $kondisiRumah->id_penerima = $request->id_penerima;
        $kondisiRumah->tmpt_berteduh = $request->tmpt_berteduh;
        $kondisiRumah->jenis_lantai = $request->jenis_lantai;
        $kondisiRumah->jenis_dinding = $request->jenis_dinding;
        $kondisiRumah->fasilitas_mck = $request->fasilitas_mck;
        $kondisiRumah->sumber_listrik = $request->sumber_listrik;
        if ($request->hasFile('foto_rumah')) {
            $file = $request->file('foto_rumah');
            $path = public_path() . '/assets/img/ ';
            $filename = str::random(6) . '_' . $file->getClientOriginalName();
            $file->move($path, $filename);
            $kondisiRumah->foto_rumah = $filename;
        }
        $kondisiRumah->save();
        Session::flash("flash_notification", [
            "level" => "Success",
            "message" => "Berhasil Menyimpan <b>" . $kondisiRumah->kondisiRumah . "</b>"
        ]);
        return redirect()->route('konmah.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kondisiRumah = Kondisi::findOrFail($id);
        return view('backend.konmah.show', compact('kondisiRumah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kondisiRumah = Kondisi::findOrFail($id);
        $penban = Penerima::all();
        return view('backend.konmah.edit', compact('kondisiRumah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'id_penerima' => 'required'
        ]);
        $kondisiRumah = Kondisi::findOrFail($id);
        $kondisiRumah->nik = $request->nik;
        $kondisiRumah->id_penerima = $request->id_penerima;
        $kondisiRumah->tmpt_berteduh = $request->tmpt_berteduh;
        $kondisiRumah->jenis_lantai = $request->jenis_lantai;
        $kondisiRumah->jenis_dinding = $request->jenis_dinding;
        $kondisiRumah->fasilitas_mck = $request->fasilitas_mck;
        $kondisiRumah->sumber_listrik = $request->sumber_listrik;
        if ($request->hasFile('foto_rumah')) {
            $file = $request->file('foto_rumah');
            $path = public_path('/assets/img/');
            $filename = Str::random(6) . '_' . $file->getClientOriginalName();
            $file->move($path, $filename);

            if ($kondisiRumah->foto_rumah){
                $filepath = public_path('/assets/img/') . $kondisiRumah->foto_rumah;
                try {
                    File::delete($filepath);
                } catch (FileNotFoundException $e) {
                    // Anda bisa menambahkan log atau penanganan kesalahan di sini jika diperlukan
                }
            }
            $kondisiRumah->foto_rumah = $filename;
        }
        $kondisiRumah->save();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil Mengedit <b>" . $kondisiRumah->kondisiRumah . "</b>"
        ]);
        return redirect()->route('konmah.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kondisiRumah = Kondisi::findOrFail($id);
        if ($kondisiRumah->foto_rumah) {
            $filepath = public_path() . '/assets/img/' . $kondisiRumah->foto_rumah;
            try {
                File::delete($filepath);
            } catch (FileNotFoundException $e) {
                // File sudah dihapus/tidak ada
            }
        }
        $kondisiRumah->delete();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil Menghapus <b>" . $kondisiRumah->kondisiRumah . "</b>"
        ]);
        return redirect()->route('konmah.index');
    }
}
