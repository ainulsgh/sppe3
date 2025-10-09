<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use App\Models\PerikananRecord;
use App\Models\PertanianRecord;
use App\Models\PeternakanRecord;
use App\Models\PerhubunganRecord;
use App\Models\DpmptspRecord;
use Maatwebsite\Excel\Facades\Excel;
use App\Export\Export;

class AdminController extends Controller
{
    private function offices(): array
    {
        return [
            'perikanan' => [
                'label' => 'Dinas Kelautan dan Perikanan',
                'model' => PerikananRecord::class,
                'indicators' => [
                    'penangkapan_di_laut'            => 'Penangkapan di Laut',
                    'penangkapan_di_perairan_umum'   => 'Penangkapan di Perairan Umum',
                    'budidaya_laut_rumput_laut'      => 'Budidaya Laut (Rumput Laut)',
                    'budidaya_tambak_rumput_laut'    => 'Budidaya Tambak (Rumput Laut)',
                    'budidaya_tambak_udang'          => 'Budidaya Tambak (Udang)',
                    'budidaya_tambak_bandeng'        => 'Budidaya Tambak (Bandeng)',
                    'budidaya_tambak_lainnya'        => 'Budidaya Tambak (Ikan Lainnya)',
                    'budidaya_kolam'                 => 'Budidaya Kolam',
                    'budidaya_sawah'                 => 'Budidaya Sawah',
                ],
                'unit' => 'Ton',
            ],
            'peternakan' => [
                'label' => 'Dinas Peternakan dan Kesehatan Hewan',
                'model' => PeternakanRecord::class,
                'indicators' => [
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
                ],
                'unit' => '',
            ],
            'perhubungan' => [
                'label' => 'Dinas Perhubungan',
                'model' => PerhubunganRecord::class,
                'indicators' => [
                    'retribusi_truk'               => ['label' => 'Retribusi Truk',                'unit' => 'Rupiah'],
                    'retribusi_pick_up'            => ['label' => 'Retribusi Pick Up',             'unit' => 'Rupiah'],
                    'retribusi_parkir_motor'       => ['label' => 'Retribusi Parkir Motor',        'unit' => 'Rupiah'],
                    'retribusi_parkir_angkot'      => ['label' => 'Retribusi Parkir Angkot',       'unit' => 'Rupiah'],
                ],
                'unit' => '',
            ],
            'dpmptsp' => [
                'label' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu',
                'model' => DpmptspRecord::class,
                'indicators' => [
                    'pbg'               => ['label' => 'Persetujuan Bangunan Gedung',                'unit' => 'Unit'],
                ],
                'unit' => '',
            ],
            'dinas pertanian' => [
                'label' => 'Dinas Pertanian',
                'model' => PertanianRecord::class,
                'indicators' => [
                    'produksi_padi'               => ['label' => 'Produksi Padi',                'unit' => 'Ton'],
                ],
                'unit' => '',
            ],
            'dinas pariwisata' => [
                'label' => 'Dinas Pariwisata',
                'model' => PariwisataRecord::class,
                'indicators' => [
                    'jumlah_kunjungan_wisata'        => ['label' => 'Jumlah Kunjungan Wisata',         'unit' => 'Orang'],
                    'pad_objek_wisata'               => ['label' => 'PAD Objek Wisata',                'unit' => 'Juta Rupiah'],
                ],
                'unit' => '',
            ],
            //tambahkan dinas baru sesuai dengan role
        ];
    }

    private array $MONTH_KEYS = [1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'mei', 6 => 'jun', 7 => 'jul', 8 => 'ags', 9 => 'sep', 10 => 'okt', 11 => 'nov', 12 => 'des'];

    private function getOffice(string $key): array
    {
        $all = $this->offices();
        abort_unless(array_key_exists($key, $all), 400, 'Dinas tidak dikenal.');
        return $all[$key];
    }

    private function indicatorLabel(array $indicators, string $key): string
    {
        $v = $indicators[$key] ?? $key;
        return is_array($v) ? ($v['label'] ?? $key) : (string)$v;
    }

    private function indicatorUnit(array $meta, string $key): string
    {
        $v = $meta['indicators'][$key] ?? null;
        if (is_array($v) && isset($v['unit'])) return (string)$v['unit'];
        return $meta['unit'] ?? '';
    }

