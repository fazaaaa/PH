<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Klasifikasi;
use App\Models\Pekerjaan;
use App\Models\Penduduk;
use Illuminate\Http\Request;

class PekerjaanController extends Controller
{
    public function index()
    {
        $pekerjaan = Pekerjaan::with('penduduk')->get();
        return view('pekerjaan.index', compact('pekerjaan'));
    }

    public function create()
    {
        $penduduk = Penduduk::all();
        return view('pekerjaan.create', compact('penduduk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penduduk' => 'required',
            'pekerjaan' => 'required',
            'pendapatan' => 'required',
            'jumlah' => 'required',
            'status' => 'required'
        ]);

        try {
            $pekerjaan = Pekerjaan::create($request->all());
            // update klasifikasi->pekerjaan sesuai id_penduduk dengan nilai penghasilan ke dalam tabel
            $hit = '';
            if ($request->pendapatan <= 3000000) {
                $hit = 'Rendah';
            } elseif ($request->pendapatan <= 5000000) {
                $hit = 'Menengah';
            } elseif ($request->pendapatan >= 10000000) {
                $hit = 'Tinggi';
            }

            $jml = '';
            if ($request->jumlah <= 3) {
                $jml = 'Sedikit';
            } elseif ($request->jumlah <= 5) {
                $jml = 'Sedang';
            } elseif ($request->jumlah > 5) {
                $jml = 'Banyak';
            }


            $klasifikasi = Klasifikasi::where('id_penduduk', $request->id_penduduk)->first();
            $klasifikasi->update([
                'pendapatan' => $hit,
                'jumlah' => $jml,
                'status' => $request->status
            ]);

            $hasil = Hasil::where('id_penduduk', $request->id_penduduk)->first();
            $hasil->update([
                'pendapatan' => $hit,
                'jumlah' => $jml,
                'status' => $request->status
            ]);

            $tipe = '';
            if ($hit == 'Rendah' && $jml == 'Sedikit' && $request->status == 'Bekerja') {
                $tipe = 'rsb';
            } elseif ($hit == 'Rendah' && $jml == 'Sedikit' && $request->status == 'Tidak Bekerja') {
                $tipe = 'rst';
            } elseif ($hit == 'Rendah' && $jml == 'Sedang' && $request->status == 'Bekerja') {
                $tipe = 'rsdb';
            } elseif ($hit == 'Rendah' && $jml == 'Sedang' && $request->status == 'Tidak Bekerja') {
                $tipe = 'rstb';
            } elseif ($hit == 'Rendah' && $jml == 'Banyak' && $request->status == 'Bekerja') {
                $tipe = 'rbb';
            } elseif ($hit == 'Rendah' && $jml == 'Banyak' && $request->status == 'Tidak Bekerja') {
                $tipe = 'rbt';
            } elseif ($hit == 'Menengah' && $jml == 'Sedikit' && $request->status == 'Bekerja') {
                $tipe = 'msb';
            } elseif ($hit == 'Menengah' && $jml == 'Sedikit' && $request->status == 'Tidak Bekerja') {
                $tipe = 'mst';
            } elseif ($hit == 'Menengah' && $jml == 'Sedang' && $request->status == 'Bekerja') {
                $tipe = 'msdb';
            } elseif ($hit == 'Menengah' && $jml == 'Sedang' && $request->status == 'Tidak Bekerja') {
                $tipe = 'mstb';
            } elseif ($hit == 'Menengah' && $jml == 'Banyak' && $request->status == 'Bekerja') {
                $tipe = 'mbb';
            } elseif ($hit == 'Menengah' && $jml == 'Banyak' && $request->status == 'Tidak Bekerja') {
                $tipe = 'mbb';
            } elseif ($hit == 'Tinggi' && $jml == 'Sedikit' && $request->status == 'Bekerja') {
                $tipe = 'tsb';
            } elseif ($hit == 'Tinggi' && $jml == 'Sedikit' && $request->status == 'Tidak Bekerja') {
                $tipe = 'tst';
            } elseif ($hit == 'Tinggi' && $jml == 'Sedang' && $request->status == 'Bekerja') {
                $tipe = 'tsdb';
            } elseif ($hit == 'Tinggi' && $jml == 'Sedang' && $request->status == 'Tidak Bekerja') {
                $tipe = 'tstb';
            } elseif ($hit == 'Tinggi' && $jml == 'Banyak' && $request->status == 'Bekerja') {
                $tipe = 'tbb';
            } elseif ($hit == 'Tinggi' && $jml == 'Banyak' && $request->status == 'Tidak Bekerja') {
                $tipe = 'tbt';
            }

            $id = $request->id_penduduk;

            $keterangan = $this->perhitunganNaiveByes($tipe, $id);
            $hasil = Hasil::where('id_penduduk', $id)->first();
            $hasil->update([
                'keterangan' => $keterangan
            ]);

            $resultc45 = '';
            $highest = $this->perhitunganC45();
            if ($highest == 'gainpendapatan') {
                $kelayakan = [
                    'Rendah' => 'Layak',
                    'Menengah' => 'Tidak Layak',
                    'Tinggi' => 'Tidak Layak',
                ];
                $resultc45 = $kelayakan[$hit] ?? '';
            } elseif ($highest == 'gainjumlah') {
                $kelayakan = [
                    'Sedikit' => 'Tidak Layak',
                    'Sedang' => 'Tidak Layak',
                    'Banyak' => 'Layak',
                ];
                $resultc45 = $kelayakan[$hit] ?? '';
            } elseif ($highest == 'gainstatus') {
                $kelayakan = [
                    'Bekerja' => 'Tidak Layak',
                    'Tidak Bekerja' => 'Layak',
                ];
                $resultc45 = $kelayakan[$hit] ?? '';
            }
            $hasilc45 = Klasifikasi::where('id_penduduk', $id)->first();
            $hasilc45->update([
                'keterangan' => $resultc45
            ]);

            return redirect()->route('pekerjaan.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data. Error: ' . $e->getMessage());
        }
    }

