// resources/js/Pages/Admin/Data.jsx
import { Head, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import Select from 'react-select';
import { useMemo, useState } from 'react';
import { Table as TableIcon, Download as DownloadIcon } from 'lucide-react';

export default function AdminData({
  rows = [],
  selected = {},
  offices = [],
  yearsPerOffice = {},
  allMonths = [],
}) {
  const monthsFull = [
    'Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','November','Desember'
  ];
  const monthKeys  = ['jan','feb','mar','apr','mei','jun','jul','ags','sep','okt','nov','des'];
  const quarterLabels = {1:'Triwulan I',2:'Triwulan II',3:'Triwulan III',4:'Triwulan IV'};

  const selStyles = {
    control: (base) => ({
      ...base, borderRadius: 16, minHeight: 44, boxShadow: 'none',
      borderColor: '#e5e7eb', ':hover':{ borderColor:'#cbd5e1' }
    }),
    menu: (b) => ({ ...b, borderRadius: 12, overflow:'hidden' }),
    multiValue: (b) => ({ ...b, borderRadius: 10, background:'#e5e7eb' }),
  };
  const [sOffices, setSOffices]   = useState(selected.offices ?? []);
  const [sYears, setSYears]       = useState(selected.years ?? []);
  const [sMonths, setSMonths]     = useState(selected.months ?? []);
  const [sQuarters, setSQuarters] = useState(selected.quarters ?? []);
  const [showExport, setShowExport] = useState(false); 

  const optOffices = useMemo(
    () => offices.map(o => ({ value:o.key, label:o.label })),
    [offices]
  );
  const optYears = useMemo(
    () => Array.from(new Set(Object.values(yearsPerOffice).flat()))
      .sort((a,b)=>a-b)
      .map(y=>({ value:y, label:y })),
    [yearsPerOffice]
  );
  const optMonths = useMemo(
    () => (allMonths.length? allMonths : Array.from({length:12},(_,i)=>i+1))
      .map(m=>({ value:m, label: monthsFull[m-1] })),
    [allMonths]
  );
  const optQuarters = useMemo(
    () => [1,2,3,4].map(q => ({ value:q, label: quarterLabels[q] })),
    []
  );

  const monthsFromQuarters = (qs=[]) => {
    const map = { 1:[1,2,3], 2:[4,5,6], 3:[7,8,9], 4:[10,11,12] };
    return [...new Set(qs.flatMap(q => map[q] || []))];
  };

  const monthCols = useMemo(() => {
    const triMonths = monthsFromQuarters(sQuarters);
    let use = [];
    if (sMonths.length && triMonths.length) {
      use = [...new Set([...sMonths, ...triMonths])];
    } else if (sMonths.length) {
      use = sMonths;
    } else if (triMonths.length) {
      use = triMonths;
    } else {
      use = allMonths.length ? allMonths : Array.from({length:12},(_,i)=>i+1);
    }

    return use.slice().sort((a,b)=>a-b).map(m => ({
      num: m, key: monthKeys[m-1], label: monthsFull[m-1]
    }));
  }, [sMonths, sQuarters, allMonths]);

  const apply = (off=sOffices, yrs=sYears, mons=sMonths, qtrs=sQuarters) => {
    router.get(route('admin.data'), {
      offices: off, years: yrs, months: mons, quarters: qtrs,
    }, { preserveScroll: true, preserveState: true });
  };

  const exportUrl = (type /* 'csv' | 'xlsx' */) => {
    const qs = new URLSearchParams();
    (sOffices || []).forEach(v => qs.append('offices[]', v));
    (sYears   || []).forEach(v => qs.append('years[]', v));
    (sMonths  || []).forEach(v => qs.append('months[]', v));
    (sQuarters|| []).forEach(v => qs.append('quarters[]', v));
    qs.set('type', type);
    return route('admin.export') + '?' + qs.toString();
  };

  const phAll = (label) => ({ label: `Semua ${label}`, value: '__ALL__', isDisabled: true });

  return (
    <AdminLayout header="Badan Pusat Statistik">
      <Head title="Badan Pusat Statistik" />

      <div className="bg-white rounded-2xl shadow-sm border p-5">
        <div className="mb-4 flex items-center justify-between">
          <div className="font-semibold text-slate-700 flex items-center gap-2">
            <TableIcon className="h-5 w-5" />
            Data Indikator
          </div>

        <div className="relative">
          <button
            onClick={() => setShowExport(v => !v)}
            className="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-3 py-2 text-sm hover:bg-green-700"
          >
            <DownloadIcon className="h-4 w-4" />
            Export
          </button>

          {showExport && (
            <div className="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg z-20">
              <a
                href={exportUrl('csv')}
                className="block px-4 py-2 text-sm hover:bg-slate-100"
                onClick={() => setShowExport(false)}
              >
                Export CSV
              </a>
              <a
                href={exportUrl('xlsx')}
                className="block px-4 py-2 text-sm hover:bg-slate-100"
                onClick={() => setShowExport(false)}
              >
                Export XLSX
              </a>
            </div>
          )}
        </div>
        </div>

        <div className="grid lg:grid-cols-4 gap-4 mb-5">
          <div>
            <label className="block text-sm text-slate-600 mb-1">Dinas</label>
            <Select
              styles={selStyles}
              isSearchable={false}
              isMulti closeMenuOnSelect={false}
              placeholder={sOffices.length ? undefined : 'Semua'}
              value={sOffices.map(k=>({ value:k, label:(offices.find(o=>o.key===k)?.label ?? k) }))}
              options={sOffices.length ? optOffices : [phAll('Dinas'), ...optOffices]}
              onChange={(vals)=>{ const v=(vals??[]).map(o=>o.value); setSOffices(v); apply(v, sYears, sMonths, sQuarters); }}
              className="text-sm"
            />
          </div>

          <div>
            <label className="block text-sm text-slate-600 mb-1">Tahun</label>
            <Select
              styles={selStyles}
              isSearchable={false}
              isMulti closeMenuOnSelect={false}
              placeholder={sYears.length ? undefined : 'Semua'}
              value={sYears.map(y=>({ value:y, label:y }))}
              options={sYears.length ? optYears : [phAll('Tahun'), ...optYears]}
              onChange={(vals)=>{ const v=(vals??[]).map(o=>o.value); setSYears(v); apply(sOffices, v, sMonths, sQuarters); }}
              className="text-sm"
            />
          </div>

          <div>
            <label className="block text-sm text-slate-600 mb-1">Triwulanan</label>
            <Select
              styles={selStyles}
              isSearchable={false}
              isMulti closeMenuOnSelect={false}
              placeholder={sQuarters.length ? undefined : 'Tidak dipilih'}
              value={sQuarters.map(q=>({ value:q, label: quarterLabels[q] }))}
              options={[1,2,3,4].map(q=>({ value:q, label: quarterLabels[q] }))}
              onChange={(vals)=>{ const v=(vals??[]).map(o=>o.value); setSQuarters(v); apply(sOffices, sYears, sMonths, v); }}
              className="text-sm"
            />
          </div>

          <div>
            <label className="block text-sm text-slate-600 mb-1">Bulanan</label>
            <Select
              styles={selStyles}
              isSearchable={false}
              isMulti closeMenuOnSelect={false}
              placeholder={sMonths.length ? undefined : 'Semua'}
              value={sMonths.map(m=>({ value:m, label: monthsFull[m-1] }))}
              options={sMonths.length ? optMonths : [phAll('Bulan'), ...optMonths]}
              onChange={(vals)=>{ const v=(vals??[]).map(o=>o.value); setSMonths(v); apply(sOffices, sYears, v, sQuarters); }}
              className="text-sm"
            />
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-sm border">
            <thead className="bg-slate-800">
              <tr>
                <th className="p-2 border text-center text-white">Dinas</th>
                <th className="p-2 border text-center text-white">Indikator</th>
                <th className="p-2 border text-center text-white">Satuan</th>
                <th className="p-2 border text-center text-white">Tahun</th>
                {monthCols.map(c => (
                  <th key={c.key} className="p-2 border text-center text-white">{c.label}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {rows.map((row, idx)=>(
                <tr key={idx}>
                  <td className="p-2 border">{row.dinas_label}</td>
                  <td className="p-2 border">{row.indikator_label}</td>
                  <td className="p-2 border text-center">{row.unit && row.unit.trim() !== '' ? row.unit : '—'}</td>
                  <td className="p-2 border text-center">{row.tahun}</td>
                  {monthCols.map(c => (
                    <td key={c.key} className="p-2 border text-center">{row[c.key] ?? 0}</td>
                  ))}
                </tr>
              ))}
              {rows.length === 0 && (
                <tr>
                  <td className="p-3 text-center text-slate-500" colSpan={4 + monthCols.length}>
                    Tidak ada data
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </AdminLayout>
  );
}