    public function dashboard(Request $request)
    {
        $DINAS = $this->offices();
        $chartOffice = $request->query('chart_office');
        if ($chartOffice && !array_key_exists($chartOffice, $DINAS)) {
            abort(400, 'Dinas tidak dikenal.');
        }
        $chartYear = $request->query('chart_year');
        $chartYear = $chartYear !== null ? (int)$chartYear : null;
        $chartIndicator = $request->query('chart_indicator');
        $chartYear = $request->query('chart_year');
        $chartYear = $chartYear !== null ? (int)$chartYear : null;
        $chartIndicator = $request->query('chart_indicator');
        $chart = [];
        for ($b = 1; $b <= 12; $b++) {
            $chart[] = ['bulan' => $b, 'nilai' => 0];
        }
        if (
            $chartOffice &&
            $chartYear &&
            $chartIndicator &&
            isset($DINAS[$chartOffice]['indicators'][$chartIndicator])
        ) {
            $M = $DINAS[$chartOffice]['model'];
            $recs = $M::where('tahun', $chartYear)->orderBy('bulan')->get(['bulan', $chartIndicator]);
            $chart = [];
            for ($b = 1; $b <= 12; $b++) {
                $rec = $recs->firstWhere('bulan', $b);
                $chart[] = ['bulan' => $b, 'nilai' => $rec ? (int)$rec->{$chartIndicator} : 0];
            }
        }

        $yearsPerOffice = [];
        foreach ($DINAS as $k => $m) {
            $yearsPerOffice[$k] = ($m['model'])::select('tahun')->distinct()->orderBy('tahun')
                ->pluck('tahun')->map(fn($y) => (int)$y)->values()->all();
        }

        $allMonths = range(1, 12);
        $selOffices = collect((array)$request->query('table_offices', []))
            ->filter(fn($k) => array_key_exists($k, $DINAS))->values()->all();
        $selYears = collect((array)$request->query('table_years', []))
            ->map(fn($y) => (int)$y)->filter()->values()->all();
        $selIndicators = collect((array)$request->query('table_indicators', []))->values()->all();
        $selMonths = collect((array)$request->query('table_months', []))
            ->map(fn($m) => (int)$m)->filter(fn($m) => $m >= 1 && $m <= 12)->unique()->sort()->values()->all();

        $officesForTable = !empty($selOffices) ? $selOffices : array_keys($DINAS);

        $rows = [];
        foreach ($officesForTable as $office) {
            $meta = $DINAS[$office];
            $model = $meta['model'];
            $indMap = $meta['indicators'];

            $years = !empty($selYears) ? $selYears : ($yearsPerOffice[$office] ?? []);
            if (empty($years)) continue;

            $inds = !empty($selIndicators)
                ? array_values(array_filter(array_map(function ($v) use ($office) {
                    if (str_contains($v, ':')) {
                        [$o, $k] = explode(':', $v, 2);
                        return $o === $office ? $k : null;
                    }
                    return $v;
                }, $selIndicators)))
                : array_keys($indMap);

            $inds = array_values(array_intersect($inds, array_keys($indMap)));
            if (empty($inds)) continue;

            $all = $model::whereIn('tahun', $years)->orderBy('tahun')->orderBy('bulan')->get();

            foreach ($years as $y) {
                foreach ($inds as $ik) {
                    $label = $this->indicatorLabel($indMap, $ik);
                    $row = [
                        'dinas_key'       => $office,
                        'dinas_label'     => $meta['label'],
                        'indikator_key'   => $ik,
                        'indikator_label' => $label,
                        'tahun'           => (int)$y,
                    ];
                    foreach ($allMonths as $b) {
                        $rec = $all->first(fn($r) => $r->tahun == $y && $r->bulan == $b);
                        $row[$this->MONTH_KEYS[$b]] = $rec ? (int)$rec->{$ik} : 0;
                    }
                    $rows[] = $row;
                }
            }
        }

        $officeOptions = collect($DINAS)->map(fn($m, $k) => ['key' => $k, 'label' => $m['label']])->values()->all();
        $indicatorOptionsForChart = collect($DINAS[$chartOffice]['indicators'] ?? [])
            ->map(fn($v, $k) => ['key' => $k, 'label' => is_array($v) ? ($v['label'] ?? $k) : (string)$v])
            ->values()->all();

        $indicatorOptionsForTable = [];
        foreach ($DINAS as $office => $meta) {
            foreach ($meta['indicators'] as $k => $v) {
                $label = is_array($v) ? ($v['label'] ?? $k) : (string)$v;
                $indicatorOptionsForTable[] = [
                    'office'  => $office,
                    'key'     => $k,
                    'label'   => $label,
                    'display' => "{$meta['label']} • {$label}",
                ];
            }
        }

        return Inertia::render('Admin/Dashboard', [
            'chartOffice'       => $chartOffice,
            'chartYear'         => $chartYear,
            'chartIndicator'    => $chartIndicator,
            'chartIndicatorLbl' => '',
            'chart'             => $chart,
            'tableRows'         => $rows,
            'tableOffices'      => $selOffices,
            'tableYears'        => $selYears,
            'tableIndicators'   => $selIndicators,
            'tableMonths'       => $selMonths,
            'offices'                   => $officeOptions,
            'yearsPerOffice'            => $yearsPerOffice,
            'indicatorOptionsForChart'  => $indicatorOptionsForChart,
            'indicatorOptionsForTable'  => $indicatorOptionsForTable,
            'allMonths'                 => $allMonths,
        ]);
    }