    private function perhitunganNaiveByes($tipe, $id)
    {
        $totalData = Hasil::count();
        $layak = Hasil::where('keterangan', 'Layak')->count();
        $tidakLayak = Hasil::where('keterangan', 'Tidak Layak')->count();
        // pendapatan
        $pendapatanrendahlayak = Hasil::where('pendapatan', 'Rendah')->where('keterangan', 'Layak')->count();
        $pendapatanrendahtidakLayak = Hasil::where('pendapatan', 'Rendah')->where('keterangan', 'Tidak Layak')->count();
        $pendapatanmenengahlayak = Hasil::where('pendapatan', 'Menengah')->where('keterangan', 'Layak')->count();
        $pendapatanmenengahtidakLayak = Hasil::where('pendapatan', 'Menengah')->where('keterangan', 'Tidak Layak')->count();
        $pendapatantinggilayak = Hasil::where('pendapatan', 'Tinggi')->where('keterangan', 'Layak')->count();
        $pendapatantinggitidakLayak = Hasil::where('pendapatan', 'Tinggi')->where('keterangan', 'Tidak Layak')->count();

        // jumlah anggota keluarga
        $jumlahsedikitlayak = Hasil::where('jumlah', 'Sedikit')->where('keterangan', 'Layak')->count();
        $jumlahsedikittidakLayak = Hasil::where('jumlah', 'Sedikit')->where('keterangan', 'Tidak Layak')->count();
        $jumlahsedanglayak = Hasil::where('jumlah', 'Sedang')->where('keterangan', 'Layak')->count();
        $jumlahsedangtidakLayak = Hasil::where('jumlah', 'Sedang')->where('keterangan', 'Tidak Layak')->count();
        $jumlahbanyaklayak = Hasil::where('jumlah', 'Banyak')->where('keterangan', 'Layak')->count();
        $jumlahbanyaktidakLayak = Hasil::where('jumlah', 'Banyak')->where('keterangan', 'Tidak Layak')->count();

        // status pekerjaan
        $statuspekerjaanlayak = Hasil::where('status', 'Bekerja')->where('keterangan', 'Layak')->count();
        $statuspekerjaantidakLayak = Hasil::where('status', 'Bekerja')->where('keterangan', 'Tidak Layak')->count();
        $statustdkpekerjaanlayak = Hasil::where('status', 'Tidak Bekerja')->where('keterangan', 'Layak')->count();
        $statustdkpekerjaantidaklayak = Hasil::where('status', 'Tidak Bekerja')->where('keterangan', 'Tidak Layak')->count();

        // perior prob
        $playak = $this->priorprob($layak, $totalData);
        $ptidakLayak = $this->priorprob($tidakLayak, $totalData);
        // Likelihood
        $prendahlayak = $this->calculateLikelihoods($pendapatanrendahlayak, $layak);
        $prendahtidakLayak = $this->calculateLikelihoods($pendapatanrendahtidakLayak, $tidakLayak);
        $pmenengahlayak = $this->calculateLikelihoods($pendapatanmenengahlayak, $layak);
        $pmenengahtidakLayak = $this->calculateLikelihoods($pendapatanmenengahtidakLayak, $tidakLayak);
        $ptinggilayak = $this->calculateLikelihoods($pendapatantinggilayak, $layak);
        $ptinggitidakLayak = $this->calculateLikelihoods($pendapatantinggitidakLayak, $tidakLayak);

        $jsedikitlayak = $this->calculateLikelihoods($jumlahsedikitlayak, $layak);
        $jsedikittidakLayak = $this->calculateLikelihoods($jumlahsedikittidakLayak, $tidakLayak);
        $jsedanglayak = $this->calculateLikelihoods($jumlahsedanglayak, $layak);
        $jsedangtidakLayak = $this->calculateLikelihoods($jumlahsedangtidakLayak, $tidakLayak);
        $jbanyaklayak = $this->calculateLikelihoods($jumlahbanyaklayak, $layak);
        $jbanyaktidakLayak = $this->calculateLikelihoods($jumlahbanyaktidakLayak, $tidakLayak);

        $spekerjaanlayak = $this->calculateLikelihoods($statuspekerjaanlayak, $layak);
        $spekerjaantidakLayak = $this->calculateLikelihoods($statuspekerjaantidakLayak, $tidakLayak);
        $stdkpekerjaanlayak = $this->calculateLikelihoods($statustdkpekerjaanlayak, $layak);
        $stdkpekerjaantidaklayak = $this->calculateLikelihoods($statustdkpekerjaantidaklayak, $tidakLayak);

        // Posterior Prob
        if ($tipe == 'rsb') {
            $posteriorlayak = $this->posterprob($playak, $prendahlayak, $jsedikitlayak, $spekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $prendahtidakLayak, $jsedikittidakLayak, $spekerjaantidakLayak);
        } elseif ($tipe == 'rst') {
            $posteriorlayak = $this->posterprob($playak, $prendahlayak, $jsedikitlayak, $stdkpekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $prendahtidakLayak, $jsedikittidakLayak, $stdkpekerjaantidaklayak);
        } elseif ($tipe == 'rsdb') {
            $posteriorlayak = $this->posterprob($playak, $prendahlayak, $jsedanglayak, $spekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $prendahtidakLayak, $jsedangtidakLayak, $spekerjaantidakLayak);
        } elseif ($tipe == 'rstb') {
            $posteriorlayak = $this->posterprob($playak, $prendahlayak, $jsedanglayak, $stdkpekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $prendahtidakLayak, $jsedangtidakLayak, $stdkpekerjaantidaklayak);
        } elseif ($tipe == 'rbb') {
            $posteriorlayak = $this->posterprob($playak, $prendahlayak, $jbanyaklayak, $spekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $prendahtidakLayak, $jbanyaktidakLayak, $spekerjaantidakLayak);
        } elseif ($tipe == 'rbt') {
            $posteriorlayak = $this->posterprob($playak, $prendahlayak, $jbanyaklayak, $stdkpekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $prendahtidakLayak, $jbanyaktidakLayak, $stdkpekerjaantidaklayak);
        } elseif ($tipe == 'msb') {
            $posteriorlayak = $this->posterprob($playak, $pmenengahlayak, $jsedikitlayak, $spekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $pmenengahtidakLayak, $jsedikittidakLayak, $spekerjaantidakLayak);
        } elseif ($tipe == 'mst') {
            $posteriorlayak = $this->posterprob($playak, $pmenengahlayak, $jsedikitlayak, $stdkpekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $pmenengahtidakLayak, $jsedikittidakLayak, $stdkpekerjaantidaklayak);
        } elseif ($tipe == 'msdb') {
            $posteriorlayak = $this->posterprob($playak, $pmenengahlayak, $jsedanglayak, $spekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $pmenengahtidakLayak, $jsedangtidakLayak, $spekerjaantidakLayak);
        } elseif ($tipe == 'mstb') {
            $posteriorlayak = $this->posterprob($playak, $pmenengahlayak, $jsedanglayak, $stdkpekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $pmenengahtidakLayak, $jsedangtidakLayak, $stdkpekerjaantidaklayak);
        } elseif ($tipe == 'mbb') {
            $posteriorlayak = $this->posterprob($playak, $pmenengahlayak, $jbanyaklayak, $spekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $pmenengahtidakLayak, $jbanyaktidakLayak, $spekerjaantidakLayak);
        } elseif ($tipe == 'mbt') {
            $posteriorlayak = $this->posterprob($playak, $pmenengahlayak, $jbanyaklayak, $stdkpekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $pmenengahtidakLayak, $jbanyaktidakLayak, $stdkpekerjaantidaklayak);
        } elseif ($tipe == 'tsb') {
            $posteriorlayak = $this->posterprob($playak, $ptinggilayak, $jsedikitlayak, $spekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $ptinggitidakLayak, $jsedikittidakLayak, $spekerjaantidakLayak);
        } elseif ($tipe == 'tst') {
            $posteriorlayak = $this->posterprob($playak, $ptinggilayak, $jsedikitlayak, $stdkpekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $ptinggitidakLayak, $jsedikittidakLayak, $stdkpekerjaantidaklayak);
        } elseif ($tipe == 'tsdb') {
            $posteriorlayak = $this->posterprob($playak, $ptinggilayak, $jsedanglayak, $spekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $ptinggitidakLayak, $jsedangtidakLayak, $spekerjaantidakLayak);
        } elseif ($tipe == 'tstb') {
            $posteriorlayak = $this->posterprob($playak, $ptinggilayak, $jsedanglayak, $stdkpekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $ptinggitidakLayak, $jsedangtidakLayak, $stdkpekerjaantidaklayak);
        } elseif ($tipe == 'tbb') {
            $posteriorlayak = $this->posterprob($playak, $ptinggilayak, $jbanyaklayak, $spekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $ptinggitidakLayak, $jbanyaktidakLayak, $spekerjaantidakLayak);
        } elseif ($tipe == 'tbt') {
            $posteriorlayak = $this->posterprob($playak, $ptinggilayak, $jbanyaklayak, $stdkpekerjaanlayak);
            $posteriortidakLayak = $this->posterprob($ptidakLayak, $ptinggitidakLayak, $jbanyaktidakLayak, $stdkpekerjaantidaklayak);
        }

        // Cek apkah poserior layak lebih besar dari posterior tidak layak
        $keterangan = '';
        if ($posteriorlayak > $posteriortidakLayak) {
            $keterangan = 'Layak';
        } else {
            $keterangan = 'Tidak Layak';
        }

        return $keterangan;
    }

