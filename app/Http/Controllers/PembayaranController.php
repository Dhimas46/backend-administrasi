<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Penghuni;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembayaran = Pembayaran::with('penghuni')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $pembayaran
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'penghuni' => 'required',
                'jenisIuran' => 'required',
                'jumlahPembayaran' => 'required',
                'tanggalPembayaran' => 'required',
                'statusPembayaran' => 'required',
                'jenisPembayaran' => 'required'
            ]);
    
            $penghuniId = $request->penghuni;
            $jenisIuran = $request->jenisIuran;
            $jumlahPembayaranTotal = $request->jumlahPembayaran;
            $tanggalPembayaran = $request->tanggalPembayaran;
            $statusPembayaran = $request->statusPembayaran;
            $jenisPembayaran = $request->jenisPembayaran;
    
            if ($jenisPembayaran == 'Bulanan') {
                $pembayaran = Pembayaran::create([
                    'penghuni_id' => $penghuniId,
                    'jenis_iuran' => $jenisIuran,
                    'jumlah_pembayaran' => $jumlahPembayaranTotal,
                    'tanggal_pembayaran' => $tanggalPembayaran,
                    'status_pembayaran' => $statusPembayaran,
                ]);
    
                return response()->json([
                    'message' => 'Berhasil menambahkan data pembayaran bulanan',
                    'data' => $pembayaran
                ]);
            } else {
                $tahunan = [];
                $jumlahPembayaranBulanan = $jumlahPembayaranTotal / 12;
                for ($i = 1; $i <= 12; $i++) {
                    $tanggalPembayaranBulanIni = date('Y-m-d', strtotime("+$i months", strtotime($tanggalPembayaran)));
                    $tahunan[] = [
                        'penghuni_id' => $penghuniId,
                        'jenis_iuran' => $jenisIuran,
                        'jumlah_pembayaran' => $jumlahPembayaranBulanan,
                        'tanggal_pembayaran' => $tanggalPembayaranBulanIni,
                        'status_pembayaran' => $statusPembayaran,
                    ];
                }
    
                Pembayaran::insert($tahunan);
    
                return response()->json([
                    'message' => 'Berhasil menambahkan data pembayaran tahunan',
                    'data' => $tahunan
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'penghuni' => 'required',
                'jenisIuran' => 'required',
                'jumlahPembayaran' => 'required',
                'tanggalPembayaran' => 'required',
                'statusPembayaran' => 'required'
            ]);

            $pembayaran = Pembayaran::find($id);
            
            if ($pembayaran) {
                $pembayaran->update([
                    'penghuni_id' => $request->penghuni,
                    'jenis_iuran' => $request->jenisIuran,
                    'jumlah_pembayaran' => $request->jumlahPembayaran,
                    'tanggal_pembayaran' => $request->tanggalPembayaran,
                    'status_pembayaran' => $request->statusPembayaran
                ]);
            }

            return response()->json([
                'message' => 'Berhasil mengubah data',
                'data' => $pembayaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah data',
                'data' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pembayaran = Pembayaran::find($id);
            $pembayaran->delete();

            return response()->json([
                'message' => 'Berhasil menghapus data',
                'data' => $pembayaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data',
                'data' => $e->getMessage()
            ]);
        }
    }
}
