// resources/js/Pages/Dinaspertanian/Data.jsx
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useEffect, useMemo, useRef, useState } from 'react';

export default function Data({ records = [], filters = {} }) {
  const [tahun, setTahun] = useState('');
  const [bulan, setBulan] = useState('');
  const [showExport, setShowExport] = useState(false);
  const exportRef = useRef(null);

  useEffect(() => {
    window.scrollTo({ top: 0, behavior: 'auto' });
  }, []);
    
  const monthLabels = useMemo(
    () => [
      'Januari','Februari','Maret','April','Mei','Juni',
      'Juli','Agustus','September','Oktober','November','Desember',
    ],
    []
  );

  const normalized = useMemo(
    () =>
      (records ?? []).map((r) => ({
        ...r,
        tahun: Number(r.tahun),
        bulan: Number(r.bulan),
      })),
    [records]
  );

  const yearsInData = useMemo(
    () =>
      Array.from(new Set(normalized.map((r) => r.tahun)))
        .filter((v) => !Number.isNaN(v))
        .sort((a, b) => a - b),
    [normalized]
  );

  const monthsForSelectedYear = useMemo(() => {
    const source = tahun === '' ? normalized : normalized.filter((r) => r.tahun === Number(tahun));
    return Array.from(new Set(source.map((r) => r.bulan)))
      .filter((v) => !Number.isNaN(v) && v >= 1 && v <= 12)
      .sort((a, b) => a - b);
  }, [normalized, tahun]);

  useEffect(() => {
    if (filters?.tahun && yearsInData.includes(Number(filters.tahun))) {
      setTahun(Number(filters.tahun));
    }
    if (filters?.bulan && monthsForSelectedYear.includes(Number(filters.bulan))) {
      setBulan(Number(filters.bulan));
    }
  }, [filters, yearsInData.length]);

  useEffect(() => {
    if (bulan !== '' && !monthsForSelectedYear.includes(Number(bulan))) {
      setBulan('');
    }
  }, [monthsForSelectedYear, bulan]);

  useEffect(() => {
    function handleClickOutside(e) {
      if (exportRef.current && !exportRef.current.contains(e.target)) setShowExport(false);
    }
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  const selectedYear = tahun === '' ? null : Number(tahun);
  const selectedMonth = bulan === '' ? null : Number(bulan);
  const record =
    selectedYear && selectedMonth
      ? normalized.find((r) => r.tahun === selectedYear && r.bulan === selectedMonth)
      : null;

  const canAct = !!(selectedYear && selectedMonth);

  //sesuaikan dengan database
  const indikator = [
    { label: 'Produksi Padi',                 key: 'produksi_padi', unit: 'Ton' },
  ];

  return (
    //ganti dinas
    <AuthenticatedLayout header={<span>Dinas pertanian</span>}>
      <Head title="Dinas pertanian" />

      <div className="bg-white rounded-2xl shadow-sm border p-5 mb-6">
        <div className="flex items-center gap-3 text-slate-700 font-semibold mb-4">
          <svg className="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <rect x="3" y="4" width="18" height="18" rx="2" strokeWidth="2" />
            <path d="M16 2v4M8 2v4M3 10h18" strokeWidth="2" />
          </svg>
          <span>Filter Periode Data</span>
        </div>

        <div className="grid md:grid-cols-2 gap-4">
          {/* Tahun */}
          <div>
            <label className="block text-sm text-slate-600 mb-1">Tahun</label>
            <select
              value={tahun}
              onChange={(e) => setTahun(e.target.value === '' ? '' : Number(e.target.value))}
              className="w-full rounded-xl border-slate-200 shadow-sm px-3 py-2 text-sm"
            >
              <option value="">Pilih Tahun</option>
              {yearsInData.map((y) => (
                <option key={y} value={y}>
                  {y}
                </option>
              ))}
            </select>
          </div>

          <div>
            <label className="block text-sm text-slate-600 mb-1">Bulan</label>
            <select
              value={bulan}
              onChange={(e) => setBulan(e.target.value === '' ? '' : Number(e.target.value))}
              className="w-full rounded-xl border-slate-200 shadow-sm px-3 py-2 text-sm"
            >
              <option value="">Pilih Bulan</option>
              {monthsForSelectedYear.map((m) => (
                <option key={m} value={m}>
                  {monthLabels[m - 1]}
                </option>
              ))}
            </select>
          </div>
        </div>
      </div>

      <div className="bg-white rounded-2xl shadow-sm border p-5">
        <div className="flex items-center justify-between mb-3">

          <div className="flex items-center gap-3 text-slate-700 font-semibold mb-4">
              <svg className="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="3" y="4" width="18" height="16" rx="2" strokeWidth="2"/>
                <path d="M3 10h18M9 20V10M15 20V10" strokeWidth="2" />
              </svg>
              <span>Data</span>
          </div>

          <div className="flex gap-3" ref={exportRef}>
            <Link
              href={`/pertanian/edit?tahun=${selectedYear ?? ''}&bulan=${selectedMonth ?? ''}`}
              className={`px-4 py-2 rounded-lg text-white text-sm ${
                canAct
                  ? 'bg-orange-500 hover:bg-orange-600'
                  : 'bg-orange-300 cursor-not-allowed pointer-events-none'
              }`}
              preserveScroll
            >
              Edit Data
            </Link>

            <div className="relative">
              <button
                type="button"
                disabled={!canAct}
                onClick={() => setShowExport((v) => !v)}
                className={`px-4 py-2 rounded-lg text-white text-sm ${
                  canAct ? 'bg-green-600 hover:bg-green-700' : 'bg-green-300 cursor-not-allowed'
                }`}
              >
                Export
              </button>

              {showExport && canAct && (
                <div className="absolute right-0 mt-2 w-44 bg-white rounded-md shadow-lg border z-10">
                  <a
                    href={`/pertanian/export?format=csv&tahun=${selectedYear}&bulan=${selectedMonth}`}
                    className="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100"
                  >
                    Export CSV
                  </a>
                  <a
                    href={`/pertanian/export?format=xls&tahun=${selectedYear}&bulan=${selectedMonth}`}
                    className="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100"
                  >
                    Export Excel
                  </a>
                </div>
              )}
            </div>
          </div>
        </div>

        <div className="overflow-x-auto shadow border">
<table className="w-full text-sm md:text-sm leading-tight">
  <thead className="bg-slate-900 text-white ">
    <tr>
      <th className="px-2 py-3 text-center text-sm font-semibold border w-1/2">Indikator</th>
      <th className="px-2 py-3 text-center text-sm font-semibold border">Satuan</th>
      <th className="px-2 py-3 text-center text-sm font-semibold border">Jumlah</th>
    </tr>
  </thead>
  <tbody className="divide-y divide-slate-200">
    {indikator.map((row) => (
      <tr
        key={row.key}
        className="odd:bg-white even:bg-slate-50 hover:bg-slate-100"
      >
        <td className="px-2 py-3 text-slate-700 text-sm">{row.label}</td>
        <td className="px-2 py-3 text-center text-slate-900 border text-sm">{row.unit}</td>
        <td className="px-2 py-3 text-center font-medium font-mono border text-sm">
          {record
            ? record[row.key] !== null && record[row.key] !== undefined
              ? parseFloat(record[row.key]).toLocaleString('id-ID', {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2,
                })
              : '0,00'
            : '0,00'}
        </td>
      </tr>
    ))}
  </tbody>
</table>

        </div>

        {!canAct && (
          <div className="px-3 py-3 text-xs md:text-sm text-slate-500">
            Silakan pilih <b>Tahun</b> dan <b>Bulan</b> terlebih dahulu.
          </div>
        )}
      </div>
    </AuthenticatedLayout>
  );
}