    private function priorprob($jumlah, $total)
    {
        return $jumlah / $total;
    }

    private function calculateLikelihoods($categories, $total)
    {
        return $categories / $total;
    }

    private function posterprob($layak, $pendapatan, $jumlah, $status)
    {
        return $layak * $pendapatan * $jumlah * $status;
    }

    private function perhitunganC45()
    {
        $totalData = Klasifikasi::count();
        $layak = Klasifikasi::where('keterangan', 'Layak')->count();
        $tidakLayak = Klasifikasi::where('keterangan', 'Tidak Layak')->count();
        // pendapatan
        $pendapatanrendah  = Klasifikasi::where('pendapatan', 'Rendah')->count();
        $pendapatanrendahlayak = Klasifikasi::where('pendapatan', 'Rendah')->where('keterangan', 'Layak')->count();
        $pendapatanrendahtidakLayak = Klasifikasi::where('pendapatan', 'Rendah')->where('keterangan', 'Tidak Layak')->count();
        $pendapatanmenengah = Klasifikasi::where('pendapatan', 'Menengah')->count();
        $pendapatanmenengahlayak = Klasifikasi::where('pendapatan', 'Menengah')->where('keterangan', 'Layak')->count();
        $pendapatanmenengahtidakLayak = Klasifikasi::where('pendapatan', 'Menengah')->where('keterangan', 'Tidak Layak')->count();
        $pendapatantinggi = Klasifikasi::where('pendapatan', 'Tinggi')->count();
        $pendapatantinggilayak = Klasifikasi::where('pendapatan', 'Tinggi')->where('keterangan', 'Layak')->count();
        $pendapatantinggitidakLayak = Klasifikasi::where('pendapatan', 'Tinggi')->where('keterangan', 'Tidak Layak')->count();

        // jumlah anggota keluarga
        $jumlahsedikit = Klasifikasi::where('jumlah', 'Sedikit')->count();
        $jumlahsedikitlayak = Klasifikasi::where('jumlah', 'Sedikit')->where('keterangan', 'Layak')->count();
        $jumlahsedikittidakLayak = Klasifikasi::where('jumlah', 'Sedikit')->where('keterangan', 'Tidak Layak')->count();
        $jumlahsedang = Klasifikasi::where('jumlah', 'Sedang')->count();
        $jumlahsedanglayak = Klasifikasi::where('jumlah', 'Sedang')->where('keterangan', 'Layak')->count();
        $jumlahsedangtidakLayak = Klasifikasi::where('jumlah', 'Sedang')->where('keterangan', 'Tidak Layak')->count();
        $jumlahbanyak = Klasifikasi::where('jumlah', 'Banyak')->count();
        $jumlahbanyaklayak = Klasifikasi::where('jumlah', 'Banyak')->where('keterangan', 'Layak')->count();
        $jumlahbanyaktidakLayak = Klasifikasi::where('jumlah', 'Banyak')->where('keterangan', 'Tidak Layak')->count();

        // status pekerjaan
        $statuspekerjaan = Klasifikasi::where('status', 'Bekerja')->count();
        $statuspekerjaanlayak = Klasifikasi::where('status', 'Bekerja')->where('keterangan', 'Layak')->count();
        $statuspekerjaantidakLayak = Klasifikasi::where('status', 'Bekerja')->where('keterangan', 'Tidak Layak')->count();
        $statustdkpekerjaan = Klasifikasi::where('status', 'Tidak Bekerja')->count();
        $statustdkpekerjaanlayak = Klasifikasi::where('status', 'Tidak Bekerja')->where('keterangan', 'Layak')->count();
        $statustdkpekerjaantidaklayak = Klasifikasi::where('status', 'Tidak Bekerja')->where('keterangan', 'Tidak Layak')->count();

        // entropy
        $entropy = $this->calculateEntropy($layak, $tidakLayak, $totalData);

        // Pendapatan
        $entropypendrendah = $this->calculateEntropy($pendapatanrendahlayak, $pendapatanrendahtidakLayak, $pendapatanrendah);
        $entropypendmenengah = $this->calculateEntropy($pendapatanmenengahlayak, $pendapatanmenengahtidakLayak, $pendapatanmenengah);
        $entropypendtinggi = $this->calculateEntropy($pendapatantinggilayak, $pendapatantinggitidakLayak, $pendapatantinggi);
        $entropipendapatan = ($pendapatanrendah / $totalData) * $entropypendrendah + ($pendapatanmenengah / $totalData) * $entropypendmenengah + ($pendapatantinggi / $totalData) * $entropypendtinggi;

        // jumlah
        $entropyjmlsedikit = $this->calculateEntropy($jumlahsedikitlayak, $jumlahsedikittidakLayak, $jumlahsedikit);
        $entropyjmlsedang = $this->calculateEntropy($jumlahsedanglayak, $jumlahsedangtidakLayak, $jumlahsedang);
        $entropyjmlbanyak = $this->calculateEntropy($jumlahbanyaklayak, $jumlahbanyaktidakLayak, $jumlahbanyak);
        $entropijumlah = ($jumlahsedikit / $totalData) * $entropyjmlsedikit + ($jumlahsedang / $totalData) * $entropyjmlsedang + ($jumlahbanyak / $totalData) * $entropyjmlbanyak;

        // status
        $entropystatusbekerja = $this->calculateEntropy($statuspekerjaanlayak, $statuspekerjaantidakLayak, $statuspekerjaan);
        $entropystatustdkbekerja = $this->calculateEntropy($statustdkpekerjaanlayak, $statustdkpekerjaantidaklayak, $statustdkpekerjaan);
        $entropistatus = ($statuspekerjaan / $totalData) * $entropystatusbekerja + ($statustdkpekerjaan / $totalData) * $entropystatustdkbekerja;

        // gain
        $gainpendapatan = $entropy - $entropipendapatan;
        $gainjumlah = $entropy - $entropijumlah;
        $gainstatus = $entropy - $entropistatus;

        $gains = [
            'gainpendapatan' => $gainpendapatan,
            'gainjumlah' => $gainjumlah,
            'gainstatus' => $gainstatus
        ];
        $highestGain = max($gains);
        $highestGainKey = array_search($highestGain, $gains);

        return $highestGainKey;
    }

