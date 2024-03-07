<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penghuni;
use App\Models\Pembayaran;
use App\Models\HistoryPenghuni;

class PenghuniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penghuni = Penghuni::with('history_penghuni')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $penghuni
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'namaLengkap' => 'required',
                'fotoKtp' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'statusPenghuni' => 'required',
                'nomorTelepon' => 'required',
                'statusPernikahan' => 'required'
            ]);
            $penghuni = new Penghuni;
            $penghuni->nama_lengkap = $request->namaLengkap;
            
            if($request->hasFile('fotoKtp')) {
                $file = $request->file('fotoKtp');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('uploads/penghuni/', $filename);
                $penghuni->foto_ktp = $filename;
            }

            $penghuni->status_penghuni = $request->statusPenghuni;
            $penghuni->nomor_telepon = $request->nomorTelepon;
            $penghuni->status_pernikahan = $request->statusPernikahan;
            $penghuni->save();

            return response()->json([
                'message' => 'Berhasil menambahkan data',
                'data' => $penghuni
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data'. $e->getMessage(),
                'success' => false
            ], 500);
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
                'namaLengkap' => 'required',
                'fotoKtp' => 'nullable|image|max:2048',
                'statusPenghuni' => 'required',
                'nomorTelepon' => 'required|numeric',
                'statusPernikahan' => 'required'
            ]);

            $penghuni = Penghuni::find($id);
            $penghuni->nama_lengkap = $request->namaLengkap;
    
            if ($request->hasFile('fotoKtp')) {
                if ($penghuni->foto_ktp) {
                    unlink('uploads/penghuni/' . $penghuni->foto_ktp);
                }
                $file = $request->file('fotoKtp');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('uploads/penghuni/', $filename);
                $penghuni->foto_ktp = $filename;
            }
    
            $penghuni->status_penghuni = $request->statusPenghuni;
            $penghuni->nomor_telepon = $request->nomorTelepon;
            $penghuni->status_pernikahan = $request->statusPernikahan;
            $penghuni->save();
    
            return response()->json([
                'message' => 'Berhasil mengubah data',
                'data' => $penghuni
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah data'. $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    try {
        $penghuni = Penghuni::find($id);
        
        if (!$penghuni) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
                'success' => false
            ], 404);
        }

        HistoryPenghuni::where('penghuni_id', $id)->delete();

        Pembayaran::where('penghuni_id', $id)->delete();

        if ($penghuni->foto_ktp && file_exists('uploads/penghuni/' . $penghuni->foto_ktp)) {
            unlink('uploads/penghuni/' . $penghuni->foto_ktp);
        }
        $penghuni->delete();

        return response()->json([
            'message' => 'Berhasil menghapus data',
            'data' => $penghuni
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Gagal menghapus data: ' . $e->getMessage(),
            'success' => false
        ], 500);
    }
}

    
    
}
