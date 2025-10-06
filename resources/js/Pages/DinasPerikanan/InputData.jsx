import { Head, useForm, usePage } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useMemo, useState, useEffect } from 'react';

export default function InputData({ records = [], mode = 'create', record = null, filters = {} }) {
  useEffect(() => {
    window.scrollTo({ top: 0, behavior: 'auto' });
  }, []);

  const { flash } = usePage().props;

<<<<<<< HEAD
  const [periodError, setPeriodError] = useState('');

  const [successMsg, setSuccessMsg] = useState('');

  const [existsMsg, setExistsMsg] = useState('');

=======
  // Error periode (tahun/bulan belum dipilih)
  const [periodError, setPeriodError] = useState('');

  // Pesan sukses submit (auto-hide)
  const [successMsg, setSuccessMsg] = useState('');

  // Pesan “sudah ada di database” (NO timer, hanya bisa ditutup manual)
  const [existsMsg, setExistsMsg] = useState('');

  // Label bulan
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
  const monthNames = useMemo(
    () => ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
    []
  );

<<<<<<< HEAD
=======
  // Normalisasi records
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
  const normalized = useMemo(
    () => (records ?? []).map(r => ({ ...r, tahun: Number(r.tahun), bulan: Number(r.bulan) })),
    [records]
  );

<<<<<<< HEAD
=======
  // Ambil flash success dari server -> tampilkan (auto-hide 4 detik)
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
  useEffect(() => {
    if (flash?.success) setSuccessMsg(String(flash.success));
  }, [flash?.success]);

<<<<<<< HEAD
=======
  // Auto-hide untuk pesan sukses
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
  useEffect(() => {
    if (!successMsg) return;
    const t = setTimeout(() => setSuccessMsg(''), 4000);
    return () => clearTimeout(t);
  }, [successMsg]);

<<<<<<< HEAD
  //ganti nama variabel yang ada di database
=======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
  const base = {
    tahun: '',
    bulan: '',
    penangkapan_di_laut: '',
    penangkapan_di_perairan_umum: '',
    budidaya_laut_rumput_laut: '',
    budidaya_tambak_rumput_laut: '',
    budidaya_tambak_udang: '',
    budidaya_tambak_bandeng: '',
    budidaya_tambak_lainnya: '',
    budidaya_kolam: '',
    budidaya_sawah: '',
  };

<<<<<<< HEAD
=======
  // initial untuk mode edit
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
  const initial = useMemo(() => {
    if (mode === 'edit') {
      const t = Number(filters?.tahun) || '';
      const b = Number(filters?.bulan) || '';
      const r = record ?? {
        tahun: t, bulan: b,
        penangkapan_di_laut: 0,
        penangkapan_di_perairan_umum: 0,
        budidaya_laut_rumput_laut: 0,
        budidaya_tambak_rumput_laut: 0,
        budidaya_tambak_udang: 0,
        budidaya_tambak_bandeng: 0,
        budidaya_tambak_lainnya: 0,
        budidaya_kolam: 0,
        budidaya_sawah: 0,
      };
      return {
        tahun: t, bulan: b,
        penangkapan_di_laut: r.penangkapan_di_laut,
        penangkapan_di_perairan_umum: r.penangkapan_di_perairan_umum,
        budidaya_laut_rumput_laut: r.budidaya_laut_rumput_laut,
        budidaya_tambak_rumput_laut: r.budidaya_tambak_rumput_laut,
        budidaya_tambak_udang: r.budidaya_tambak_udang,
        budidaya_tambak_bandeng: r.budidaya_tambak_bandeng,
        budidaya_tambak_lainnya: r.budidaya_tambak_lainnya,
        budidaya_kolam: r.budidaya_kolam,
        budidaya_sawah: r.budidaya_sawah,
      };
    }
    return base;
  }, [mode, record, filters]);

  const { data, setData, post, processing, reset, errors } = useForm({ ...initial });

<<<<<<< HEAD
  const exists = useMemo(() => {
    if (mode !== 'create') return false; 
=======
  // ====== CEK EXISTING (tahun, bulan) ======
  const exists = useMemo(() => {
    if (mode !== 'create') return false; // di edit periode terkunci
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
    const t = Number(data.tahun);
    const b = Number(data.bulan);
    if (!t || !b) return false;
    return normalized.some(r => r.tahun === t && r.bulan === b);
  }, [mode, data.tahun, data.bulan, normalized]);

<<<<<<< HEAD
=======
  // Tampilkan banner “sudah ada” (tanpa timer)
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
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

<<<<<<< HEAD
=======
  // ====== SUBMIT ======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
  const submit = (e) => {
    e.preventDefault();

    if (!data.tahun || !data.bulan) {
      setPeriodError('Silakan pilih Tahun dan Bulan terlebih dahulu.');
      document.getElementById('filter-periode')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
      return;
    }
    setPeriodError('');

<<<<<<< HEAD
=======
    // Pengaman ekstra: jika sudah ada, jangan submit (misal user tekan Enter)
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
    if (mode === 'create' && exists) return;

    const action = mode === 'edit' ? route('perikanan.upsert') : route('perikanan.store');

    post(action, {
      preserveScroll: false,
<<<<<<< HEAD
      preserveState: true, 
=======
      preserveState: true, // jaga state lokal
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112

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

<<<<<<< HEAD
  //ganti label dan keynya
  //label yang muncul di halaman web
  //key yang ada di database
=======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
  const indikator = [
    { label: 'Penangkapan di laut', key: 'penangkapan_di_laut' },
    { label: 'Penangkapan di perairan umum', key: 'penangkapan_di_perairan_umum' },
    { label: 'Budidaya laut (rumput laut)', key: 'budidaya_laut_rumput_laut' },
    { label: 'Budidaya tambak (rumput laut)', key: 'budidaya_tambak_rumput_laut' },
    { label: 'Budidaya tambak (udang)', key: 'budidaya_tambak_udang' },
    { label: 'Budidaya tambak (bandeng)', key: 'budidaya_tambak_bandeng' },
    { label: 'Budidaya tambak (ikan lainnya)', key: 'budidaya_tambak_lainnya' },
    { label: 'Budidaya kolam', key: 'budidaya_kolam' },
    { label: 'Budidaya sawah', key: 'budidaya_sawah' },
  ];

  const isEdit = mode === 'edit';

  return (
<<<<<<< HEAD
    // ganti nama dinasnya
=======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
    <AuthenticatedLayout header={<div className="flex items-center gap-3">
      <span>Dinas Kelautan dan Perikanan</span>
      {isEdit && <span className="text-xs px-2 py-1 rounded bg-orange-100 text-orange-700">Mode Edit</span>}
    </div>}>
      <Head title={isEdit ? 'Edit Data Perikanan' : 'Dinas Kelautan dan Perikanan'} />

<<<<<<< HEAD
=======
      {/* ✅ Notifikasi “data sudah ada di database” — TANPA TIMER, hanya ✕ */}
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
      {existsMsg && (
        <div className="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800">
          <div className="flex justify-between items-center">
            <span>{existsMsg}</span>
            <button onClick={() => setExistsMsg('')} className="ml-3 font-bold">✕</button>
          </div>
        </div>
      )}

<<<<<<< HEAD
=======
      {/* ✅ Notifikasi sukses submit — AUTO-HIDE 4 detik */}
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
      {successMsg && (
        <div className="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800">
          <div className="flex justify-between items-center">
            <span>{successMsg}</span>
            <button onClick={() => setSuccessMsg('')} className="ml-3 font-bold">✕</button>
          </div>
        </div>
      )}

<<<<<<< HEAD
=======
      {/* Notifikasi validasi periode */}
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
      {periodError && (
        <div className="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-700">
          {periodError}
        </div>
      )}

<<<<<<< HEAD
=======
      {/* FILTER PERIODE */}
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
      <div id="filter-periode" className="bg-white rounded-2xl shadow-sm border p-5 mb-6">
        <div className="flex items-center gap-3 text-slate-700 font-semibold mb-4">
          <svg className="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <rect x="3" y="4" width="18" height="18" rx="2" strokeWidth="2" />
            <path d="M16 2v4M8 2v4M3 10h18" strokeWidth="2" />
          </svg>
          <span>Filter Periode Data</span>
        </div>

        <div className="grid md:grid-cols-2 gap-4">
<<<<<<< HEAD
=======
          {/* Tahun */}
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
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

<<<<<<< HEAD
=======
          {/* Bulan */}
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
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

<<<<<<< HEAD
=======
      {/* TABEL INDIKATOR */}
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
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
                    Ton
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

<<<<<<< HEAD
        <div className="pt-4 flex justify-end ">
          <button
=======
        {/* Tombol simpan */}
        <div className="pt-4 flex justify-end ">
          <button
            // ⛔ nonaktif jika: masih processing, atau (mode create & belum pilih periode), atau (mode create & data sudah ada)
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
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
