<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\Pembayaran;

class PengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengeluaran = Pengeluaran::orderBy('created_at', 'desc')->get();
        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $pengeluaran
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
                'jenisPengeluaran' => 'required',
                'jumlahPengeluaran' => 'required',
                'tanggalPengeluaran' => 'required',
            ]);
            $pengeluaran = new Pengeluaran;
            $pengeluaran->jenis_pengeluaran = $request->jenisPengeluaran;
            $pengeluaran->jumlah_pengeluaran = $request->jumlahPengeluaran;
            $pengeluaran->tanggal_pengeluaran = $request->tanggalPengeluaran;
            $pengeluaran->save();

            return response()->json([
                'message' => 'Berhasil menambahkan data',
                'data' => $pengeluaran
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $pengeluaran = Pengeluaran::all();
        $pembayaran = Pembayaran::where('status_pembayaran', 'Lunas')->get();
        $total_pemasukan = $pembayaran->sum('jumlah_pembayaran');
        $total_pengeluaran = $pengeluaran->sum('jumlah_pengeluaran');
    
        $saldo = $total_pemasukan - $total_pengeluaran;
    
        return response()->json([
            'total_pemasukan' => $total_pemasukan,
            'total_pengeluaran' => $total_pengeluaran,
            'saldo' => $saldo
        ]);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