    public function save(Request $request)
    {
        $DINAS = $this->offices();

        $office = $request->string('office')->toString();
        abort_unless(array_key_exists($office, $DINAS), 422, 'Dinas tidak valid.');

        $rules = [
            'office' => ['required', Rule::in(array_keys($DINAS))],
            'tahun'  => ['required', 'integer', 'min:2000', 'max:2100'],
            'bulan'  => ['required', 'integer', 'min:1', 'max:12'],
        ];
        foreach (array_keys($DINAS[$office]['indicators']) as $key) {
            $rules[$key] = ['nullable', 'integer', 'min:0'];
        }

        $data = $request->validate($rules);

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $DINAS[$office]['model'];
        $rec = $model::firstOrNew(['tahun' => $data['tahun'], 'bulan' => $data['bulan']]);

        if (Arr::has($rec->getAttributes(), 'user_id') || in_array('user_id', $rec->getFillable(), true)) {
            $rec->user_id = $request->user()->id;
        }
        foreach (array_keys($DINAS[$office]['indicators']) as $key) {
            if ($request->has($key)) {
                $rec->{$key} = (int) ($request->input($key) ?? 0);
            }
        }

        $rec->save();

        return back()->with('success', 'Data berhasil disimpan.');
    }

    public function export(Request $request)
    {
        $DINAS = $this->offices();
        $MONTH_KEYS = [1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'mei', 6 => 'jun', 7 => 'jul', 8 => 'ags', 9 => 'sep', 10 => 'okt', 11 => 'nov', 12 => 'des'];
        $MONTH_LABELS = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        $QUARTERS = [1 => [1, 2, 3], 2 => [4, 5, 6], 3 => [7, 8, 9], 4 => [10, 11, 12]];

        // ==== Selected ====
        $selOffices  = collect((array)$request->query('offices', []))
            ->filter(fn($k) => array_key_exists($k, $DINAS))->values()->all();
        $selYears    = collect((array)$request->query('years', []))->map(fn($y) => (int)$y)->filter()->values()->all();
        $selMonths   = collect((array)$request->query('months', []))->map(fn($m) => (int)$m)->filter(fn($m) => $m >= 1 && $m <= 12)->unique()->sort()->values()->all();
        $selQuarters = collect((array)$request->query('quarters', []))->map(fn($q) => (int)$q)->filter(fn($q) => in_array($q, [1, 2, 3, 4], true))->unique()->sort()->values()->all();

        $officesFor  = !empty($selOffices) ? $selOffices : array_keys($DINAS);
        $qMonths = collect($selQuarters)->flatMap(fn($q) => $QUARTERS[$q] ?? [])->unique()->sort()->values()->all();
        $monthsFor = !empty($selMonths) || !empty($qMonths)
            ? collect($selMonths)->merge($qMonths)->unique()->sort()->values()->all()
            : range(1, 12);
        $headings = ['Dinas', 'Indikator', 'Satuan', 'Tahun'];
        foreach ($monthsFor as $m) $headings[] = $MONTH_LABELS[$m];
        foreach ($selQuarters as $q) $headings[] = "Triwulan {$q}";

        $rowsOut = [];

        foreach ($officesFor as $office) {
            $meta   = $DINAS[$office];
            $model  = $meta['model'];
            $indMap = $meta['indicators'];
            $unitDefault = $meta['unit'] ?? '';

            $yearsAll = $model::select('tahun')->distinct()->orderBy('tahun')->pluck('tahun')->map(fn($y) => (int)$y)->all();
            $yearsFor = !empty($selYears) ? $selYears : $yearsAll;
            if (empty($yearsFor) || empty($indMap)) continue;

            $all = $model::whereIn('tahun', $yearsFor)->orderBy('tahun')->orderBy('bulan')->get();

            foreach ($yearsFor as $y) {
                foreach ($indMap as $key => $def) {
                    $label = is_array($def) ? ($def['label'] ?? $key) : (string)$def;
                    $unitForIndicator = is_array($def) ? ($def['unit'] ?? ($unitDefault ?? '')) : ($unitDefault ?? '');

                    $row = [
                        $meta['label'],     // Dinas
                        $label,             // Indikator
                        $unitForIndicator,  // Satuan
                        (int)$y,            // Tahun
                    ];
                    foreach ($monthsFor as $b) {
                        $rec = $all->first(fn($r) => $r->tahun == $y && $r->bulan == $b);
                        $row[] = $rec ? (int)$rec->{$key} : 0;
                    }
                    foreach ($selQuarters as $q) {
                        $sum = 0;
                        foreach (($QUARTERS[$q] ?? []) as $b) {
                            $rec = $all->first(fn($r) => $r->tahun == $y && $r->bulan == $b);
                            $sum += $rec ? (int)$rec->{$key} : 0;
                        }
                        $row[] = $sum;
                    }

                    $rowsOut[] = $row;
                }
            }
        }

        $type = strtolower($request->query('type', 'csv'));

        if ($type !== 'xlsx') {
            $filename = 'data_indikator.csv';
            return response()->streamDownload(function () use ($headings, $rowsOut) {
                $out = fopen('php://output', 'w');
                fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
                fputcsv($out, $headings);
                foreach ($rowsOut as $r) fputcsv($out, $r);
                fclose($out);
            }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
        }
        return Excel::download(new Export($headings, $rowsOut), 'data_indikator.xlsx');
    }


    public function data(Request $request)
    {
        $DINAS = $this->offices();
        $MONTH_KEYS = [1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'mei', 6 => 'jun', 7 => 'jul', 8 => 'ags', 9 => 'sep', 10 => 'okt', 11 => 'nov', 12 => 'des'];
        $QUARTERS   = [
            1 => [1, 2, 3],
            2 => [4, 5, 6],
            3 => [7, 8, 9],
            4 => [10, 11, 12],
        ];
        $offices = collect($DINAS)->map(fn($m, $k) => ['key' => $k, 'label' => $m['label']])->values()->all();
        $yearsAll = [];
        foreach ($DINAS as $k => $m) {
            $yearsAll[$k] = ($m['model'])::select('tahun')->distinct()->orderBy('tahun')
                ->pluck('tahun')->map(fn($y) => (int)$y)->values()->all();
        }
        $allMonths = range(1, 12);
        $selOffices = collect((array)$request->query('offices', []))
            ->filter(fn($k) => array_key_exists($k, $DINAS))->values()->all();
        $selYears = collect((array)$request->query('years', []))
            ->map(fn($y) => (int)$y)->filter()->values()->all();
        $selMonths = collect((array)$request->query('months', []))
            ->map(fn($m) => (int)$m)->filter(fn($m) => $m >= 1 && $m <= 12)->unique()->sort()->values()->all();
        $selQuarters = collect((array)$request->query('quarters', []))
            ->map(fn($q) => (int)$q)->filter(fn($q) => in_array($q, [1, 2, 3, 4], true))->unique()->sort()->values()->all();

        $officesForTable = !empty($selOffices) ? $selOffices : array_keys($DINAS);
        $monthsForTable  = !empty($selMonths)  ? $selMonths  : $allMonths;
        $rows = [];
        foreach ($officesForTable as $office) {
            $meta   = $DINAS[$office];
            $model  = $meta['model'];
            $indMap = $meta['indicators'];
            $unitDefault = $meta['unit'] ?? '';

            $years = !empty($selYears) ? $selYears : ($yearsAll[$office] ?? []);
            if (empty($years) || empty($indMap)) continue;

            $all = $model::whereIn('tahun', $years)->orderBy('tahun')->orderBy('bulan')->get();

            foreach ($years as $y) {
                foreach ($indMap as $key => $def) {
                    $label = is_array($def) ? ($def['label'] ?? $key) : (string)$def;
                    $unitForIndicator = is_array($def) ? ($def['unit'] ?? $unitDefault) : $unitDefault;
                    $row = [
                        'dinas_key'       => $office,
                        'dinas_label'     => $meta['label'],
                        'indikator_key'   => $key,
                        'indikator_label' => $label,
                        'unit'            => $unitForIndicator,
                        'tahun'           => (int)$y,
                    ];
                    foreach ($monthsForTable as $b) {
                        $rec = $all->first(fn($r) => $r->tahun == $y && $r->bulan == $b);
                        $row[$MONTH_KEYS[$b]] = $rec ? (int)$rec->{$key} : 0;
                    }
                    foreach ($selQuarters as $q) {
                        $sum = 0;
                        foreach ($QUARTERS[$q] as $b) {
                            $rec = $all->first(fn($r) => $r->tahun == $y && $r->bulan == $b);
                            $sum += $rec ? (int)$rec->{$key} : 0;
                        }
                        $row["tw{$q}"] = $sum;
                    }
                    $rows[] = $row;
                }
            }
        }

        $indicatorOptions = [];
        foreach ($DINAS as $office => $meta) {
            foreach ($meta['indicators'] as $k => $def) {
                $label = is_array($def) ? ($def['label'] ?? $k) : (string)$def;
                $unit  = is_array($def) ? ($def['unit'] ?? ($meta['unit'] ?? '')) : ($meta['unit'] ?? '');
                $indicatorOptions[] = [
                    'office'  => $office,
                    'key'     => $k,
                    'label'   => $label,
                    'display' => "{$meta['label']} • {$label}",
                    'unit'    => $unit,
                ];
            }
        }

        return Inertia::render('Admin/Data', [
            'rows'          => $rows,

            // selected
            'selected' => [
                'offices'  => $selOffices,
                'years'    => $selYears,
                'months'   => $selMonths,
                'quarters' => $selQuarters,
            ],

            // options
            'offices'        => $offices,
            'yearsPerOffice' => $yearsAll,
            'indicators'     => $indicatorOptions,
            'allMonths'      => $allMonths,
        ]);
    }

    public function bulkSave(Request $request)
    {
        $DINAS = $this->offices();

        $request->validate([
            'office' => ['required', Rule::in(array_keys($DINAS))],
            'tahun'  => ['required', 'integer', 'min:2000', 'max:2100'],
            'rows'   => ['required', 'array', 'min:1'],
            'rows.*.bulan' => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        $office = $request->string('office')->toString();
        $tahun  = (int) $request->input('tahun');
        $rows   = $request->input('rows', []);
        $indKeys = array_keys($DINAS[$office]['indicators']);
        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model  = $DINAS[$office]['model'];

        foreach ($rows as $row) {
            $bulan = (int) ($row['bulan'] ?? 0);
            if ($bulan < 1 || $bulan > 12) continue;

            $rec = $model::firstOrNew(['tahun' => $tahun, 'bulan' => $bulan]);

            if (Arr::has($rec->getAttributes(), 'user_id') || in_array('user_id', $rec->getFillable(), true)) {
                $rec->user_id = $request->user()->id;
            }

            foreach ($indKeys as $key) {
                if (array_key_exists($key, $row)) {
                    $rec->{$key} = (int) ($row[$key] ?? 0);
                }
            }
            $rec->save();
        }

        return back()->with('success', 'Data berhasil disimpan (bulk).');
    }

    public function destroy(Request $request)
    {
        $DINAS = $this->offices();

        $request->validate([
            'office' => ['required', Rule::in(array_keys($DINAS))],
            'id'     => ['nullable', 'integer'],
            'tahun'  => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'bulan'  => ['nullable', 'integer', 'min:1', 'max:12'],
        ]);

        $office = $request->string('office')->toString();
        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model  = $DINAS[$office]['model'];

        if ($id = $request->input('id')) {
            $rec = $model::find($id);
        } else {
            $tahun = (int) $request->input('tahun');
            $bulan = (int) $request->input('bulan');
            abort_if(!$tahun || !$bulan, 422, 'Tentukan id ATAU (tahun & bulan).');
            $rec = $model::where('tahun', $tahun)->where('bulan', $bulan)->first();
        }

        abort_unless($rec, 404, 'Data tidak ditemukan.');
        $rec->delete();

        return back()->with('success', 'Data dihapus.');
    }
}
