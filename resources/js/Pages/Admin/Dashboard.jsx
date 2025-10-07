// resources/js/Pages/Admin/Dashboard.jsx
import { Head, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
  LineChart,
  Line,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
  LabelList,
} from 'recharts';
import Select from 'react-select';
import { useMemo, useState } from 'react';
import { LineChart as LineChartIcon } from 'lucide-react';

export default function AdminDashboard({
  chartOffice,
  chartYear,
  chartIndicator,
  chart = [],
  offices = [],
  yearsPerOffice = {},
  indicatorOptionsForChart = [],
}) {
  const monthsFull = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember',
  ];

  const selStyles = {
    control: (base) => ({
      ...base,
      borderRadius: 16,
      minHeight: 44,
      boxShadow: 'none',
      borderColor: '#e5e7eb',
      ':hover': { borderColor: '#cbd5e1' },
    }),
    menu: (b) => ({ ...b, borderRadius: 12, overflow: 'hidden' }),
    multiValue: (b) => ({ ...b, borderRadius: 10, background: '#e5e7eb' }),
  };

  const [gOffice, setGOffice] = useState(chartOffice ?? null);
  const [gTahun, setGTahun] = useState(chartYear ?? null);
  const [gIndikator, setGIndikator] = useState(chartIndicator ?? null);

  const maxVal = useMemo(
    () => Math.max(0, ...chart.map((d) => Number(d.nilai) || 0)),
    [chart]
  );

  const yPad = Math.max(5, Math.ceil(maxVal * 0.1));
  const yDomain = [0, maxVal + yPad];

  const applyAll = (office, year, indicator) => {
    const params = {};
    if (office !== null && office !== undefined) params.chart_office = office;
    if (year !== null && year !== undefined) params.chart_year = Number(year);
    if (indicator !== null && indicator !== undefined) params.chart_indicator = indicator;

    router.get(route('admin.dashboard'), params, {
      preserveScroll: true,
      preserveState: true,
    });
  };

  const optYears = useMemo(
    () => (gOffice ? (yearsPerOffice[gOffice] ?? []).map((y) => ({ value: y, label: y })) : []),
    [gOffice, yearsPerOffice]
  );
  const optOffices = useMemo(
    () => offices.map((o) => ({ value: o.key, label: o.label })),
    [offices]
  );
  const optIndicatorsChart = useMemo(
    () => indicatorOptionsForChart.map((i) => ({ value: i.key, label: i.label })),
    [indicatorOptionsForChart]
  );

  return (
    <AdminLayout header="Badan Pusat Statistik">
      <Head title="Badan Pusat Statistik" />

      <div className="h-[calc(100vh-112px)] overflow-hidden">
        <div className="bg-white rounded-2xl shadow-sm border h-full flex flex-col">
          <div className="p-5">
            <div className="flex items-center justify-between mb-4">
              <h2 className="font-semibold text-slate-700 flex items-center gap-2">
                <LineChartIcon className="h-5 w-5" />
                Grafik Indikator
              </h2>
            </div>

            <div className="grid md:grid-cols-3 gap-4">
              <div>
                <label className="block text-sm text-slate-600 mb-1">Dinas</label>
                <Select
                  styles={selStyles}
                  isSearchable={false}
                  isClearable
                  value={optOffices.find((o) => o.value === gOffice) ?? null}
                  onChange={(v) => {
                    const office = v?.value ?? null;
                    setGOffice(office);
                    setGIndikator(null);
                    setGTahun(null);
                    applyAll(office, null, null);
                  }}
                  options={optOffices}
                  className="text-sm"
                  placeholder="Pilih dinas"
                />
              </div>

              <div>
                <label className="block text-sm text-slate-600 mb-1">Tahun</label>
                <Select
                  styles={selStyles}
                  isSearchable={false}
                  isClearable
                  value={optYears.find((o) => o.value === gTahun) ?? null}
                  onChange={(v) => {
                    const y = v?.value ?? null;
                    setGTahun(y);
                    applyAll(gOffice, y, gIndikator);
                  }}
                  options={optYears}
                  className="text-sm"
                  placeholder="Pilih tahun"
                  isDisabled={!gOffice}
                />
              </div>

              <div>
                <label className="block text-sm text-slate-600 mb-1">Indikator</label>
                <Select
                  styles={selStyles}
                  isSearchable={false}
                  isClearable
                  value={optIndicatorsChart.find((o) => o.value === gIndikator) ?? null}
                  onChange={(v) => {
                    const ik = v?.value ?? null;
                    setGIndikator(ik);
                    applyAll(gOffice, gTahun, ik);
                  }}
                  options={optIndicatorsChart}
                  className="text-sm"
                  placeholder="Pilih indikator"
                  isDisabled={!gOffice}
                />
              </div>
            </div>
          </div>

          <div className="flex-1 p-2">
            <ResponsiveContainer width="100%" height="100%">
              <LineChart
                data={chart}
                margin={{ left: 20, right: 24, top: 8, bottom: 20 }}
              >
                <CartesianGrid strokeDasharray="3 3" />

                <XAxis
                  dataKey="bulan"
                  tickFormatter={(v) => monthsFull[v - 1]}
                  padding={{ left: 12, right: 12 }}
                  interval={0}
                  tickMargin={8}
                />

                <YAxis hide domain={yDomain} />

                <Tooltip labelFormatter={(v) => monthsFull[v - 1]} />

                <Line
                  type="monotone"
                  dataKey="nilai"
                  stroke="#0ea5e9"
                  dot={{ r: 3 }}
                  activeDot={{ r: 5 }}
                  isAnimationActive={false}
                >
                  <LabelList
                    dataKey="nilai"
                    position="top"
                    content={({ x, y, value }) => (
                      <text
                        x={x}
                        y={(y ?? 0) - 8}
                        textAnchor="middle"
                        fontSize="12"
                        fill="#475569"
                      >
                        {value}
                      </text>
                    )}
                  />
                </Line>
              </LineChart>
            </ResponsiveContainer>
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
