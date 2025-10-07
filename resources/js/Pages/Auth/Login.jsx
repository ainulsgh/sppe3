// resources/js/Pages/Auth/Login.jsx
import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Login({ status, canResetPassword }) {
  const { data, setData, post, processing, errors, reset } = useForm({
    email: '',
    password: '',
    remember: false,
  });

  const [clientErrors, setClientErrors] = useState({});

  const submit = (e) => {
    e.preventDefault();
    const ce = {};
    if (!String(data.email || '').trim()) ce.email = 'Alamat email wajib diisi.';
    if (!String(data.password || '').trim()) ce.password = 'Kata sandi wajib diisi.';
    if (!ce.email && !/^\S+@\S+\.\S+$/.test(String(data.email))) ce.email = 'Format email tidak valid.';
    if (Object.keys(ce).length) {
      setClientErrors(ce);
      return;
    }
    setClientErrors({});
    post(route('login'), { onFinish: () => reset('password') });
  };

  const emailError = clientErrors.email || errors.email;
  const passwordError = clientErrors.password || errors.password;

  return (
    <div className="min-h-screen flex flex-col bg-slate-50">
      <Head title="Login SPPE" />
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <div className="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-slate-800 via-slate-600 to-slate-800" />
        <div
          className="absolute inset-0 opacity-5"
          style={{
            backgroundImage: `url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%23334155' fill-opacity='0.1'%3E%3Cpath d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")`,
          }}
        />
      </div>

      {/* header */}
      <header className="relative bg-white shadow-md border-b-4 border-slate-800 flex-shrink-0">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="h-14 sm:h-16 flex items-center">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 sm:w-12 sm:h-12 rounded-xl overflow-hidden shadow-sm">
                <img src="/images/bone.svg" alt="Logo Bone" className="w-full h-full object-cover" />
              </div>
              <div className="leading-tight">
                <h1 className="text-slate-900 font-semibold tracking-tight text-[17px] sm:text-[22px]">SPPE</h1>
                <p className="text-slate-600 text-[12px] sm:text-[13px]">Kabupaten Bone</p>
              </div>
            </div>
          </div>
        </div>
      </header>

      {/* main */}
      <main className="relative flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-8">
        <div className="w-full max-w-6xl">
          <div className="grid grid-cols-1 lg:grid-cols-5 gap-6 lg:gap-10 items-center">
            {/* LOGIN card */}
            <div className="order-1 lg:order-2 lg:col-span-2">
              {/* === PERUBAHAN: responsive max-width card === */}
              <div className="mx-auto w-full max-w-[100%] sm:max-w-[520px] md:max-w-[600px] lg:max-w-[680px]">
                <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_10px_30px_rgb(2_6_23/0.08)]">
                  <div className="bg-slate-800 px-5 py-5 sm:px-8 sm:py-6 text-center">
                    <h3 className="text-[18px] sm:text-xl font-bold text-white tracking-tight">Portal Masuk</h3>
                    <p className="mt-1 text-slate-300 text-xs sm:text-sm">Akses Sistem Percepatan Pertumbuhan Ekonomi</p>
                  </div>

                  <div className="px-5 py-5 sm:px-8 sm:py-7">
                    {status && (
                      <div className="mb-4 rounded-lg border border-green-200 bg-green-50 p-3">
                        <p className="text-center text-[13px] font-medium text-green-800">{status}</p>
                      </div>
                    )}

                    <form onSubmit={submit} className="space-y-4 sm:space-y-5" noValidate>
                      {/* email */}
                      <div>
                        <InputLabel
                          htmlFor="email"
                          value="Email"
                          className="mb-1 block text-[13px] sm:text-[14.5px] font-medium text-slate-800"
                        />
                        <TextInput
                          id="email"
                          type="email"
                          name="email"
                          value={data.email}
                          placeholder="nama@instansi.go.id"
                          className="w-full rounded-lg border-2 border-slate-300 px-3.5 py-3 text-[15px] sm:px-4 sm:py-3.5 sm:text-[15.5px]
                                     transition-all duration-150 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/15"
                          autoComplete="username"
                          isFocused
                          required
                          onChange={(e) => setData('email', e.target.value)}
                        />
                        <InputError message={emailError} className="mt-1 text-[12.5px] text-red-600" />
                      </div>

                      {/* password */}
                      <div>
                        <div className="mb-1 flex items-center justify-between">
                          <InputLabel
                            htmlFor="password"
                            value="Kata Sandi"
                            className="text-[13px] sm:text-[14.5px] font-medium text-slate-800"
                          />
                        </div>
                        <TextInput
                          id="password"
                          type="password"
                          name="password"
                          value={data.password}
                          placeholder="Masukkan kata sandi"
                          className="w-full rounded-lg border-2 border-slate-300 px-3.5 py-3 text-[15px] sm:px-4 sm:py-3.5 sm:text-[15.5px]
                                     transition-all duration-150 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/15"
                          autoComplete="current-password"
                          required
                          onChange={(e) => setData('password', e.target.value)}
                        />
                        <InputError message={passwordError} className="mt-1 text-[12.5px] text-red-600" />
                      </div>

                      {/* remember */}
                      <div className="flex items-center gap-2">
                        <Checkbox
                          name="remember"
                          checked={data.remember}
                          onChange={(e) => setData('remember', e.target.checked)}
                          className="rounded border-2 border-slate-300"
                        />
                        <label className="cursor-pointer text-[12.5px] sm:text-[13px] text-slate-700">
                          Ingat saya di perangkat ini
                        </label>
                      </div>

                      {/* submit */}
                      <PrimaryButton
                        className="w-full flex justify-center rounded-lg bg-slate-800 py-2.5 text-[15.5px] font-semibold text-white
                                   shadow-md transition-all duration-150 hover:bg-slate-700 hover:shadow-lg
                                   focus:ring-4 focus:ring-slate-800/20 disabled:cursor-not-allowed disabled:opacity-60"
                        disabled={processing}
                      >
                        {processing ? (
                          <div className="flex items-center justify-center gap-2">
                            <div className="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent" />
                            <span>Memproses...</span>
                          </div>
                        ) : (
                          'MASUK'
                        )}
                      </PrimaryButton>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            {/* teks kiri */}
            <div className="order-2 lg:order-1 lg:col-span-3">
              <div className="text-center lg:text-left">
                <h2 className="font-bold text-slate-900 leading-tight text-[clamp(22px,4vw,32px)]">
                  Sistem Percepatan <br className="hidden sm:block" />
                  <span className="text-slate-800">Pertumbuhan Ekonomi</span>
                </h2>
                <div className="mt-4 h-[3px] w-24 bg-slate-800 rounded mx-auto lg:mx-0" />
                <p className="mt-4 max-w-2xl mx-auto lg:mx-0 text-slate-600 text-[15px] sm:text-base lg:text-lg leading-relaxed">
                  Lorem ipsum dolor sit, amet consectetur adipisicing elit. Vero consequatur earum deleniti fugit optio esse doloremque, 
                  ipsa dignissimos omnis soluta rem! Corrupti animi cumque delectus pariatur consequatur ducimus accusantium maxime.
                </p>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  );
}
