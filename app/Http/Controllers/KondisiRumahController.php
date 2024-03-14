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
        $konru = Kondisi::orderBy('create_at','desc')->get();
        return view('backend.konmah.index', compact('konru'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penban = Penerima::all();
        return view('backend.konmah.create', compact('konru'));
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
        $konru = new Kondisi();
        $konru->nik = $request->nik;
        $konru->id_penerima = $request->id_penerima;
        $konru->tmpt_berteduh = $request->tmpt_berteduh;
        $konru->jenis_lantai = $request->jenis_lantai;
        $konru->jenis_dinding = $request->jenis_dinding;
        $konru->fasilitas_mck = $request->fasilitas_mck;
        $konru->sumber_listrik = $request->sumber_listrik;
        if ($request->hasFile('foto_rumah')) {
            $file = $request->file('foto_rumah');
            $path = public_path() . '/assets/img/ ';
            $filename = str::random(6) . '_' . $file->getClientOriginalName();
            $file->move($path, $filename);
            $konru->foto_rumah = $filename;
        }
        $konru->save();
        Session::flash("flash_notification", [
            "level" => "Success",
            "message" => "Berhasil Menyimpan <b>" . $konru->konru . "</b>"
        ]);
        return redirect()->route('konmah.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $konru = Kondisi::findOrFail($id);
        return view('backend.konmah.show', compact('konru'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $konru = Kondisi::findOrFail($id);
        $penban = Penerima::all();
        return view('backend.konmah.edit', compact('konru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'id_penerima' => 'required'
        ]);
        $konru = Kondisi::findOrFail($id);
        $konru->nik = $request->nik;
        $konru->id_penerima = $request->id_penerima;
        $konru->tmpt_berteduh = $request->tmpt_berteduh;
        $konru->jenis_lantai = $request->jenis_lantai;
        $konru->jenis_dinding = $request->jenis_dinding;
        $konru->fasilitas_mck = $request->fasilitas_mck;
        $konru->sumber_listrik = $request->sumber_listrik;
        if ($request->hasFile('foto_rumah')) {
            $file = $request->file('foto_rumah');
            $path = public_path('/assets/img/');
            $filename = Str::random(6) . '_' . $file->getClientOriginalName();
            $file->move($path, $filename);

            if ($konru->foto_rumah){
                $filepath = public_path('/assets/img/') . $konru->foto_rumah;
                try {
                    File::delete($filepath);
                } catch (FileNotFoundException $e) {
                    // Anda bisa menambahkan log atau penanganan kesalahan di sini jika diperlukan
                }
            }
            $konru->foto_rumah = $filename;
        }
        $konru->save();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil Mengedit <b>" . $konru->konru . "</b>"
        ]);
        return redirect()->route('konmah.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $konru = Kondisi::findOrFail($id);
        if ($konru->foto_rumah) {
            $filepath = public_path() . '/assets/img/' . $konru->foto_rumah;
            try {
                File::delete($filepath);
            } catch (FileNotFoundException $e) {
                // File sudah dihapus/tidak ada
            }
        }
        $konru->delete();
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil Menghapus <b>" . $konru->konru . "</b>"
        ]);
        return redirect()->route('konmah.index');
    }
}
