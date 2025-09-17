import { Head, useForm, usePage } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useMemo, useState, useEffect } from 'react';

export default function InputData({ records = [], mode = 'create', record = null, filters = {} }) {
  useEffect(() => {
    window.scrollTo({ top: 0, behavior: 'auto' });
  }, []);

  const { flash } = usePage().props;

  // Error periode (tahun/bulan belum dipilih)
  const [periodError, setPeriodError] = useState('');

  // Pesan sukses submit (auto-hide)
  const [successMsg, setSuccessMsg] = useState('');

  // Pesan “sudah ada di database” (NO timer, hanya bisa ditutup manual)
  const [existsMsg, setExistsMsg] = useState('');

  // Label bulan
  const monthNames = useMemo(
    () => ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
    []
  );

  // Normalisasi records
  const normalized = useMemo(
    () => (records ?? []).map(r => ({ ...r, tahun: Number(r.tahun), bulan: Number(r.bulan) })),
    [records]
  );

  // Ambil flash success dari server -> tampilkan (auto-hide 4 detik)
  useEffect(() => {
    if (flash?.success) setSuccessMsg(String(flash.success));
  }, [flash?.success]);

  // Auto-hide untuk pesan sukses
  useEffect(() => {
    if (!successMsg) return;
    const t = setTimeout(() => setSuccessMsg(''), 4000);
    return () => clearTimeout(t);
  }, [successMsg]);

  const base = {
    tahun: '',
    bulan: '',
    pbg: '',
  };

  // initial untuk mode edit
  const initial = useMemo(() => {
    if (mode === 'edit') {
      const t = Number(filters?.tahun) || '';
      const b = Number(filters?.bulan) || '';
      const r = record ?? {
        tahun: t, bulan: b,
        pbg: 0,
      };
      return {
        tahun: t, bulan: b,
        daging_sapi: r.daging_sapi,
        pbg: r.pbg,
      };
    }
    return base;
  }, [mode, record, filters]);

  const { data, setData, post, processing, reset, errors } = useForm({ ...initial });

  // ====== CEK EXISTING (tahun, bulan) ======
  const exists = useMemo(() => {
    if (mode !== 'create') return false; // di edit periode terkunci
    const t = Number(data.tahun);
    const b = Number(data.bulan);
    if (!t || !b) return false;
    return normalized.some(r => r.tahun === t && r.bulan === b);
  }, [mode, data.tahun, data.bulan, normalized]);

  // Tampilkan banner “sudah ada” (tanpa timer)
  useEffect(() => {
    if (mode !== 'create') return;
    const t = Number(data.tahun);
    const b = Number(data.bulan);
    if (exists && t && b) {
      const month = monthNames[b - 1] || `Bulan ${b}`;
      setExistsMsg(`Data untuk ${month} ${t} sudah ada di database. Silakan mengedit data jika ingin memperbarui.`);
    } else {
      setExistsMsg('');
    }
  }, [exists, data.tahun, data.bulan, monthNames, mode]);

  // ====== SUBMIT ======
  const submit = (e) => {
    e.preventDefault();

    if (!data.tahun || !data.bulan) {
      setPeriodError('Silakan pilih Tahun dan Bulan terlebih dahulu.');
      document.getElementById('filter-periode')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
      return;
    }
    setPeriodError('');

    // Pengaman ekstra: jika sudah ada, jangan submit (misal user tekan Enter)
    if (mode === 'create' && exists) return;

    const action = mode === 'edit' ? route('dpmptsp.upsert') : route('dpmptsp.store');

    post(action, {
      preserveScroll: false,
      preserveState: true, // jaga state lokal

      onSuccess: (page) => {
        const serverMsg = page?.props?.flash?.success;
        setSuccessMsg(serverMsg || 'Data berhasil disimpan.');
        window.scrollTo({ top: 0, behavior: 'smooth' });
        if (mode !== 'edit') reset();
      },

      onError: () => setSuccessMsg(''),
    });
  };

  const now = new Date();
  const years = useMemo(
    () => Array.from({ length: 11 }, (_, i) => now.getFullYear() - 5 + i),
    []
  );
  const months = monthNames;

  const indikator = [
    { label: 'Persetujuan Bangunan Gedung (PBG)',                 key: 'pbg',           unit: 'Unit' },
  ];

  const isEdit = mode === 'edit';

  return (
    <AuthenticatedLayout header={<div className="flex items-center gap-3">
      <span>Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu</span>
      {isEdit && <span className="text-xs px-2 py-1 rounded bg-orange-100 text-orange-700">Mode Edit</span>}
    </div>}>
      <Head title={isEdit ? 'Edit Data dpmptsp' : 'DPMPTSP'} />

      {/* ✅ Notifikasi “data sudah ada di database” — TANPA TIMER, hanya ✕ */}
      {existsMsg && (
        <div className="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800">
          <div className="flex justify-between items-center">
            <span>{existsMsg}</span>
            <button onClick={() => setExistsMsg('')} className="ml-3 font-bold">✕</button>
          </div>
        </div>
      )}

      {/* ✅ Notifikasi sukses submit — AUTO-HIDE 4 detik */}
      {successMsg && (
        <div className="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800">
          <div className="flex justify-between items-center">
            <span>{successMsg}</span>
            <button onClick={() => setSuccessMsg('')} className="ml-3 font-bold">✕</button>
          </div>
        </div>
      )}

      {/* Notifikasi validasi periode */}
      {periodError && (
        <div className="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-700">
          {periodError}
        </div>
      )}

      {/* FILTER PERIODE */}
      <div id="filter-periode" className="bg-white rounded-2xl shadow-sm border p-5 mb-6">
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
              value={data.tahun ?? ''}
              onChange={(e) => setData('tahun', e.target.value === '' ? '' : Number(e.target.value))}
              className="w-full rounded-xl border-slate-200 shadow-sm px-3 py-2 text-sm"
              required
              disabled={isEdit}
            >
              <option value="">Pilih Tahun</option>
              {years.map((y) => <option key={y} value={y}>{y}</option>)}
            </select>
            {isEdit && <input type="hidden" name="tahun" value={data.tahun} />}
            {errors.tahun && <p className="text-red-600 text-sm mt-1">{errors.tahun}</p>}
          </div>

          {/* Bulan */}
          <div>
            <label className="block text-sm text-slate-600 mb-1">Bulan</label>
            <select
              value={data.bulan ?? ''}
              onChange={(e) => setData('bulan', e.target.value === '' ? '' : Number(e.target.value))}
              className="w-full rounded-xl border-slate-200 shadow-sm px-3 py-2 text-sm"
              required
              disabled={isEdit}
            >
              <option value="">Pilih Bulan</option>
              {monthNames.map((m, i) => <option key={m} value={i + 1}>{m}</option>)}
            </select>
            {isEdit && <input type="hidden" name="bulan" value={data.bulan} />}
            {errors.bulan && <p className="text-red-600 text-sm mt-1">{errors.bulan}</p>}
          </div>
        </div>
      </div>

      {/* TABEL INDIKATOR */}
      <form onSubmit={submit} className="bg-white rounded-2xl shadow-sm border p-5">
        <div className="flex items-center gap-3 text-slate-700 font-semibold mb-4">
          <svg className="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4 12.5-12.5z" strokeWidth="2" />
          </svg>
          <span>Input Data</span>
        </div>

        <div className="overflow-x-auto shadow">
          <table className="w-full table-auto text-xs md:text-sm leading-normal border border-slate-200">
            <thead className="bg-slate-900 text-white text-sm">
              <tr>
                <th className="px-3 py-3 text-center text-sm font-semibold md:w-1/2 border border-slate-200">Indikator</th>
                <th className="px-3 py-3 text-center text-sm font-semibold border border-slate-200">Jumlah</th>
                <th className="px-3 py-3 text-center text-sm font-semibold md:w-1/5 border border-slate-200">Satuan</th>
              </tr>
            </thead>
            <tbody>
              {indikator.map((row) => (
                <tr key={row.key} className="odd:bg-white even:bg-slate-50 hover:bg-slate-100">
                  <td className="px-3 py-3 text-slate-700 border border-slate-200 text-sm">
                    {row.label}
                  </td>
                  <td className="px-3 py-3 border border-slate-200">
                    <div className="flex justify-center">
                      <input
                        type="number"
                        min="0"
                        step="any"
                        inputMode="decimal"
                        value={data[row.key]}
                        onChange={(e) => setData(row.key, e.target.value)}
                        className="w-full max-w-[240px] md:max-w-[280px] rounded-xl border-slate-200 shadow-sm px-2 py-1 text-center font-mono tabular-nums"
                        required
                      />
                    </div>
                    {errors[row.key] && (
                      <div className="text-center">
                        <p className="text-red-600 text-xs md:text-sm mt-1">{errors[row.key]}</p>
                      </div>
                    )}
                  </td>
                  <td className="px-3 py-3 text-center text-slate-900 border border-slate-200">
                    Unit
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {/* Tombol simpan */}
        <div className="pt-4 flex justify-end ">
          <button
            // ⛔ nonaktif jika: masih processing, atau (mode create & belum pilih periode), atau (mode create & data sudah ada)
            disabled={
              processing || (!isEdit && (!data.tahun || !data.bulan || exists))
            }
            title={exists ? 'Kombinasi Tahun & Bulan sudah ada. Gunakan menu Edit.' : undefined}
            className={`inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white ${
              processing || (!isEdit && (!data.tahun || !data.bulan || exists))
                ? 'bg-slate-400 cursor-not-allowed'
                : 'bg-slate-900 hover:bg-slate-700'
            }`}
          >
            Simpan
          </button>
        </div>
      </form>
    </AuthenticatedLayout>
  );
}
