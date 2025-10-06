// resources/js/Layouts/AdminLayout.jsx
import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link, usePage } from '@inertiajs/react';
import { useEffect, useState, useCallback } from 'react';

export default function AdminLayout({ header, children }) {
  const user = usePage().props?.auth?.user ?? {};

<<<<<<< HEAD
=======
  // ===== util awal sinkron (hindari flicker) =====
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
  const isMdUpNow = () =>
    (typeof window !== 'undefined'
      ? window.matchMedia('(min-width: 768px)').matches
      : false);

<<<<<<< HEAD
  const [mdUp, setMdUp]   = useState(isMdUpNow);
  const [open, setOpen]   = useState(isMdUpNow); 
  const [ready, setReady] = useState(false);     

  useEffect(() => { setReady(true); }, []);

=======
  // state
  const [mdUp, setMdUp]   = useState(isMdUpNow); // ≥ md ?
  const [open, setOpen]   = useState(isMdUpNow); // sidebar open?
  const [ready, setReady] = useState(false);     // aktifkan animasi setelah mount

  useEffect(() => { setReady(true); }, []);

  // monitor perubahan breakpoint
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
  useEffect(() => {
    if (typeof window === 'undefined') return;
    const mql = window.matchMedia('(min-width: 768px)');
    const handler = (e) => {
      setMdUp(e.matches);
<<<<<<< HEAD
      setOpen(e.matches); 
=======
      setOpen(e.matches); // desktop => open, mobile => closed
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
    };
    setMdUp(mql.matches);
    setOpen(mql.matches);
    try { mql.addEventListener('change', handler); return () => mql.removeEventListener('change', handler); }
    catch { mql.addListener(handler); return () => mql.removeListener(handler); }
  }, []);

<<<<<<< HEAD
=======
  // helper active
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
  const norm = (p) => (p || '').replace(/\/+$/, '');
  const isActivePrefix = (href) => {
    try {
      const now = norm(window.location.pathname), h = norm(href);
      return now === h || now.startsWith(h + '/');
    } catch { return false; }
  };

  const paths = { dashboard: '/admin/dashboard', data: '/admin/data' };

<<<<<<< HEAD
  const contentPad = open ? 'pl-64 md:pl-64' : 'pl-0 md:pl-20';

=======
  // konten terdorong di mobile saat open
  const contentPad = open ? 'pl-64 md:pl-64' : 'pl-0 md:pl-20';

  // tutup sidebar setelah klik menu di mobile
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
  const handleNavClick = useCallback(() => {
    if (!mdUp) setOpen(false);
  }, [mdUp]);

<<<<<<< HEAD
=======
  // transisi: nonaktif sebelum ready
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
  const transMobile  = ready ? 'transition-transform duration-200' : 'transition-none';
  const transDesktop = ready ? 'md:transition-[width] md:duration-200' : 'md:transition-none';

  return (
    <div className="min-h-screen bg-slate-50">
<<<<<<< HEAD
=======
      {/* Guard CSS: sembunyikan tombol burger di ≥ md apa pun yang terjadi */}
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
      <style>{`
        @media (min-width: 768px) {
          .admin-burger { display: none !important; }
        }
      `}</style>

<<<<<<< HEAD
=======
      {/* SIDEBAR */}
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
      <aside
        className={
          `fixed inset-y-0 left-0 z-40 bg-slate-900 text-slate-200
           w-64 overflow-hidden
           ${open ? 'translate-x-0' : '-translate-x-full'} ${transMobile}
           md:translate-x-0 ${transDesktop} ${open ? 'md:w-64' : 'md:w-20'}`
        }
        aria-label="Sidebar"
      >
        <div className="h-16 border-b border-slate-800 flex items-center justify-center px-4">
          <span className={`${open ? 'inline' : 'hidden md:inline'} font-bold tracking-wide text-lg md:text-lg`}>
            SPPE Kab. Bone
          </span>
        </div>

<<<<<<< HEAD
=======
        {/* NAV (utama + logout di bawah) */}
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        <div className="h-[calc(100%-4rem)] flex flex-col">
          <nav className="px-2 py-3 mt-2 space-y-3">
            <NavItem
              href={paths.dashboard}
              active={isActivePrefix(paths.dashboard)}
              icon={IconHome}
              onClick={handleNavClick}
              open={open}
            >
              Dashboard
            </NavItem>
            <NavItem
              href={paths.data}
              active={isActivePrefix(paths.data)}
              icon={IconTable}
              onClick={handleNavClick}
              open={open}
            >
              Data
            </NavItem>
          </nav>

          <div className="px-2 py-3 border-t border-slate-800/60 mt-auto">
            <Link
              href={route('logout')}
              method="post"
              as="button"
              className="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-left text-sm hover:bg-slate-800/60 text-slate-300"
              onClick={handleNavClick}
            >
              <IconLogout className="h-5 w-5" />
              <span className={`${open ? 'inline' : 'hidden md:inline'}`}>Log Out</span>
            </Link>
          </div>
        </div>
      </aside>

<<<<<<< HEAD
=======
      {/* OVERLAY (mobile) */}
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
      <button
        aria-label="Close sidebar overlay"
        onClick={() => setOpen(false)}
        className={`fixed inset-0 z-30 bg-black/30 md:hidden
          ${ready ? 'transition-opacity' : 'transition-none'}
          ${open ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'}`}
      />

<<<<<<< HEAD
      <section className={`min-h-screen flex flex-col ${contentPad}`}>
        <header className="h-16 bg-white border-b flex items-center justify-between px-4 sticky top-0 z-20">
          <div className="flex items-center gap-2">
=======
      {/* KONTEN */}
      <section className={`min-h-screen flex flex-col ${contentPad}`}>
        <header className="h-16 bg-white border-b flex items-center justify-between px-4 sticky top-0 z-20">
          <div className="flex items-center gap-2">
            {/* 🔒 Render tombol burger HANYA di mobile */}
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
            {!mdUp && (
              <button
                className="admin-burger inline-flex items-center justify-center rounded border px-2 py-1 text-slate-600"
                onClick={() => setOpen(v => !v)}
                aria-label="Toggle sidebar"
                aria-expanded={open}
              >
                <svg className="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path strokeWidth="2" strokeLinecap="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
            )}

            <img src="/images/bps.svg" alt="BPS" className="h-10 w-10 object-contain" loading="lazy" />
            <h1 className="text-lg font-semibold mr-9">
              {header ?? 'Badan Pusat Statistik'}
            </h1>
          </div>

          <div className="flex items-center gap-3">
            <div className="h-9 w-9 rounded-full bg-slate-200 flex items-center justify-center">
              <svg className="h-5 w-5 text-slate-600" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5Zm0 2c-4 0-8 2-8 6v2h16v-2c0-4-4-6-8-6Z" />
              </svg>
            </div>
            <div className="hidden sm:block">
              <div className="text-sm font-medium text-slate-700">{user?.name ?? 'Admin'}</div>
              <div className="text-xs text-slate-500">Administrator</div>
            </div>
          </div>
        </header>

        <main className="flex-1 p-6">
          {children}
        </main>
      </section>
    </div>
  );
}

<<<<<<< HEAD
=======
/* ===== Komponen & Icon ===== */
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
function NavItem({ href, active, icon: I, children, onClick, open }) {
  return (
    <Link
      href={href}
      onClick={onClick}
      className={`flex items-center gap-3 rounded-lg px-3 py-2 text-sm
        ${active ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-300'}`}
    >
      <I className="h-5 w-5" />
      <span className={`${open ? 'inline' : 'hidden md:inline'}`}>{children}</span>
    </Link>
  );
}

function IconHome(props){ return (
  <svg {...props} viewBox="0 0 24 24" fill="none" stroke="currentColor">
    <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h5v-6h4v6h5a1 1 0 001-1V10" />
  </svg>
);}

function IconTable(props){ return (
  <svg {...props} viewBox="0 0 24 24" fill="none" stroke="currentColor">
    <rect x="3" y="4" width="18" height="16" rx="2" strokeWidth="2"/>
    <path d="M3 10h18M9 20V10M15 20V10" strokeWidth="2"/>
  </svg>
);}

function IconLogout(props){ return (
  <svg {...props} viewBox="0 0 24 24" fill="none" stroke="currentColor">
    <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" />
  </svg>
);}
