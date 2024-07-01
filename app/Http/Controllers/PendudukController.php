<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Klasifikasi;
use App\Models\KondisiRumah;
use App\Models\Pekerjaan;
use App\Models\Pendidikan;
use App\Models\Penduduk;
use App\Models\JenisBantuan;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Phpml\Classification\NaiveBayes;
use Illuminate\Support\Facades\Log;

class PendudukController extends Controller
{
    // Method untuk menampilkan data penduduk
    public function index(Request $request)
    {
        $query = Penduduk::query();
        $query->whereNotIn('id', [1, 2, 3, 4, 5]);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('No_KK', 'like', "%{$search}%")
                ->orWhere('NIK', 'like', "%{$search}%")
                ->orWhere('Nama_lengkap', 'like', "%{$search}%")
                ->orWhere('Hbg_kel', 'like', "%{$search}%")
                ->orWhere('JK', 'like', "%{$search}%")
                ->orWhere('tmpt_lahir', 'like', "%{$search}%")
                ->orWhere('tgl_lahir', 'like', "%{$search}%")
                ->orWhere('Agama', 'like', "%{$search}%")
                ->orWhere('Pendidikan_terakhir', 'like', "%{$search}%")
                ->orWhere('jenis_bantuan_id', function($query) use ($search) {
                    $query->select('id')
                    ->from('jenis_bantuan')
                    ->where('nama_bantuan', 'like', "%{$search}%");
                })
                ->orWhere('Penerima_bantuan', 'like', "%{$search}%");
        }

