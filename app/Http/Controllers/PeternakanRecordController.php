<?php

namespace App\Http\Controllers;

use App\Models\PeternakanRecord;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

//ganti nama classnya
class PeternakanRecordController extends Controller
{

    //sesuaikan databasenya
    private const numericFields = [
        'daging_sapi',
        'daging_kambing',
        'daging_kuda',
        'daging_ayam_buras',
        'daging_ayam_ras_pedaging',
        'daging_itik',
        'telur_ayam_petelur',
        'telur_ayam_buras',
        'telur_itik',
        'telur_ayam_ras_petelur_rak',
        'telur_ayam_buras_rak',
        'telur_itik_rak'
    ];

    public function index(Request $request)
    {
        return Inertia::render('DinasPeternakan/InputData', [
            'records' => PeternakanRecord::orderByDesc('tahun')->orderByDesc('bulan')->get(),
            'filters' => [
                'tahun' => $request->integer('tahun'),
                'bulan' => $request->integer('bulan'),
            ],
        ]);
    }

    public function dashboard(Request $request)
    {
        //sesuaikan dengan datbase
        $META = [
            'daging_sapi'                => ['label' => 'Daging Sapi',                'unit' => 'Ton'],
            'daging_kambing'             => ['label' => 'Daging Kambing',             'unit' => 'Ton'],
            'daging_kuda'                => ['label' => 'Daging Kuda',                'unit' => 'Ton'],
            'daging_ayam_buras'          => ['label' => 'Daging Ayam Buras',          'unit' => 'Ton'],
            'daging_ayam_ras_pedaging'   => ['label' => 'Daging Ayam Ras Pedaging',   'unit' => 'Ton'],
            'daging_itik'                => ['label' => 'Daging Itik',                'unit' => 'Ton'],
            'telur_ayam_petelur'         => ['label' => 'Telur Ayam Petelur',         'unit' => 'Kg'],
            'telur_ayam_buras'           => ['label' => 'Telur Ayam Buras',           'unit' => 'Kg'],
            'telur_itik'                 => ['label' => 'Telur Itik',                 'unit' => 'Kg'],
            'telur_ayam_ras_petelur_rak' => ['label' => 'Telur Ayam Ras Petelur',     'unit' => 'Rak'],
            'telur_ayam_buras_rak'       => ['label' => 'Telur Ayam Buras',           'unit' => 'Rak'],
            'telur_itik_rak'             => ['label' => 'Telur Itik',                 'unit' => 'Rak'],
        ];
        $IND = collect($META)->mapWithKeys(fn($v, $k) => [$k => $v['label']])->all();
        $mKey = [1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'mei', 6 => 'jun', 7 => 'jul', 8 => 'ags', 9 => 'sep', 10 => 'okt', 11 => 'nov', 12 => 'des'];

        $allYears      = PeternakanRecord::select('tahun')->distinct()->orderBy('tahun')->pluck('tahun')->map(fn($y) => (int)$y)->values()->all();
        $allIndicators = array_keys($META);
        $allMonths     = range(1, 12);

        $hasInd  = $request->has('chart_indicator');
        $hasYear = $request->has('chart_year');

        $chartIndicator = $hasInd  ? $request->query('chart_indicator') : null;
        if ($hasInd && !array_key_exists($chartIndicator, $IND)) abort(400);

        $chartYear = $hasYear ? (int)$request->query('chart_year') : null;

        $chart = [];
        if ($hasInd && $hasYear) {
            $chartRecs = PeternakanRecord::where('tahun', $chartYear)
                ->orderBy('bulan')
                ->get(['bulan', $chartIndicator]);

            for ($b = 1; $b <= 12; $b++) {
                $rec = $chartRecs->firstWhere('bulan', $b);
                $chart[] = ['bulan' => $b, 'nilai' => $rec ? (float)$rec->{$chartIndicator} : 0];
            }
        }

        $selYears      = collect((array)$request->query('table_years', []))->map(fn($y) => (int)$y)->filter()->values()->all();
        $selIndicators = collect((array)$request->query('table_indicators', []))->filter(fn($k) => array_key_exists($k, $IND))->values()->all();
        $selMonths     = collect((array)$request->query('table_months', []))->map(fn($m) => (int)$m)->filter(fn($m) => $m >= 1 && $m <= 12)->unique()->sort()->values()->all();

        $yearsForTable = !empty($selYears) ? $selYears : $allYears;
        $indForTable   = !empty($selIndicators) ? $selIndicators : $allIndicators;

        $all = PeternakanRecord::whereIn('tahun', $yearsForTable)->orderBy('tahun')->orderBy('bulan')->get();

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

        return Inertia::render('DinasPeternakan/Dashboard', [
            'chartYear'         => $chartYear,
            'chartIndicator'    => $chartIndicator,
            'chartIndicatorLbl' => $chartIndicator ? $IND[$chartIndicator] : null,
            'chart'             => $chart,

            'tableYears'        => $selYears,
            'tableIndicators'   => collect($selIndicators)->map(fn($k) => ['key' => $k, 'label' => $IND[$k]])->values()->all(),
            'tableMonths'       => $selMonths,
            'tableRows'         => $rows,

            'indicators'        => collect($META)->map(fn($v, $k) => ['key' => $k, 'label' => $v['label'], 'unit' => $v['unit']])->values()->all(),
            'allYears'          => $allYears,
            'allMonths'         => $allMonths,
        ]);
    }

