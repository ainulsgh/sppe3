import { Head } from '@inertiajs/react';

export default function Admin({ perikanan = [], pertanian = [] }) {
  return (
    <div className="p-6 max-w-6xl mx-auto">
      <Head title="Admin" />
      <h1 className="text-2xl font-semibold mb-6">Dashboard Admin</h1>

      <section className="mb-8">
        <h2 className="text-xl font-semibold mb-2">Data Dinas Perikanan</h2>
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
              {perikanan.map(r => (
                <tr key={r.id} className="border-t">
                  <td className="px-3 py-2">{r.tahun}</td>
                  <td className="px-3 py-2">{r.produksi_ikan}</td>
                  <td className="px-3 py-2">{r.jumlah_kapal}</td>
                  <td className="px-3 py-2">{r.nelayan_aktif}</td>
                  <td className="px-3 py-2">{r.luas_tambak}</td>
                  <td className="px-3 py-2">{Intl.NumberFormat('id-ID').format(r.nilai_ekspor)}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </section>

      <section>
        <h2 className="text-xl font-semibold mb-2">Data Dinas Pertanian</h2>
        <div className="bg-white rounded-xl shadow overflow-auto">
          <table className="min-w-full text-sm">
            <thead className="bg-gray-100">
              <tr>
                <th className="px-3 py-2 text-left">Tahun</th>
                <th className="px-3 py-2 text-left">Luas Lahan</th>
                <th className="px-3 py-2 text-left">Produksi Padi</th>
                <th className="px-3 py-2 text-left">Produksi Jagung</th>
                <th className="px-3 py-2 text-left">Produktivitas Padi</th>
                <th className="px-3 py-2 text-left">Jumlah Petani</th>
                <th className="px-3 py-2 text-left">Irigasi Aktif</th>
                <th className="px-3 py-2 text-left">Harga Gabah</th>
              </tr>
            </thead>
            <tbody>
              {pertanian.map(r => (
                <tr key={r.id} className="border-t">
                  <td className="px-3 py-2">{r.tahun}</td>
                  <td className="px-3 py-2">{r.luas_lahan}</td>
                  <td className="px-3 py-2">{r.produksi_padi}</td>
                  <td className="px-3 py-2">{r.produksi_jagung}</td>
                  <td className="px-3 py-2">{r.produktivitas_padi}</td>
                  <td className="px-3 py-2">{r.jumlah_petani}</td>
                  <td className="px-3 py-2">{r.irigasi_aktif}</td>
                  <td className="px-3 py-2">{r.harga_gabah}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </section>
    </div>
  );
}