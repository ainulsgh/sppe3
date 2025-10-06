import { Head, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Perikanan({ records = [] }) {
  const { data, setData, post, processing, reset, errors } = useForm({
    tahun: new Date().getFullYear(),
    produksi_ikan: '',
    jumlah_kapal: '',
    nelayan_aktif: '',
    luas_tambak: '',
    nilai_ekspor: '',
  });

  const submit = (e) => {
    e.preventDefault();
    // hindari Ziggy dulu
    post('/perikanan', { onSuccess: () => reset() });
  };

  return (
    <AuthenticatedLayout header={<h2 className="text-xl font-semibold">Dinas Perikanan</h2>}>
      <Head title="Dinas Perikanan" />

      <form onSubmit={submit} className="grid gap-3 bg-white p-4 rounded-xl shadow max-w-3xl">
        <Input label="Tahun" val={data.tahun} set={(v)=>setData('tahun',v)} err={errors.tahun} />
        <Input label="Produksi Ikan (ton)" val={data.produksi_ikan} set={(v)=>setData('produksi_ikan',v)} err={errors.produksi_ikan} />
        <Input label="Jumlah Kapal" val={data.jumlah_kapal} set={(v)=>setData('jumlah_kapal',v)} err={errors.jumlah_kapal} />
        <Input label="Nelayan Aktif" val={data.nelayan_aktif} set={(v)=>setData('nelayan_aktif',v)} err={errors.nelayan_aktif} />
        <Input label="Luas Tambak (ha)" val={data.luas_tambak} set={(v)=>setData('luas_tambak',v)} err={errors.luas_tambak} />
        <Input label="Nilai Ekspor (Rp)" val={data.nilai_ekspor} set={(v)=>setData('nilai_ekspor',v)} err={errors.nilai_ekspor} />
        <button disabled={processing} className="mt-2 px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
      </form>

      <h2 className="text-xl font-semibold mt-8 mb-3">Data Tersimpan</h2>
      <div className="bg-white rounded-xl shadow overflow-auto">
        <table className="min-w-full text-sm">
          <thead className="bg-gray-100">
            <tr>
              <th className="px-3 py-2 text-left">Tahun</th>
              <th className="px-3 py-2 text-left">Produksi Ikan</th>
              <th className="px-3 py-2 text-left">Jumlah Kapal</th>
              <th className="px-3 py-2 text-left">Nelayan Aktif</th>
              <th className="px-3 py-2 text-left">Luas Tambak</th>
              <th className="px-3 py-2 text-left">Nilai Ekspor</th>
            </tr>
          </thead>
          <tbody>
            {records.map((r)=> (
              <tr key={r.id} className="border-t">
                <td className="px-3 py-2">{r.tahun}</td>
                <td className="px-3 py-2">{r.produksi_ikan}</td>
                <td className="px-3 py-2">{r.jumlah_kapal}</td>
                <td className="px-3 py-2">{r.nelayan_aktif}</td>
                <td className="px-3 py-2">{r.luas_tambak}</td>
                <td className="px-3 py-2">{Intl.NumberFormat('id-ID').format(r.nilai_ekspor)}</td>
              </tr>
            ))}
            {records.length === 0 && (
              <tr><td className="px-3 py-4 text-gray-500" colSpan={6}>Belum ada data.</td></tr>
            )}
          </tbody>
        </table>
      </div>
    </AuthenticatedLayout>
  );
}

function Input({label, val, set, err}) {
  return (
    <div>
      <label className="block text-sm">{label}</label>
      <input type="number" value={val} onChange={e=>set(e.target.value)}
             className="w-full border rounded-lg px-3 py-2" />
      {err && <p className="text-red-600 text-sm">{err}</p>}
    </div>
  );
}