        $penduduk = $query->get();
        return view('penduduk.index', compact('penduduk'));
    }


    public function cetakpenduduk()
    {
        $penduduk = Penduduk::with(['pekerjaan', 'kondisiRumah'])->get();

        // Cek apakah ada penduduk yang tidak memiliki pekerjaan atau kondisi rumah
        foreach ($penduduk as $p) {
            if (is_null($p->pekerjaan) || is_null($p->kondisiRumah)) {
                return redirect()->back()->with('error', 'Lengkapi data pekerjaan dan kondisi rumah untuk cetak');
            }
        }

        return view('cetakpenduduk', compact('penduduk'));
    }


    public function cari(Request $request)
    {
        $pendudukIds = Penduduk::where('Nama_lengkap', 'like', "%" . $request->nama . "%")->pluck('id');
        $data = Klasifikasi::with('penduduk')->whereIn('id_penduduk', $pendudukIds)->get();
        $kondisi = KondisiRumah::with('penduduk')->whereIn('id_penduduk', $pendudukIds)->get();
        $hasils = Hasil::with('penduduk')->get();

        return view('penduduk', compact('data', 'kondisi', 'hasils'));
    }

    public function cetakklasifikasi(Request $request)
    {
        $query = Penduduk::query();
        $query->whereNotIn('id', [1, 2, 3, 4, 5]);
        $pendudukIds = Penduduk::where('Nama_lengkap', 'like', "%" . $request->nama . "%")->pluck('id');
        $data = Klasifikasi::with('penduduk')->whereIn('id_penduduk', $pendudukIds)->get();
        $kondisi = KondisiRumah::with('penduduk')->whereIn('id_penduduk', $pendudukIds)->get();

        $hasils = Hasil::with('penduduk')->get();
        return view('cetakklasifikasi', compact('data', 'kondisi', 'hasils'));
    }

    public function create()
    {
        $jenisbantuan = JenisBantuan::all();
        return view('penduduk.create', compact('jenisbantuan'));
    }

    public function store(Request $request)
    {
        // Log::info($request->all());

        $validatedData = $request->validate([
            'No_KK' => 'required',
            'NIK' => 'required',
            'pas_foto' =>
            'image|mimes:jpeg,png,jpg|max:2048',
            'Nama_lengkap' => 'required',
            'Hbg_kel' => 'required',
            'JK' => 'required',
            'tmpt_lahir' => 'required',
            'tgl_lahir' => 'required|date',
            'Agama' => 'required',
            'Pendidikan_terakhir' => 'required',
            'jenis_bantuan_id' => 'required',
            'Penerima_bantuan' => 'required'
        ]);

        if ($request->hasFile('pas_foto')) {
            $file = $request->file('pas_foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/pas_foto', $fileName);

            try {
                $penduduk = new Penduduk([
                    'No_KK' => $request->No_KK,
                    'NIK' => $request->NIK,
                    'pas_foto' => $fileName ?? null,
                    'Nama_lengkap' => $request->Nama_lengkap,
                    'Hbg_kel' => $request->Hbg_kel,
                    'JK' => $request->JK,
                    'tmpt_lahir' => $request->tmpt_lahir,
                    'tgl_lahir' => $request->tgl_lahir,
                    'Agama' => $request->Agama,
                    'Pendidikan_terakhir' => $request->Pendidikan_terakhir,
                    'jenis_bantuan_id' => $request->jenisbantuan->nama_bantuan,
                    'Penerima_bantuan' => $request->Penerima_bantuan
                ]);

                $penduduk->save();

                // inserrt id penduduk ke tabel klasifikasi
                $klasifikasi = new Klasifikasi();
                $klasifikasi->id_penduduk = $penduduk->id;
                $klasifikasi->save();

                $hasil = new Hasil();
                $hasil->id_penduduk = $penduduk->id;
                $hasil->save();

                return redirect()->route('penduduk.index')->with('success', 'Data berhasil ditambahkan');
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data. Error: ' . $e->getMessage());
            }
        } else {
            try {
                $penduduk = new Penduduk([
                    'No_KK' => $request->No_KK,
                    'NIK' => $request->NIK,
                    'Nama_lengkap' => $request->Nama_lengkap,
                    'Hbg_kel' => $request->Hbg_kel,
                    'JK' => $request->JK,
                    'tmpt_lahir' => $request->tmpt_lahir,
                    'tgl_lahir' => $request->tgl_lahir,
                    'Agama' => $request->Agama,
                    'Pendidikan_terakhir' => $request->Pendidikan_terakhir,
                    'jenis_bantuan_id' => $request->jenisbantuan->nama_bantuan,
                    'Penerima_bantuan' => $request->Penerima_bantuan
                ]);

                $penduduk->save();

                // inserrt id penduduk ke tabel klasifikasi
                $klasifikasi = new Klasifikasi();
                $klasifikasi->id_penduduk = $penduduk->id;
                $klasifikasi->save();

                $hasil = new Hasil();
                $hasil->id_penduduk = $penduduk->id;
                $hasil->save();

                return redirect()->route('penduduk.index')->with('success', 'Data berhasil ditambahkan');
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data. Error: ' . $e->getMessage());
            }
        }
    }


    public function edit($id)
    {
        $penduduk = Penduduk::find($id);
        return view('penduduk.edit', compact('penduduk'));
    }

    public function update(Request $request, $id)
    {
        if ($request->hasFile('pas_foto')) {
            $file = $request->file('pas_foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/pas_foto', $fileName);
        }
        Penduduk::find($id)->update([
            'No_KK' => $request->No_KK,
            'NIK' => $request->NIK,
            'pas_foto' => $fileName ?? null,
            'Nama_lengkap' => $request->Nama_lengkap,
            'Hbg_kel' => $request->Hbg_kel,
            'JK' => $request->JK,
            'tmpt_lahir' => $request->tmpt_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'Agama' => $request->Agama,
            'Pendidikan_terakhir' => $request->Pendidikan_terakhir,
            'jenis_bantuan_id' => $request->jenisbantuan->nama_bantuan,
            'Penerima_bantuan' => $request->Penerima_bantuan
        ]);

        return redirect()->route('penduduk.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        Penduduk::find($id)->delete();
        Pekerjaan::where('id_penduduk', $id)->delete();
        Pendidikan::where('id_penduduk', $id)->delete();
        KondisiRumah::where('id_penduduk', $id)->delete();
        Klasifikasi::where('id_penduduk', $id)->delete();
        Hasil::where('id_penduduk', $id)->delete();

        return redirect()->route('penduduk.index')->with('success', 'Data berhasil dihapus');
    }
}