    public function calculateEntropy($countTidak, $countYa, $total)
    {
        $entropy = 0;
        if ($total > 0) {
            $pTidak = $countTidak / $total;
            $pYa = $countYa / $total;

            $entropyPartTidak = $pTidak > 0 ? - ($pTidak * log($pTidak, 2)) : 0;
            $entropyPartYa = $pYa > 0 ? - ($pYa * log($pYa, 2)) : 0;

            $entropy = $entropyPartTidak + $entropyPartYa;
        }

        return ceil($entropy * 1000) / 1000;
    }

    public function edit($id)
    {
        $pekerjaan = Pekerjaan::find($id);
        $penduduk = Penduduk::all();
        return view('pekerjaan.edit', compact('pekerjaan', 'penduduk'));
    }

    public function update(Request $request, $id)
    {
        Pekerjaan::find($id)->update([
            'pekerjaan' => $request->pekerjaan,
            'pendapatan' => $request->pendapatan,
            'jumlah' => $request->jumlah,
            'status' => $request->status
        ]);

        $hit = '';
        if ($request->pendapatan <= 3000000) {
            $hit = 'Rendah';
        } elseif ($request->pendapatan <= 5000000) {
            $hit = 'Menengah';
        } elseif ($request->pendapatan >= 10000000) {
            $hit = 'Tinggi';
        }

        $jml = '';
        if ($request->jumlah <= 3) {
            $jml = 'Sedikit';
        } elseif ($request->jumlah <= 5) {
            $jml = 'Sedang';
        } elseif ($request->jumlah > 5) {
            $jml = 'Banyak';
        }


        $klasifikasi = Klasifikasi::where('id_penduduk', $request->id_penduduk)->first();
        $klasifikasi->update([
            'pendapatan' => $hit,
            'jumlah' => $jml,
            'status' => $request->status
        ]);

        $hasil = Hasil::where('id_penduduk', $request->id_penduduk)->first();
        $hasil->update([
            'pendapatan' => $hit,
            'jumlah' => $jml,
            'status' => $request->status
        ]);

        $tipe = '';
        if ($hit == 'Rendah' && $jml == 'Sedikit' && $request->status == 'Bekerja') {
            $tipe = 'rsb';
        } elseif ($hit == 'Rendah' && $jml == 'Sedikit' && $request->status == 'Tidak Bekerja') {
            $tipe = 'rst';
        } elseif ($hit == 'Rendah' && $jml == 'Sedang' && $request->status == 'Bekerja') {
            $tipe = 'rsdb';
        } elseif ($hit == 'Rendah' && $jml == 'Sedang' && $request->status == 'Tidak Bekerja') {
            $tipe = 'rstb';
        } elseif ($hit == 'Rendah' && $jml == 'Banyak' && $request->status == 'Bekerja') {
            $tipe = 'rbb';
        } elseif ($hit == 'Rendah' && $jml == 'Banyak' && $request->status == 'Tidak Bekerja') {
            $tipe = 'rbt';
        } elseif ($hit == 'Menengah' && $jml == 'Sedikit' && $request->status == 'Bekerja') {
            $tipe = 'msb';
        } elseif ($hit == 'Menengah' && $jml == 'Sedikit' && $request->status == 'Tidak Bekerja') {
            $tipe = 'mst';
        } elseif ($hit == 'Menengah' && $jml == 'Sedang' && $request->status == 'Bekerja') {
            $tipe = 'msdb';
        } elseif ($hit == 'Menengah' && $jml == 'Sedang' && $request->status == 'Tidak Bekerja') {
            $tipe = 'mstb';
        } elseif ($hit == 'Menengah' && $jml == 'Banyak' && $request->status == 'Bekerja') {
            $tipe = 'mbb';
        } elseif ($hit == 'Menengah' && $jml == 'Banyak' && $request->status == 'Tidak Bekerja') {
            $tipe = 'mbb';
        } elseif ($hit == 'Tinggi' && $jml == 'Sedikit' && $request->status == 'Bekerja') {
            $tipe = 'tsb';
        } elseif ($hit == 'Tinggi' && $jml == 'Sedikit' && $request->status == 'Tidak Bekerja') {
            $tipe = 'tst';
        } elseif ($hit == 'Tinggi' && $jml == 'Sedang' && $request->status == 'Bekerja') {
            $tipe = 'tsdb';
        } elseif ($hit == 'Tinggi' && $jml == 'Sedang' && $request->status == 'Tidak Bekerja') {
            $tipe = 'tstb';
        } elseif ($hit == 'Tinggi' && $jml == 'Banyak' && $request->status == 'Bekerja') {
            $tipe = 'tbb';
        } elseif ($hit == 'Tinggi' && $jml == 'Banyak' && $request->status == 'Tidak Bekerja') {
            $tipe = 'tbt';
        }

        $id = $request->id_penduduk;

        $keterangan = $this->perhitunganNaiveByes($tipe, $id);
        $hasil = Hasil::where('id_penduduk', $id)->first();
        $hasil->update([
            'keterangan' => $keterangan
        ]);

        $resultc45 = '';
        $highest = $this->perhitunganC45();
        if ($highest == 'gainpendapatan') {
            $kelayakan = [
                'Rendah' => 'Layak',
                'Menengah' => 'Tidak Layak',
                'Tinggi' => 'Tidak Layak',
            ];
            $resultc45 = $kelayakan[$hit] ?? '';
        } elseif ($highest == 'gainjumlah') {
            $kelayakan = [
                'Sedikit' => 'Tidak Layak',
                'Sedang' => 'Tidak Layak',
                'Banyak' => 'Layak',
            ];
            $resultc45 = $kelayakan[$hit] ?? '';
        } elseif ($highest == 'gainstatus') {
            $kelayakan = [
                'Bekerja' => 'Tidak Layak',
                'Tidak Bekerja' => 'Layak',
            ];
            $resultc45 = $kelayakan[$hit] ?? '';
        }
        $hasilc45 = Klasifikasi::where('id_penduduk', $id)->first();
        $hasilc45->update([
            'keterangan' => $resultc45
        ]);

        return redirect()->route('pekerjaan.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        Pekerjaan::destroy($id);
        return redirect()->route('pekerjaan.index')->with('success', 'Data berhasil dihapus');
    }
}
