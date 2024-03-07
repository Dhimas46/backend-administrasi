<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rumah;
use App\Models\Penghuni;
use App\Models\Pembayaran;
use App\Models\HistoryPenghuni;

class RumahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rumah = Rumah::orderBy('created_at', 'desc')->get();
        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $rumah
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
                'alamatRumah' => 'required',
                'statusHunian' => 'required',
            ]);
            $rumah = new Rumah;
            $rumah->alamat_rumah = $request->alamatRumah;
            $rumah->status_hunian = $request->statusHunian;
            $rumah->save();

            if ($request->has('penghuni')) {
                $historyPenghuni = new HistoryPenghuni;
                $historyPenghuni->rumah_id = $rumah->id;
                $historyPenghuni->penghuni_id = $request->penghuni;
                $historyPenghuni->tanggal_mulai_hunian = now();
                $historyPenghuni->tanggal_selesai_hunian = null;
                $historyPenghuni->save();
            }

            return response()->json([
                'message' => 'Berhasil menambahkan data',
                'data' => $rumah
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data',
                'data' => $e->getMessage()
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            HistoryPenghuni::where('rumah_id', $id)->delete();
            $rumah = Rumah::findOrFail($id);
            $rumah->delete();
          
    
            return response()->json([
                'message' => 'Berhasil menghapus data',
                'data' => $rumah
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data',
                'data' => $e->getMessage()
            ]);
        } 
    }
    
}
