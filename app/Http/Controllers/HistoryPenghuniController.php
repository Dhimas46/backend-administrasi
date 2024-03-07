<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rumah;
use App\Models\Penghuni;
use App\Models\Pembayaran;
use App\Models\HistoryPenghuni;

class HistoryPenghuniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = HistoryPenghuni::with('penghuni', 'rumah')->get();
        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $data
        ], 200);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = HistoryPenghuni::with('penghuni', 'rumah')->where('rumah_id', $id)->get();
        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $data->toArray()
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function pembayaran($id)
    {
        $data = HistoryPenghuni::with('penghuni', 'rumah')->where('rumah_id', $id)->get();
    
        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
                'success' => false
            ], 404);
        }
       $historyAktif = $data->filter(function ($item) {
            return $item->status === 1;
        });

        $penghuniIds = $historyAktif->pluck('penghuni_id')->toArray();
        $pembayaran = Pembayaran::with('penghuni')->whereIn('penghuni_id', $penghuniIds)->get();
        
        return response()->json([
            'message' => 'Berhasil menampilkan data pembayaran',
            'data' => $pembayaran->toArray()
        ], 200);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $historyPenghuni = HistoryPenghuni::where('rumah_id', $id)->whereNull('tanggal_selesai_hunian')->first();

            if ($historyPenghuni) {
                $historyPenghuni->update([
                    'tanggal_selesai_hunian' => now(),
                    'status' => '0'
                ]);
            }

            if ($request->penghuni) {
                $newHistoryPenghuni = new HistoryPenghuni();
                $newHistoryPenghuni->rumah_id = $id;
                $newHistoryPenghuni->penghuni_id = $request->penghuni;
                $newHistoryPenghuni->tanggal_mulai_hunian = now();
                $newHistoryPenghuni->tanggal_selesai_hunian = null;
                $newHistoryPenghuni->status = '1';
                $newHistoryPenghuni->save();
            }

            $rumah = Rumah::findOrFail($id);
            $rumah->status_hunian = $request->penghuni ? 'Dihuni' : 'Tidak dihuni';
            $rumah->save();

            return response()->json([
                'message' => 'Berhasil mengakhiri hunian',
                'data' => $historyPenghuni 
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal mengakhiri hunian',
                'data' => $th->getMessage()
            ], 500);
        }
    }

    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
