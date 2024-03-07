<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;
use Illuminate\Support\Facades\Storage;

class SummaryController extends Controller
{
    public function chart()
    {
        $pengeluaran = Pengeluaran::get()->groupBy(function ($date) {
            return Carbon::parse($date->tanggal_pengeluaran)->format('Y-m');
        });
    
        $pembayaran = Pembayaran::where('status_pembayaran', 'Lunas')->get()->groupBy(function ($date) {
            return Carbon::parse($date->tanggal_pembayaran)->format('Y-m');
        });
    
        $totalPengeluaran = [];
        $totalPembayaran = [];
    
        foreach ($pengeluaran as $tanggal => $data) {
            $tahun = Carbon::parse($tanggal)->year;
            $bulan = Carbon::parse($tanggal)->month;
            $totalPengeluaran[$tahun][$bulan] = $data->sum('jumlah_pengeluaran');
        }
    
        foreach ($pembayaran as $tanggal => $data) {
            $tahun = Carbon::parse($tanggal)->year;
            $bulan = Carbon::parse($tanggal)->month;
            $totalPembayaran[$tahun][$bulan] = $data->sum('jumlah_pembayaran');
        }
    
        $saldo = 0;
    
        foreach ($totalPembayaran as $tahun => $pembayaranPerTahun) {
            foreach ($pembayaranPerTahun as $bulan => $jumlahPembayaran) {
                $saldo += $jumlahPembayaran;
            }
        }
    
        foreach ($totalPengeluaran as $tahun => $pengeluaranPerTahun) {
            foreach ($pengeluaranPerTahun as $bulan => $jumlahPengeluaran) {
                $saldo -= $jumlahPengeluaran;
            }
        }
    
        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => [
                'totalPengeluaran' => $totalPengeluaran,
                'totalPembayaran' => $totalPembayaran
            ],
            'saldo' => $saldo,
            'sumPengeluaran' => array_sum(array_map('array_sum', $totalPengeluaran)),
            'sumPembayaran' => array_sum(array_map('array_sum', $totalPembayaran))
        ]);
    }

    public function export(Request $request) 
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $exportFileName = 'summary.xlsx';
        $exportPath = 'storage/uploads/export/' . $exportFileName;
        
        Excel::store(new ReportsExport($month, $year), $exportPath, 'public');
        
        $filePath = public_path($exportPath);
        
        if (!file_exists($filePath)) {
            throw new \Exception("The file '$exportFileName' does not exist.");
        }
        
        return response()->download($filePath, $exportFileName);
    }
    
    
    
    
    

}
