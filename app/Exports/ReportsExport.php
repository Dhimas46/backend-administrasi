<?php
namespace App\Exports;

use App\Models\Pengeluaran;
use App\Models\Pembayaran;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $month;
    protected $year;

    public function __construct($month = null, $year = null)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        if ($this->month && $this->year) {
            $date = Carbon::createFromFormat('m', $this->month)->startOfMonth();
            $monthYear = $date->isoFormat('MMMM YYYY');

            $pengeluaran = Pengeluaran::selectRaw("SUM(jumlah_pengeluaran) as total, DATE_FORMAT(tanggal_pengeluaran, '%M %Y') as month_year")
                ->whereMonth('tanggal_pengeluaran', $this->month)
                ->whereYear('tanggal_pengeluaran', $this->year)
                ->groupBy('month_year')
                ->get()
                ->toArray();

            $pengeluaran = collect($pengeluaran)->keyBy('month_year');

            $pemasukan = Pembayaran::selectRaw("SUM(jumlah_pembayaran) as total, DATE_FORMAT(tanggal_pembayaran, '%M %Y') as month_year")
                ->whereMonth('tanggal_pembayaran', $this->month)
                ->whereYear('tanggal_pembayaran', $this->year)
                ->groupBy('month_year')
                ->get()
                ->toArray();

            $pemasukan = collect($pemasukan)->keyBy('month_year');
        } else {
            $pengeluaran = Pengeluaran::selectRaw("SUM(jumlah_pengeluaran) as total, DATE_FORMAT(tanggal_pengeluaran, '%M %Y') as month_year")
                ->groupBy('month_year') 
                ->get()
                ->toArray();

            $pengeluaran = collect($pengeluaran)->keyBy('month_year');

            $pemasukan = Pembayaran::selectRaw("SUM(jumlah_pembayaran) as total, DATE_FORMAT(tanggal_pembayaran, '%M %Y') as month_year")
                ->groupBy('month_year') 
                ->get()
                ->toArray();

            $pemasukan = collect($pemasukan)->keyBy('month_year');
        }
        
        $saldoAkhir = [];

       
        $combinedData = $pemasukan->merge($pengeluaran);
        $totalSaldoAkhirPerBulan = [];


        $sumPemasukan = [];
        $sumPengeeluaran = [];

        foreach ($combinedData as $key => $data) {
            $totalPemasukan = $pemasukan->has($key) ? $pemasukan[$key]['total'] : 0;
            $totalPengeluaran = $pengeluaran->has($key) ? $pengeluaran[$key]['total'] : 0;
            $saldoAkhir[$key] = $totalPemasukan - $totalPengeluaran;
            $totalSaldoAkhirPerBulan[] = $saldoAkhir;
            $sumPemasukan[] = $totalPemasukan;
            $sumPengeeluaran[] = $totalPengeluaran;  
        }
        $saldoTotal = array_sum($sumPemasukan) - array_sum($sumPengeeluaran);
       
        
        return collect($saldoAkhir)->map(function ($saldo, $key) use ($pengeluaran, $pemasukan) {
            return [
                'Month' => $key ?: 'All',
                'Total Pengeluaran' => $pengeluaran[$key]['total'] ?? '0',
                'Total Pemasukan' => $pemasukan[$key]['total'] ?? '0',
                'Saldo' => $saldo,
            ];
        })->push([
            'Month' => 'Saldo Total',
            'Total Pengeluaran' => '',
            'Total Pemasukan' => '',
            'Saldo Akhir' => $saldoTotal
        ]);
    }

    public function headings(): array
    {
        return [
            'Month',
            'Total Pengeluaran',
            'Total Pemasukan',
            'Saldo Akhir',
        ];
    }
  
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Membuat baris pertama (judul) menjadi tebal
            'A1:D1' => ['font' => ['bold' => true]], // Membuat judul kolom menjadi tebal
            'A2:D' . ($sheet->getHighestRow()) => ['border' => 'thin'], // Menambahkan border tipis ke seluruh sel data
        ];
    }
}
