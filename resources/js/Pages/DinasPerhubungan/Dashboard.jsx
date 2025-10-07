// resources/js/Pages/DinasPerhubungan/Dashboard.jsx
import { Head, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {
  LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, LabelList
} from 'recharts';
import Select from 'react-select';
import { useMemo, useState,useEffect } from 'react';
import { LineChart as LineChartIcon, Table as TableIcon } from 'lucide-react';

export default function Dashboard({
  chartYear, chartIndicator, chartIndicatorLbl, chart,
  tableYears = [], tableIndicators = [], tableMonths = [], tableRows = [],
  indicators = [], allYears = [], allMonths = [],
}) {
  const monthsFull = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
  const monthKeys  = ['jan','feb','mar','apr','mei','jun','jul','ags','sep','okt','nov','des'];

  useEffect(() => {
    window.scrollTo({ top: 0, behavior: 'auto' });
  }, []);

  const selStyles = {
    control: (base) => ({ ...base, borderRadius: 16, minHeight: 44, boxShadow: 'none', borderColor: '#e5e7eb', ':hover':{borderColor:'#cbd5e1'} }),
    menu: (b) => ({ ...b, borderRadius: 12, overflow:'hidden' }),
    multiValue: (b) => ({ ...b, borderRadius: 10, background:'#e5e7eb' }),
  };

  const searchParams = typeof window !== 'undefined' ? new URLSearchParams(window.location.search) : null;
  const hasYearQ     = !!searchParams?.has('chart_year');
  const hasIndQ      = !!searchParams?.has('chart_indicator');

  const [gTahun, setGTahun]         = useState(hasYearQ ? (chartYear ?? null) : null);
  const [gIndikator, setGIndikator] = useState(hasIndQ  ? (chartIndicator ?? null) : null);

  const [tYears, setTYears]           = useState(tableYears);
  const [tIndikators, setTIndikators] = useState(tableIndicators.map(i=>i.key));
  const [tMonths, setTMonths]         = useState(tableMonths);

  const applyAll = (y = gTahun, ik = gIndikator, years = tYears, inds = tIndikators, mons = tMonths) => {
    const params = {};
    if (y !== null && y !== undefined) params.chart_year = Number(y);
    if (ik !== null && ik !== undefined) params.chart_indicator = ik;
    if (years?.length) params.table_years = years;
    if (inds?.length)  params.table_indicators = inds;
    if (mons?.length)  params.table_months = mons;

    router.get(route('perhubungan.dashboard'), params, { preserveScroll: true, preserveState: true });
  };

  const optYears      = useMemo(() => (allYears ?? []).map(y=>({ value:y, label:y })), [allYears]);
  const optIndikators = useMemo(() => (indicators ?? []).map(i=>({ value:i.key, label:i.label })), [indicators]);
  const optMonths     = useMemo(
    () => (allMonths.length?allMonths:Array.from({length:12},(_,i)=>i+1))
      .map(m=>({ value:m, label: monthsFull[m-1] })),
    [allMonths]
  );

  const monthCols = useMemo(() => {
    const use = (tMonths.length ? tMonths : (allMonths.length ? allMonths : Array.from({length:12},(_,i)=>i+1)))
      .slice().sort((a,b)=>a-b);
    return use.map(m => ({ num:m, key:monthKeys[m-1], label:monthsFull[m-1] }));
  }, [tMonths, allMonths]);

  const toNumber = (v) => {
    if (v === null || v === undefined || v === '') return 0;
    const num = Number(String(v).replace(',', '.'));
    return Number.isNaN(num) ? 0 : num;
  };

  const chartDataRaw = useMemo(
    () => (chart ?? []).map(d => ({ ...d, nilaiNum: toNumber(d.nilai) })),
    [chart]
  );

  const baseMonths = useMemo(
    () => Array.from({length:12},(_,i)=>({ bulan:i+1, nilaiNum:0 })),
    []
  );
  const chartData = chartDataRaw.length ? chartDataRaw : baseMonths;

  const maxVal   = useMemo(() => Math.max(0, ...chartData.map(d=>d.nilaiNum)), [chartData]);
  const yPad     = Math.max(5, Math.ceil(maxVal * 0.1));
  const yDomain  = [0, maxVal + yPad];

  const phAll = (label) => ({ label: `Semua ${label}`, value: '__ALL__', isDisabled: true });

  const unitMap = useMemo(() => {
    const m = new Map();
    (indicators ?? []).forEach(i => m.set(i.key, i.unit || 'Ton'));
    return m;
  }, [indicators]);

  const formatNumber = (v) => {
    if (v === null || v === undefined || v === '') return '';
    const str = String(v);
    const hasDecimal = str.includes('.') || str.includes(',');
    const num = Number(String(v).replace(',', '.'));
    if (Number.isNaN(num)) return v;
    return new Intl.NumberFormat('id-ID', {
      minimumFractionDigits: hasDecimal ? 2 : 0,
      maximumFractionDigits: 6,
    }).format(num);
  };

  return (
    <AuthenticatedLayout header={<span>Dinas Perhubungan</span>}>
      <Head title="Dinas Perhubungan" />

      <div className="bg-white rounded-2xl shadow-sm border p-5 mb-6">
        <div className="flex items-center justify-between mb-4">
          <h2 className="font-semibold text-slate-700 flex items-center gap-2">
            <LineChartIcon className="h-5 w-5" />
            Grafik Indikator
          </h2>
        </div>

        <div className="grid md:grid-cols-2 gap-4 mb-4">
          <div>
            <label className="block text-sm text-slate-600 mb-1">Tahun</label>
            <Select
              styles={selStyles}
              isSearchable={false}
              isClearable
              value={optYears.find(o=>o.value===gTahun) ?? null}
              onChange={(v)=>{ const y=v?.value ?? null; setGTahun(y); applyAll(y, gIndikator); }}
              options={optYears}
              className="text-sm"
              placeholder="Pilih tahun"
            />
          </div>

          <div>
            <label className="block text-sm text-slate-600 mb-1">Indikator</label>
            <Select
              styles={selStyles}
              isSearchable={false}
              isClearable
              value={optIndikators.find(o=>o.value===gIndikator) ?? null}
              onChange={(v)=>{ const ik=v?.value ?? null; setGIndikator(ik); applyAll(gTahun, ik); }}
              options={optIndikators}
              className="text-sm"
              placeholder="Pilih indikator"
            />
          </div>
        </div>

        <div className="rounded-xl border border-slate-100 p-2">
          <ResponsiveContainer width="100%" height={340}>
            <LineChart data={chartData} margin={{ left: 16, right: 24, top: 8, bottom: 20 }}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis
                dataKey="bulan"
                tickFormatter={(v)=>monthsFull[v-1]}
                interval={0}
                padding={{ left: 12, right: 12 }}
                tickMargin={8}
              />
              <YAxis hide domain={yDomain} allowDecimals />
              <Tooltip
                labelFormatter={(v)=>monthsFull[v-1]}
                formatter={(val) => [formatNumber(val), chartIndicatorLbl || 'Nilai']}
              />
              <Line type="monotone" dataKey="nilaiNum" stroke="#0ea5e9" dot={{ r:3 }} activeDot={{ r:5 }} isAnimationActive={false}>
                <LabelList
                  dataKey="nilaiNum"
                  position="top"
                  content={({ x, y, value }) => (
                    <text x={x} y={(y ?? 0) - 8} textAnchor="middle" fontSize="12" fill="#475569">
                      {formatNumber(value)}
                    </text>
                  )}
                />
              </Line>
            </LineChart>
          </ResponsiveContainer>
        </div>
      </div>

      <div className="bg-white rounded-2xl shadow-sm border p-5">
        <div className="mb-4 font-semibold text-slate-700 flex items-center gap-2">
          <TableIcon className="h-5 w-5" />
          Tabel Indikator
        </div>

        <div className="grid lg:grid-cols-3 gap-4 mb-5">
          <div>
            <label className="block text-sm text-slate-600 mb-1">Tahun</label>
            <Select
              styles={selStyles}
              isMulti
              isSearchable={false}
              closeMenuOnSelect={false}
              placeholder={tYears.length ? undefined : 'Semua'}
              value={tYears.map(y=>({ value:y, label:y }))}
              options={tYears.length ? optYears : [phAll('Tahun'), ...optYears]}
              onChange={(vals)=>{ const v=(vals??[]).map(o=>o.value); setTYears(v); applyAll(gTahun, gIndikator, v, tIndikators, tMonths); }}
              className="text-sm"
            />
          </div>

          <div>
            <label className="block text-sm text-slate-600 mb-1">Indikator</label>
            <Select
              styles={selStyles}
              isMulti
              isSearchable={false}
              closeMenuOnSelect={false}
              placeholder={tIndikators.length ? undefined : 'Semua '}
              value={tIndikators.map(k=>{
                const it=indicators.find(i=>i.key===k);
                return { value:k, label: it?.label ?? k };
              })}
              options={tIndikators.length ? optIndikators : [phAll('Indikator'), ...optIndikators]}
              onChange={(vals)=>{ const v=(vals??[]).map(o=>o.value); setTIndikators(v); applyAll(gTahun, gIndikator, tYears, v, tMonths); }}
              className="text-sm"
            />
          </div>

          <div>
            <label className="block text-sm text-slate-600 mb-1">Bulan</label>
            <Select
              styles={selStyles}
              isMulti
              isSearchable={false}
              closeMenuOnSelect={false}
              placeholder={tMonths.length ? undefined : 'Semua'}
              value={tMonths.map(m=>({ value:m, label: monthsFull[m-1] }))}
              options={tMonths.length ? optMonths : [phAll('Bulan'), ...optMonths]}
              onChange={(vals)=>{ const v=(vals??[]).map(o=>o.value); setTMonths(v); applyAll(gTahun, gIndikator, tYears, tIndikators, v); }}
              className="text-sm"
            />
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-sm border">
            <thead className="bg-slate-900">
              <tr>
                <th className="p-2 border text-center text-sm text-white">Indikator</th>
                <th className="p-2 border text-center text-sm text-white">Satuan</th>
                <th className="p-2 border text-center text-sm text-white">Tahun</th>
                {monthCols.map(c=>(
                  <th key={c.key} className="p-2 border text-center text-white">{c.label}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {tableRows.map((row, idx)=>{
                const unit = unitMap.get(row.indikator_key ?? row.indikator) ?? 'Ton';

                return (
                  <tr key={idx}>
                    <td className="p-2 border text-sm">{row.indikator_label}</td>
                    <td className="p-2 border text-sm text-center">{unit}</td>
                    <td className="p-2 border text-sm text-center">{row.tahun}</td>
                    {monthCols.map(c=>(
                      <td key={c.key} className="p-2 border text-center">
                        {formatNumber(row[c.key])}
                      </td>
                    ))}
                  </tr>
                );
              })}
              {tableRows.length === 0 && (
                <tr>
                  <td className="p-3 text-center text-slate-500" colSpan={3 + monthCols.length}>
                    Tidak ada data
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