    public function data(Request $request)
    {
        $records = PeternakanRecord::orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get([
                //sesuaikan dengan database
                'id',
                'tahun',
                'bulan',
                'daging_sapi',
                'daging_kambing',
                'daging_kuda',
                'daging_ayam_buras',
                'daging_ayam_ras_pedaging',
                'daging_itik',
                'telur_ayam_petelur',
                'telur_ayam_buras',
                'telur_itik',
                'telur_ayam_ras_petelur_rak',
                'telur_ayam_buras_rak',
                'telur_itik_rak'
            ]);

        return Inertia::render('DinasPeternakan/Data', [
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

        $rec = PeternakanRecord::where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->first();

        return Inertia::render('DinasPeternakan/InputData', [
            'mode' => 'edit',
            'record' => $rec,
            'filters' => ['tahun' => $tahun, 'bulan' => $bulan],
        ]);
    }

    public function upsert(Request $request)
    {

        $rules = [
            'tahun' => 'required|integer|min:2000|max:2100',
            'bulan' => 'required|integer|min:1|max:12',
        ];
        foreach (self::numericFields as $f) {
            $rules[$f] = 'required|numeric|min:0';
        }

        $data = $request->validate($rules);

        foreach (self::numericFields as $f) {
            $data[$f] = (float) $data[$f];
        }

        $data['user_id'] = $request->user()->id;

        PeternakanRecord::updateOrCreate(
            ['tahun' => $data['tahun'], 'bulan' => $data['bulan']],
            $data
        );

        return redirect()
            ->route('peternakan.data', ['tahun' => $data['tahun'], 'bulan' => $data['bulan']])
            ->with('success', 'Data berhasil disimpan.');
    }

    public function store(Request $request)
    {

        $rules = [
            'tahun' => 'required|integer|min:2000|max:2100',
            'bulan' => 'required|integer|min:1|max:12',
        ];
        foreach (self::numericFields as $f) {
            $rules[$f] = 'required|numeric|min:0';
        }

        $data = $request->validate($rules);

        foreach (self::numericFields as $f) {
            $data[$f] = (float) $data[$f];
        }

        $data['user_id'] = $request->user()->id;

        PeternakanRecord::updateOrCreate(
            ['tahun' => $data['tahun'], 'bulan' => $data['bulan']],
            $data
        );

        return back()->with('success', 'Data berhasil disimpan.');
    }

    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $format = $request->query('format', 'csv');
        $tahun  = (int) $request->query('tahun');
        $bulan  = (int) $request->query('bulan');

        abort_if(!$tahun || !$bulan, 400, 'Parameter tahun/bulan wajib.');

        $rec = PeternakanRecord::where('tahun', $tahun)->where('bulan', $bulan)->first();

        //sesuaikan dengan database
        $indikator = [
            ['key' => 'daging_sapi',              'label' => 'Daging Sapi',              'unit' => 'Ton'],
            ['key' => 'daging_kambing',           'label' => 'Daging Kambing',           'unit' => 'Ton'],
            ['key' => 'daging_kuda',              'label' => 'Daging Kuda',              'unit' => 'Ton'],
            ['key' => 'daging_ayam_buras',        'label' => 'Daging Ayam Buras',        'unit' => 'Ton'],
            ['key' => 'daging_ayam_ras_pedaging', 'label' => 'Daging Ayam Ras Pedaging', 'unit' => 'Ton'],
            ['key' => 'daging_itik',              'label' => 'Daging Itik',              'unit' => 'Ton'],
            ['key' => 'telur_ayam_petelur',       'label' => 'Telur Ayam Petelur',       'unit' => 'Kg'],
            ['key' => 'telur_ayam_buras',         'label' => 'Telur Ayam Buras',         'unit' => 'Kg'],
            ['key' => 'telur_itik',               'label' => 'Telur Itik',               'unit' => 'Kg'],
            ['key' => 'telur_ayam_ras_petelur_rak', 'label' => 'Telur Ayam Ras Petelur', 'unit' => 'Rak'],
            ['key' => 'telur_ayam_buras_rak',     'label' => 'Telur Ayam Buras',         'unit' => 'Rak'],
            ['key' => 'telur_itik_rak',           'label' => 'Telur Itik',               'unit' => 'Rak'],
        ];

        $headers = ['Tahun', 'Bulan', 'Indikator',  'Satuan', 'Nilai'];
        $rows = [];

        foreach ($indikator as $row) {
            $val = 0;
            if ($rec && isset($rec->{$row['key']})) {
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

        $filenameBase = "peternakan_{$tahun}_" . str_pad($bulan, 2, '0', STR_PAD_LEFT);

        if ($format === 'xls') {
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

    public function destroy(PeternakanRecord $PeternakanRecord)
    {
        $this->authorize('delete', $PeternakanRecord);
        $PeternakanRecord->delete();
        return back();
    }
}
