<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use App\Models\PerikananRecord;
use App\Models\PeternakanRecord;
use App\Models\PerhubunganRecord;
use App\Models\dpmptspRecord;
use Maatwebsite\Excel\Facades\Excel;
use App\Export\Export;

class AdminController extends Controller
{
    /** ------------- KONFIGURASI DINAS & INDIKATOR ------------- */
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
                // indikator mendukung bentuk array {label, unit}
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
                // tidak pakai default unit; ambil dari masing-masing indikator
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
                'model' => dpmptspRecord::class,
                'indicators' => [
                    'pbg'               => ['label' => 'Persetujuan Bangunan Gedung',                'unit' => 'Unit'],
                ],
                'unit' => '',
            ],
        ];
    }

    private array $MONTH_KEYS = [1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'mei', 6 => 'jun', 7 => 'jul', 8 => 'ags', 9 => 'sep', 10 => 'okt', 11 => 'nov', 12 => 'des'];

    private function getOffice(string $key): array
    {
        $all = $this->offices();
        abort_unless(array_key_exists($key, $all), 400, 'Dinas tidak dikenal.');
        return $all[$key];
    }

    /** Helper label/unit indikator (kompatibel string / array {label, unit}) */
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

    /** ====================== DASHBOARD ====================== */
    // app/Http/Controllers/AdminController.php

    public function dashboard(Request $request)
    {
        $DINAS = $this->offices();

        /* ---------- Grafik (single) ---------- */
        // ❗ Tanpa default – boleh null
        $chartOffice = $request->query('chart_office');
        if ($chartOffice && !array_key_exists($chartOffice, $DINAS)) {
            abort(400, 'Dinas tidak dikenal.');
        }

        $chartYear = $request->query('chart_year');            // bisa null
        $chartYear = $chartYear !== null ? (int)$chartYear : null;

        $chartIndicator = $request->query('chart_indicator');  // bisa null
        // ❗ Tidak lagi auto-pilih indikator pertama

        // Siapkan grafik: default 12 nol
        $chart = [];
        for ($b = 1; $b <= 12; $b++) {
            $chart[] = ['bulan' => $b, 'nilai' => 0];
        }

        // Jika semua filter lengkap & valid → isi data sebenarnya
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

        /* ---------- Tabel (multi) ---------- */
        $yearsPerOffice = [];
        foreach ($DINAS as $k => $m) {
            $yearsPerOffice[$k] = ($m['model'])::select('tahun')->distinct()->orderBy('tahun')
                ->pluck('tahun')->map(fn($y) => (int)$y)->values()->all();
        }

        $allMonths = range(1, 12);

        // selected (boleh kosong = Semua)
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

        // options
        $officeOptions = collect($DINAS)->map(fn($m, $k) => ['key' => $k, 'label' => $m['label']])->values()->all();

        // ✅ aman saat chartOffice null; ambil label dari string/array
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
            // Grafik (indikator label sengaja kosong supaya tidak tampil)
            'chartOffice'       => $chartOffice,
            'chartYear'         => $chartYear,
            'chartIndicator'    => $chartIndicator,
            'chartIndicatorLbl' => '',   // ❗ kosongkan
            'chart'             => $chart,

            // Tabel
            'tableRows'         => $rows,

            // selected
            'tableOffices'      => $selOffices,
            'tableYears'        => $selYears,
            'tableIndicators'   => $selIndicators,
            'tableMonths'       => $selMonths,

            // options
            'offices'                   => $officeOptions,
            'yearsPerOffice'            => $yearsPerOffice,
            'indicatorOptionsForChart'  => $indicatorOptionsForChart,
            'indicatorOptionsForTable'  => $indicatorOptionsForTable,
            'allMonths'                 => $allMonths,
        ]);
    }


    /** ====================== SIMPAN (UPSERT) SATU BULAN ====================== */
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

        // tambahkan rule indikator dinamis (semua optional, numeric >= 0)
        foreach (array_keys($DINAS[$office]['indicators']) as $key) {
            $rules[$key] = ['nullable', 'integer', 'min:0'];
        }

        $data = $request->validate($rules);

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $DINAS[$office]['model'];

        // cari atau buat record (unik: tahun+bulan)
        $rec = $model::firstOrNew(['tahun' => $data['tahun'], 'bulan' => $data['bulan']]);

        // isi user_id jika ada kolomnya
        if (Arr::has($rec->getAttributes(), 'user_id') || in_array('user_id', $rec->getFillable(), true)) {
            $rec->user_id = $request->user()->id;
        }

        // set nilai indikator yang dikirim (yang null tidak disentuh)
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
        // --- Master sama seperti di data() ---
        $DINAS = [
            'perikanan' => [
                'label' => 'Dinas Kelautan dan Perikanan',
                'model' => \App\Models\PerikananRecord::class,
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
                // default unit untuk semua indikator perikanan
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
                'model' => dpmptspRecord::class,
                'indicators' => [
                    'pbg'               => ['label' => 'Persetujuan Bangunan Gedung',                'unit' => 'Unit'],
                ],
                'unit' => '',
            ],
        ];
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

        // Union bulan dari bulan & triwulan
        $qMonths = collect($selQuarters)->flatMap(fn($q) => $QUARTERS[$q] ?? [])->unique()->sort()->values()->all();
        $monthsFor = !empty($selMonths) || !empty($qMonths)
            ? collect($selMonths)->merge($qMonths)->unique()->sort()->values()->all()
            : range(1, 12);

        // ==== Ambil data & bentuk array export ====
        // Tambahkan kolom "Satuan" setelah "Indikator"
        $headings = ['Dinas', 'Indikator', 'Satuan', 'Tahun'];
        foreach ($monthsFor as $m) $headings[] = $MONTH_LABELS[$m];
        foreach ($selQuarters as $q) $headings[] = "Triwulan {$q}";

        $rowsOut = [];

        foreach ($officesFor as $office) {
            $meta   = $DINAS[$office];
            $model  = $meta['model'];
            $indMap = $meta['indicators'];

            // unit default per office (bisa override per-indikator)
            $unitDefault = $meta['unit'] ?? '';

            // tahun yang ada di DB untuk office ini bila user tidak memilih
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
                        $unitForIndicator,  // Satuan  ✅ per-indikator
                        (int)$y,            // Tahun
                    ];

                    // kolom bulanan
                    foreach ($monthsFor as $b) {
                        $rec = $all->first(fn($r) => $r->tahun == $y && $r->bulan == $b);
                        $row[] = $rec ? (int)$rec->{$key} : 0;
                    }

                    // kolom triwulanan (sum 3 bulan)
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

        // === CSV (tanpa paket tambahan) ===
        if ($type !== 'xlsx') {
            $filename = 'data_indikator.csv';
            return response()->streamDownload(function () use ($headings, $rowsOut) {
                $out = fopen('php://output', 'w');
                // opsional: BOM untuk Excel Windows
                fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
                fputcsv($out, $headings);
                foreach ($rowsOut as $r) fputcsv($out, $r);
                fclose($out);
            }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
        }

        // === XLSX (butuh paket maatwebsite/excel) ===
        return Excel::download(new Export($headings, $rowsOut), 'data_indikator.xlsx');
    }

    // app/Http/Controllers/AdminController.php

    public function data(Request $request)
    {
        // --- Master dinas & indikator (samakan dgn yang di dashboard()) ---
        $DINAS = [
            'perikanan' => [
                'label' => 'Dinas Kelautan dan Perikanan',
                'model' => \App\Models\PerikananRecord::class,
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
                // ✅ default unit untuk semua indikator perikanan
                'unit' => 'Ton',
            ],
            'peternakan' => [
                'label' => 'Dinas Peternakan dan Kesehatan Hewan',
                'model' => \App\Models\PeternakanRecord::class,
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
                'model' => dpmptspRecord::class,
                'indicators' => [
                    'pbg'               => ['label' => 'Persetujuan Bangunan Gedung',                'unit' => 'Unit'],
                ],
                'unit' => '',
            ],
        ];
        $MONTH_KEYS = [1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'mei', 6 => 'jun', 7 => 'jul', 8 => 'ags', 9 => 'sep', 10 => 'okt', 11 => 'nov', 12 => 'des'];
        $QUARTERS   = [
            1 => [1, 2, 3],
            2 => [4, 5, 6],
            3 => [7, 8, 9],
            4 => [10, 11, 12],
        ];

        // ==== Options/master ====
        $offices = collect($DINAS)->map(fn($m, $k) => ['key' => $k, 'label' => $m['label']])->values()->all();
        $yearsAll = [];
        foreach ($DINAS as $k => $m) {
            $yearsAll[$k] = ($m['model'])::select('tahun')->distinct()->orderBy('tahun')
                ->pluck('tahun')->map(fn($y) => (int)$y)->values()->all();
        }
        $allMonths = range(1, 12);

        // ==== Selected (boleh kosong = semua) ====
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

        // ==== Ambil data & bentuk rows ====
        $rows = [];
        foreach ($officesForTable as $office) {
            $meta   = $DINAS[$office];
            $model  = $meta['model'];
            $indMap = $meta['indicators'];
            // unit default untuk office ini
            $unitDefault = $meta['unit'] ?? '';

            $years = !empty($selYears) ? $selYears : ($yearsAll[$office] ?? []);
            if (empty($years) || empty($indMap)) continue;

            $all = $model::whereIn('tahun', $years)->orderBy('tahun')->orderBy('bulan')->get();

            foreach ($years as $y) {
                foreach ($indMap as $key => $def) {
                    $label = is_array($def) ? ($def['label'] ?? $key) : (string)$def;
                    $unitForIndicator = is_array($def) ? ($def['unit'] ?? $unitDefault) : $unitDefault;

                    // base row (+ unit)
                    $row = [
                        'dinas_key'       => $office,
                        'dinas_label'     => $meta['label'],
                        'indikator_key'   => $key,
                        'indikator_label' => $label,
                        // ✅ kolom unit per-indikator
                        'unit'            => $unitForIndicator,
                        'tahun'           => (int)$y,
                    ];
                    // bulanan (hanya yang terpilih)
                    foreach ($monthsForTable as $b) {
                        $rec = $all->first(fn($r) => $r->tahun == $y && $r->bulan == $b);
                        $row[$MONTH_KEYS[$b]] = $rec ? (int)$rec->{$key} : 0;
                    }
                    // triwulanan (hanya yang terpilih)
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

        // ==== Options untuk FE ====
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
                    // opsional: kirim unit ke FE bila diperlukan di tempat lain
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

    /** ====================== SIMPAN BANYAK BULAN SEKALIGUS ====================== */
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

    /** ====================== HAPUS DATA ====================== */
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
