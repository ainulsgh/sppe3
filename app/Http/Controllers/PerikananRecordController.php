<?php

namespace App\Http\Controllers;

use App\Models\PerikananRecord;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

<<<<<<< HEAD
//sesuiakn nama class
class PerikananRecordController extends Controller
{
    //sesuaikan databasenya
    private const numericFields = [
        'penangkapan_di_laut',
        'penangkapan_di_perairan_umum',
        'budidaya_laut_rumput_laut',
        'budidaya_tambak_rumput_laut',
        'budidaya_tambak_udang',
        'budidaya_tambak_bandeng',
        'budidaya_tambak_lainnya',
        'budidaya_kolam',
        'budidaya_sawah',
    ];

    public function index(Request $request)
    {
=======
class PerikananRecordController extends Controller
{
    public function index(Request $request)
    {
        // kirim semua records + optional filter untuk prefilling form
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        return Inertia::render('DinasPerikanan/InputData', [
            'records' => PerikananRecord::orderByDesc('tahun')->orderByDesc('bulan')->get(),
            'filters' => [
                'tahun' => $request->integer('tahun'),
                'bulan' => $request->integer('bulan'),
            ],
        ]);
    }

    public function dashboard(Request $request)
    {
<<<<<<< HEAD
        //sesaikan dengan yang ada didatabase
=======
        // ===== Master indikator =====
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        $IND = [
            'penangkapan_di_laut'           => 'Penangkapan di Laut',
            'penangkapan_di_perairan_umum'  => 'Penangkapan di Perairan Umum',
            'budidaya_laut_rumput_laut'     => 'Budidaya Laut (Rumput Laut)',
            'budidaya_tambak_rumput_laut'   => 'Budidaya Tambak (Rumput Laut)',
            'budidaya_tambak_udang'         => 'Budidaya Tambak (Udang)',
            'budidaya_tambak_bandeng'       => 'Budidaya Tambak (Bandeng)',
            'budidaya_tambak_lainnya'       => 'Budidaya Tambak (Ikan Lainnya)',
            'budidaya_kolam'                => 'Budidaya Kolam',
            'budidaya_sawah'                => 'Budidaya Sawah',
        ];
        $mKey = [1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'mei', 6 => 'jun', 7 => 'jul', 8 => 'ags', 9 => 'sep', 10 => 'okt', 11 => 'nov', 12 => 'des'];

<<<<<<< HEAD
        $allYears      = PerikananRecord::select('tahun')->distinct()->orderBy('tahun')->pluck('tahun')->map(fn($y) => (int)$y)->values()->all();
        $allIndicators = array_keys($IND);
        $allMonths     = range(1, 12);
        $hasYear = $request->has('chart_year');
        $hasInd  = $request->has('chart_indicator');
=======
        /* ===== Daftar master untuk "Semua" ===== */
        $allYears      = PerikananRecord::select('tahun')->distinct()->orderBy('tahun')->pluck('tahun')->map(fn($y) => (int)$y)->values()->all();
        $allIndicators = array_keys($IND);
        $allMonths     = range(1, 12);

        /* ===== Grafik: HANYA jika tahun & indikator ada ===== */
        $hasYear = $request->has('chart_year');
        $hasInd  = $request->has('chart_indicator');

>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        $chartYear      = $hasYear ? (int)$request->query('chart_year') : null;
        $chartIndicator = $hasInd  ? $request->query('chart_indicator') : null;

        if ($hasInd && !array_key_exists($chartIndicator, $IND)) {
            abort(400, 'Indikator tidak valid.');
        }

        $chart = [];
        if ($hasYear && $hasInd) {
            $chartRecs = PerikananRecord::where('tahun', $chartYear)
                ->orderBy('bulan')
                ->get(['bulan', $chartIndicator]);

            for ($b = 1; $b <= 12; $b++) {
                $rec = $chartRecs->firstWhere('bulan', $b);
                $chart[] = ['bulan' => $b, 'nilai' => $rec ? (float)$rec->{$chartIndicator} : 0];
            }
        }

<<<<<<< HEAD
        $selYears = collect((array)$request->query('table_years', []))
            ->map(fn($y) => (int)$y)->filter()->values()->all();
        $selIndicators = collect((array)$request->query('table_indicators', []))
            ->filter(fn($k) => array_key_exists($k, $IND))->values()->all();
=======
        /* ====== Filter TABEL (multi) ====== */
        $selYears = collect((array)$request->query('table_years', []))
            ->map(fn($y) => (int)$y)->filter()->values()->all();              // kosong = semua
        $selIndicators = collect((array)$request->query('table_indicators', []))
            ->filter(fn($k) => array_key_exists($k, $IND))->values()->all();   // kosong = semua
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        $selMonths = collect((array)$request->query('table_months', []))
            ->map(fn($m) => (int)$m)->filter(fn($m) => $m >= 1 && $m <= 12)->unique()->sort()->values()->all(); // kosong = semua

        $yearsForTable = !empty($selYears) ? $selYears : $allYears;
        $indForTable   = !empty($selIndicators) ? $selIndicators : $allIndicators;

        $all = PerikananRecord::whereIn('tahun', $yearsForTable)
            ->orderBy('tahun')->orderBy('bulan')->get();

        $rows = [];
        foreach ($yearsForTable as $y) {
            foreach ($indForTable as $ik) {
                $row = [
                    'indikator_key'   => $ik,
                    'indikator_label' => $IND[$ik],
                    'tahun'           => (int)$y,
                ];
                foreach ($allMonths as $b) {
                    $rec = $all->first(fn($r) => $r->tahun == $y && $r->bulan == $b);
                    $row[$mKey[$b]] = $rec ? (float)$rec->{$ik} : 0;
                }
                $rows[] = $row;
            }
        }

        return Inertia::render('DinasPerikanan/Dashboard', [
<<<<<<< HEAD
=======
            // Grafik (biarkan null/empty jika belum lengkap filternya)
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
            'chartYear'         => $chartYear,
            'chartIndicator'    => $chartIndicator,
            'chartIndicatorLbl' => $chartIndicator ? $IND[$chartIndicator] : null,
            'chart'             => $chart,
<<<<<<< HEAD
=======

            // Tabel (multi)
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
            'tableYears'        => $selYears,
            'tableIndicators'   => collect($selIndicators)->map(fn($k) => ['key' => $k, 'label' => $IND[$k]])->values()->all(),
            'tableMonths'       => $selMonths,
            'tableRows'         => $rows,
<<<<<<< HEAD
=======

            // master options
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
            'indicators'        => collect($IND)->map(fn($v, $k) => ['key' => $k, 'label' => $v])->values()->all(),
            'allYears'          => $allYears,
            'allMonths'         => $allMonths,
        ]);
    }

<<<<<<< HEAD
=======

>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
    public function data(Request $request)
    {
        $records = PerikananRecord::orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get([
<<<<<<< HEAD
                //sesuaikan dengan database
=======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
                'id',
                'tahun',
                'bulan',
                'penangkapan_di_laut',
                'penangkapan_di_perairan_umum',
                'budidaya_laut_rumput_laut',
                'budidaya_tambak_rumput_laut',
                'budidaya_tambak_udang',
                'budidaya_tambak_bandeng',
                'budidaya_tambak_lainnya',
                'budidaya_kolam',
                'budidaya_sawah',
            ]);

        return Inertia::render('DinasPerikanan/Data', [
            'records' => $records,
<<<<<<< HEAD
=======
            // ➜ penting: kirim filters agar Data.jsx auto set tahun/bulan
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
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

        $rec = PerikananRecord::where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->first();

<<<<<<< HEAD
        return Inertia::render('DinasPerikanan/InputData', [
            'mode' => 'edit',
            'record' => $rec,
=======
        // Render halaman yang sama dengan InputData, tapi mode=edit
        return Inertia::render('DinasPerikanan/InputData', [
            'mode' => 'edit',
            'record' => $rec, // bisa null kalau belum ada, akan diisi 0
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
            'filters' => ['tahun' => $tahun, 'bulan' => $bulan],
        ]);
    }

<<<<<<< HEAD
    public function upsert(Request $request)
    {
=======
    // App/Http/Controllers/PerikananRecordController.php

    public function upsert(Request $request)
    {
        // daftar field numerik agar mudah dipakai berulang
        $numericFields = [
            'penangkapan_di_laut',
            'penangkapan_di_perairan_umum',
            'budidaya_laut_rumput_laut',
            'budidaya_tambak_rumput_laut',
            'budidaya_tambak_udang',
            'budidaya_tambak_bandeng',
            'budidaya_tambak_lainnya',
            'budidaya_kolam',
            'budidaya_sawah',
        ];
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112

        $rules = [
            'tahun' => 'required|integer|min:2000|max:2100',
            'bulan' => 'required|integer|min:1|max:12',
        ];
<<<<<<< HEAD
        foreach (self::numericFields as $f) {
=======
        foreach ($numericFields as $f) {
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
            $rules[$f] = 'required|numeric|min:0';
        }

        $data = $request->validate($rules);

<<<<<<< HEAD
        foreach (self::numericFields as $f) {
=======
        // pastikan desimal: cast ke float (atau bisa round)
        foreach ($numericFields as $f) {
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
            $data[$f] = (float) $data[$f];
        }

        $data['user_id'] = $request->user()->id;

<<<<<<< HEAD
=======
        // MENIMPA jika tahun+bulan sudah ada
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        PerikananRecord::updateOrCreate(
            ['tahun' => $data['tahun'], 'bulan' => $data['bulan']],
            $data
        );

        return redirect()
            ->route('perikanan.data', ['tahun' => $data['tahun'], 'bulan' => $data['bulan']])
            ->with('success', 'Data berhasil disimpan.');
    }

    public function store(Request $request)
    {
<<<<<<< HEAD
=======
        $numericFields = [
            'penangkapan_di_laut',
            'penangkapan_di_perairan_umum',
            'budidaya_laut_rumput_laut',
            'budidaya_tambak_rumput_laut',
            'budidaya_tambak_udang',
            'budidaya_tambak_bandeng',
            'budidaya_tambak_lainnya',
            'budidaya_kolam',
            'budidaya_sawah',
        ];

>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        $rules = [
            'tahun' => 'required|integer|min:2000|max:2100',
            'bulan' => 'required|integer|min:1|max:12',
        ];
<<<<<<< HEAD
        foreach (self::numericFields as $f) {
=======
        foreach ($numericFields as $f) {
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
            $rules[$f] = 'required|numeric|min:0';
        }

        $data = $request->validate($rules);

<<<<<<< HEAD
        foreach (self::numericFields as $f) {
=======
        foreach ($numericFields as $f) {
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
            $data[$f] = (float) $data[$f];
        }

        $data['user_id'] = $request->user()->id;

        PerikananRecord::updateOrCreate(
            ['tahun' => $data['tahun'], 'bulan' => $data['bulan']],
            $data
        );

<<<<<<< HEAD
=======
        // ✅ 303 agar flash terbaca konsisten oleh Inertia
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        return redirect()
            ->back(303)
            ->with('success', 'Data berhasil disimpan.');
    }


    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
<<<<<<< HEAD
        $format = $request->query('format', 'csv');
=======
        $format = $request->query('format', 'csv'); // csv atau xls
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        $tahun  = (int) $request->query('tahun');
        $bulan  = (int) $request->query('bulan');

        abort_if(!$tahun || !$bulan, 400, 'Parameter tahun/bulan wajib.');

        $rec = PerikananRecord::where('tahun', $tahun)->where('bulan', $bulan)->first();

<<<<<<< HEAD
        //sesuiakn dengan database
=======
        // Mapping indikator + label + satuan
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        $indikator = [
            ['key' => 'penangkapan_di_laut',           'label' => 'Penangkapan di laut',             'unit' => 'Ton'],
            ['key' => 'penangkapan_di_perairan_umum',  'label' => 'Penangkapan di perairan umum',    'unit' => 'Ton'],
            ['key' => 'budidaya_laut_rumput_laut',     'label' => 'Budidaya laut (rumput laut)',     'unit' => 'Ton'],
            ['key' => 'budidaya_tambak_rumput_laut',   'label' => 'Budidaya tambak (rumput laut)',   'unit' => 'Ton'],
            ['key' => 'budidaya_tambak_udang',         'label' => 'Budidaya tambak (udang)',         'unit' => 'Ton'],
            ['key' => 'budidaya_tambak_bandeng',       'label' => 'Budidaya tambak (bandeng)',       'unit' => 'Ton'],
            ['key' => 'budidaya_tambak_lainnya',       'label' => 'Budidaya tambak (ikan lainnya)',  'unit' => 'Ton'],
            ['key' => 'budidaya_kolam',                'label' => 'Budidaya kolam',                  'unit' => 'Ton'],
            ['key' => 'budidaya_sawah',                'label' => 'Budidaya sawah',                  'unit' => 'Ton'],
        ];

<<<<<<< HEAD
=======
        // Header & rows (long format)
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        $headers = ['Tahun', 'Bulan', 'Indikator',  'Satuan', 'Nilai'];
        $rows = [];

        foreach ($indikator as $row) {
            $val = 0;
            if ($rec && isset($rec->{$row['key']})) {
<<<<<<< HEAD
=======
                // Nilai di DB bisa float (lihat upsert/store yang cast ke float)
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
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

        $filenameBase = "perikanan_{$tahun}_" . str_pad($bulan, 2, '0', STR_PAD_LEFT);

        if ($format === 'xls') {
<<<<<<< HEAD
=======
            // Excel-compatible (HTML table)
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
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

<<<<<<< HEAD
=======
        // Default CSV
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
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

    public function destroy(PerikananRecord $perikananRecord)
    {
        $this->authorize('delete', $perikananRecord);
        $perikananRecord->delete();
        return back();
    }
}
