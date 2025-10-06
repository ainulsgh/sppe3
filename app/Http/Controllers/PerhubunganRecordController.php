<?php

namespace App\Http\Controllers;

use App\Models\PerhubunganRecord;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PerhubunganRecordController extends Controller
{
    public function index(Request $request)
    {
        // kirim semua records + optional filter untuk prefilling form
        return Inertia::render('DinasPerhubungan/InputData', [
            'records' => PerhubunganRecord::orderByDesc('tahun')->orderByDesc('bulan')->get(),
            'filters' => [
                'tahun' => $request->integer('tahun'),
                'bulan' => $request->integer('bulan'),
            ],
        ]);
    }

    public function dashboard(Request $request)
    {
        // ===== Master indikator + unit =====
        $META = [
            'retribusi_truk'               => ['label' => 'Retribusi Truk',                'unit' => 'Rupiah'],
            'retribusi_pick_up'            => ['label' => 'Retribusi Pick Up',             'unit' => 'Rupiah'],
            'retribusi_parkir_motor'       => ['label' => 'Retribusi Parkir Motor',                'unit' => 'Rupiah'],
            'retribusi_parkir_angkot'      => ['label' => 'Retribusi Parkir Angkot',          'unit' => 'Rupiah'],
        ];
        $IND = collect($META)->mapWithKeys(fn($v, $k) => [$k => $v['label']])->all();
        $mKey = [1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'mei', 6 => 'jun', 7 => 'jul', 8 => 'ags', 9 => 'sep', 10 => 'okt', 11 => 'nov', 12 => 'des'];

        // ===== Master untuk "Semua" =====
        $allYears      = PerhubunganRecord::select('tahun')->distinct()->orderBy('tahun')->pluck('tahun')->map(fn($y) => (int)$y)->values()->all();
        $allIndicators = array_keys($META);
        $allMonths     = range(1, 12);

        /* ===== Grafik: hanya hitung jika KEDUA param ada ===== */
        $hasInd  = $request->has('chart_indicator');
        $hasYear = $request->has('chart_year');

        $chartIndicator = $hasInd  ? $request->query('chart_indicator') : null;
        if ($hasInd && !array_key_exists($chartIndicator, $IND)) abort(400);

        $chartYear = $hasYear ? (int)$request->query('chart_year') : null;

        $chart = [];
        if ($hasInd && $hasYear) {
            $chartRecs = PerhubunganRecord::where('tahun', $chartYear)
                ->orderBy('bulan')
                ->get(['bulan', $chartIndicator]);

            for ($b = 1; $b <= 12; $b++) {
                $rec = $chartRecs->firstWhere('bulan', $b);
                $chart[] = ['bulan' => $b, 'nilai' => $rec ? (float)$rec->{$chartIndicator} : 0];
            }
        }

        /* ====== Filter TABEL (multi) ====== */
        $selYears      = collect((array)$request->query('table_years', []))->map(fn($y) => (int)$y)->filter()->values()->all();
        $selIndicators = collect((array)$request->query('table_indicators', []))->filter(fn($k) => array_key_exists($k, $IND))->values()->all();
        $selMonths     = collect((array)$request->query('table_months', []))->map(fn($m) => (int)$m)->filter(fn($m) => $m >= 1 && $m <= 12)->unique()->sort()->values()->all();

        $yearsForTable = !empty($selYears) ? $selYears : $allYears;
        $indForTable   = !empty($selIndicators) ? $selIndicators : $allIndicators;

        $all = PerhubunganRecord::whereIn('tahun', $yearsForTable)->orderBy('tahun')->orderBy('bulan')->get();

        $rows = [];
        foreach ($yearsForTable as $y) {
            foreach ($indForTable as $ik) {
                $row = ['indikator_key' => $ik, 'indikator_label' => $IND[$ik], 'tahun' => (int)$y];
                foreach ($allMonths as $b) {
                    $rec = $all->first(fn($r) => $r->tahun == $y && $r->bulan == $b);
                    $row[$mKey[$b]] = $rec ? (float)$rec->{$ik} : 0;
                }
                $rows[] = $row;
            }
        }

        return Inertia::render('DinasPerhubungan/Dashboard', [
            // Grafik (biarkan null kalau belum dipilih)
            'chartYear'         => $chartYear,
            'chartIndicator'    => $chartIndicator,
            'chartIndicatorLbl' => $chartIndicator ? $IND[$chartIndicator] : null,
            'chart'             => $chart,

            // Tabel (multi)
            'tableYears'        => $selYears,
            'tableIndicators'   => collect($selIndicators)->map(fn($k) => ['key' => $k, 'label' => $IND[$k]])->values()->all(),
            'tableMonths'       => $selMonths,
            'tableRows'         => $rows,

            // master options (kirim SEKALIGUS unit)
            'indicators'        => collect($META)->map(fn($v, $k) => ['key' => $k, 'label' => $v['label'], 'unit' => $v['unit']])->values()->all(),
            'allYears'          => $allYears,
            'allMonths'         => $allMonths,
        ]);
    }

    public function data(Request $request)
    {
        $records = PerhubunganRecord::orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get([
                'id',
                'tahun',
                'bulan',
                'retribusi_truk',
                'retribusi_pick_up',
                'retribusi_parkir_motor',
                'retribusi_parkir_angkot',
            ]);

        return Inertia::render('DinasPerhubungan/Data', [
            'records' => $records,
            // ➜ penting: kirim filters agar Data.jsx auto set tahun/bulan
            'filters' => [
                'tahun' => $request->integer('tahun'),
                'bulan' => $request->integer('bulan'),
            ],
        ]);
    }


    public function edit(Request $request)
    {
        $tahun = $request->integer('tahun');
        $bulan = $request->integer('bulan');

        abort_if(!$tahun || !$bulan, 400, 'Parameter tahun/bulan wajib.');

        $rec = PerhubunganRecord::where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->first();

        // Render halaman yang sama dengan InputData, tapi mode=edit
        return Inertia::render('DinasPerhubungan/InputData', [
            'mode' => 'edit',
            'record' => $rec, // bisa null kalau belum ada, akan diisi 0
            'filters' => ['tahun' => $tahun, 'bulan' => $bulan],
        ]);
    }

    public function upsert(Request $request)
    {
        // daftar field numerik agar mudah dipakai berulang
        $numericFields = [
            'retribusi_truk',
            'retribusi_pick_up',
            'retribusi_parkir_motor',
            'retribusi_parkir_angkot',
        ];

        $rules = [
            'tahun' => 'required|integer|min:2000|max:2100',
            'bulan' => 'required|integer|min:1|max:12',
        ];
        foreach ($numericFields as $f) {
            $rules[$f] = 'required|numeric|min:0';
        }

        $data = $request->validate($rules);

        // pastikan desimal: cast ke float (atau bisa round)
        foreach ($numericFields as $f) {
            $data[$f] = (float) $data[$f];
        }

        $data['user_id'] = $request->user()->id;

        // MENIMPA jika tahun+bulan sudah ada
        PerhubunganRecord::updateOrCreate(
            ['tahun' => $data['tahun'], 'bulan' => $data['bulan']],
            $data
        );

        return redirect()
<<<<<<< HEAD
            ->route('perhubungan.data', ['tahun' => $data['tahun'], 'bulan' => $data['bulan']])
=======
            ->route('Perhubungan.data', ['tahun' => $data['tahun'], 'bulan' => $data['bulan']])
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
            ->with('success', 'Data berhasil disimpan.');
    }

    public function store(Request $request)
    {
<<<<<<< HEAD
=======
        // gunakan rules & numericFields yang sama
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        $numericFields = [
            'retribusi_truk',
            'retribusi_pick_up',
            'retribusi_parkir_motor',
            'retribusi_parkir_angkot',
        ];

        $rules = [
            'tahun' => 'required|integer|min:2000|max:2100',
            'bulan' => 'required|integer|min:1|max:12',
        ];
        foreach ($numericFields as $f) {
            $rules[$f] = 'required|numeric|min:0';
        }

        $data = $request->validate($rules);

        foreach ($numericFields as $f) {
            $data[$f] = (float) $data[$f];
        }

        $data['user_id'] = $request->user()->id;

<<<<<<< HEAD
=======
        // PENTING: pakai updateOrCreate agar MENIMPA jika (tahun, bulan) sudah ada
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        PerhubunganRecord::updateOrCreate(
            ['tahun' => $data['tahun'], 'bulan' => $data['bulan']],
            $data
        );

<<<<<<< HEAD
        // ✅ 303 agar flash terbaca konsisten oleh Inertia
        return redirect()
            ->back(303)
            ->with('success', 'Data berhasil disimpan.');
=======
        return back()->with('success', 'Data berhasil disimpan.');
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
    }

    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $format = $request->query('format', 'csv'); // csv atau xls
        $tahun  = (int) $request->query('tahun');
        $bulan  = (int) $request->query('bulan');

        abort_if(!$tahun || !$bulan, 400, 'Parameter tahun/bulan wajib.');

        $rec = PerhubunganRecord::where('tahun', $tahun)->where('bulan', $bulan)->first();

        // Mapping indikator + label + satuan
        $indikator = [
            ['key' => 'retribusi_truk',              'label' => 'Retribusi Truk',              'unit' => 'Rupiah'],
            ['key' => 'retribusi_pick_up',          'label' => 'Retribusi Pick Up',           'unit' => 'Rupiah'],
            ['key' => 'retribusi_parkir_motor',             'label' => 'Retribusi Parkir Motor',              'unit' => 'Rupiah'],
            ['key' => 'retribusi_parkir_angkot',       'label' => 'Retribusi Parkir Angkot',        'unit' => 'Rupiah'],
        ];

        // Header & rows (long format)
        $headers = ['Tahun', 'Bulan', 'Indikator',  'Satuan', 'Nilai'];
        $rows = [];

        foreach ($indikator as $row) {
            $val = 0;
            if ($rec && isset($rec->{$row['key']})) {
                // Nilai di DB bisa float (lihat upsert/store yang cast ke float)
                $val = (float) $rec->{$row['key']};
            }
            $rows[] = [
                $tahun,
                $bulan,
                $row['label'],
                $row['unit'],
                $val,
            ];
        }

        $filenameBase = "Perhubungan_{$tahun}_" . str_pad($bulan, 2, '0', STR_PAD_LEFT);

        if ($format === 'xls') {
            // Excel-compatible (HTML table)
            $filename = $filenameBase . '.xls';
            $contentType = 'application/vnd.ms-excel';

            return response()->streamDownload(function () use ($headers, $rows) {
                echo '<table border="1"><thead><tr>';
                foreach ($headers as $h) echo '<th>' . htmlspecialchars($h) . '</th>';
                echo '</tr></thead><tbody>';
                foreach ($rows as $r) {
                    echo '<tr>';
                    foreach ($r as $v) echo '<td>' . htmlspecialchars((string)$v) . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            }, $filename, [
                'Content-Type' => $contentType,
                'Cache-Control' => 'no-store, no-cache',
            ]);
        }

        // Default CSV
        $filename = $filenameBase . '.csv';
        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, $headers);
            foreach ($rows as $r) fputcsv($out, $r);
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    public function destroy(PerhubunganRecord $PerhubunganRecord)
    {
        $this->authorize('delete', $PerhubunganRecord);
        $PerhubunganRecord->delete();
        return back();
    }
}
