// resources/js/Layouts/AuthenticatedLayout.jsx
import { Link, usePage } from '@inertiajs/react';
import { useEffect, useMemo, useState, useCallback } from 'react';

export default function AuthenticatedLayout({ header, children }) {
  const page = usePage();
  const user = page.props?.auth?.user ?? {};
  const isMdUpNow = () =>
    (typeof window !== 'undefined'
      ? window.matchMedia('(min-width: 768px)').matches
      : false);

  const [mdUp, setMdUp]   = useState(isMdUpNow);
  const [open, setOpen]   = useState(isMdUpNow);
  const [ready, setReady] = useState(false);
  useEffect(() => { setReady(true); }, []);

  useEffect(() => {
    if (typeof window === 'undefined') return;
    const mql = window.matchMedia('(min-width: 768px)');
    const handler = (e) => {
      setMdUp(e.matches);
      setOpen(e.matches);
    };
    setMdUp(mql.matches);
    setOpen(mql.matches);
    try { mql.addEventListener('change', handler); return () => mql.removeEventListener('change', handler); }
    catch { mql.addListener(handler); return () => mql.removeListener(handler); }
  }, []);

  const currentPath = useMemo(() => {
    try {
      return new URL(page?.url ?? '/', 'http://x').pathname || '/';
    } catch {
      return '/';
    }
  }, [page?.url]);
  const role = useMemo(
    () => (user?.role ? String(user.role).trim().toLowerCase() : ''),
    [user?.role]
  );

  //sesuaikan role
  const paths = useMemo(() => {
    if (role === 'dinas perikanan' || currentPath.startsWith('/perikanan')) {
      return { dashboard: '/perikanan/dashboard', input: '/perikanan/inputdata', data: '/perikanan/data' };
    }
    if (role === 'dinas peternakan' || currentPath.startsWith('/peternakan')) {
      return { dashboard: '/peternakan/dashboard', input: '/peternakan/inputdata', data: '/peternakan/data' };
    }
    if (role === 'dinas perhubungan' || currentPath.startsWith('/perhubungan')) {
      return { dashboard: '/perhubungan/dashboard', input: '/perhubungan/inputdata', data: '/perhubungan/data' };
    }
    if (role === 'dpmptsp' || currentPath.startsWith('/dpmptsp')) {
      return { dashboard: '/dpmptsp/dashboard', input: '/dpmptsp/inputdata', data: '/dpmptsp/data' };
    }
        if (role === 'dinas pertanian' || currentPath.startsWith('/pertanian')) {
      return { dashboard: '/pertanian/dashboard', input: '/pertanian/inputdata', data: '/pertanian/data' };
    }
        if (role === 'dinas pariwisata' || currentPath.startsWith('/pariwisata')) {
      return { dashboard: '/pariwisata/dashboard', input: '/pariwisata/inputdata', data: '/pariwisata/data' };
    }
    if (role === 'admin') {
      return { dashboard: '/dashboard', input: '/admin', data: '/admin/data' };
    }
    return { dashboard: '/dashboard', input: '/dashboard', data: '/dashboard' };
  }, [role, currentPath]);
  const norm = (p) => (p || '').replace(/\/+$/, '');
  const isActiveExact  = (href) => {
    const now = norm(currentPath);
    const h   = norm(href);
    return now === h;
  };
  const isActivePrefix = (href) => {
    const now = norm(currentPath);
    const h   = norm(href);
    return now === h || now.startsWith(h + '/');
  };
  const items = [
    { label: 'Dashboard', href: paths.dashboard, icon: IconHome,  active: isActivePrefix(paths.dashboard) },
    { label: 'Data',      href: paths.data,      icon: IconTable, active: isActivePrefix(paths.data) },
    { label: 'Input Data',href: paths.input,     icon: IconEdit,  active: isActiveExact(paths.input) },
  ];

  const computedHeader =
    header ??
    (role === 'dinas perikanan'
      ? 'Dinas Kelautan & Perikanan'
      : role === 'dinas peternakan'
      ? 'Dinas Peternakan'
      : role === 'dinas perhubungan'
      ? 'Dinas Perhubungan'
      : role === 'dpmptsp'
      ? 'dpmptsp'
      : role === 'dinas pertanian'
      ? 'pertanian'
      : role === 'dinas pariwisata'
      ? 'pariwisata'
      : 'Dashboard');

  //set logo dinas
  const { headerLogoSrc, headerLogoAlt } = useMemo(() => {
    if (currentPath.startsWith('/perikanan') || role === 'dinas perikanan') {
      return { headerLogoSrc: '/images/perikanan.jpg', headerLogoAlt: 'Logo Dinas Kelautan & Perikanan' };
    }
    if (currentPath.startsWith('/peternakan') || role === 'dinas peternakan') {
      return { headerLogoSrc: '/images/bps.svg', headerLogoAlt: 'Logo Dinas Peternakan' };
    }
    if (currentPath.startsWith('/perhubungan') || role === 'dinas perhubungan') {
      return { headerLogoSrc: '/images/perhubungan.jpg', headerLogoAlt: 'Logo Dinas Perhubungan' };
    }
    if (currentPath.startsWith('/dpmptsp') || role === 'dpmptsp') {
      return { headerLogoSrc: '/images/bps.svg', headerLogoAlt: 'Logo dpmptsp' };
    }
    if (currentPath.startsWith('/pertanian') || role === 'dinas pertanian') {
      return { headerLogoSrc: '/images/bps.svg', headerLogoAlt: 'Logo Dinas pertanian' };
    }
    if (currentPath.startsWith('/pariwisata') || role === 'dinas pariwisata') {
      return { headerLogoSrc: '/images/bps.svg', headerLogoAlt: 'Logo Dinas pariwisata' };
    }
    return { headerLogoSrc: '/images/bps.svg', headerLogoAlt: 'Logo Dinas' };
  }, [role, currentPath]);
  const handleNavClick = useCallback(() => { if (!mdUp) setOpen(false); }, [mdUp]);

  const contentPad   = open ? 'pl-56 md:pl-56' : 'pl-0 md:pl-20';
  const transMobile  = ready ? 'transition-transform duration-200' : 'transition-none';
  const transDesktop = ready ? 'md:transition-[width] md:duration-200' : 'md:transition-none';

  return (
    <div className="min-h-screen bg-slate-50">
      <style>{`@media (min-width: 768px){ .auth-burger{display:none!important;} }`}</style>
      <aside
        className={
          `fixed inset-y-0 left-0 z-40 bg-slate-900 text-slate-200
           w-56 overflow-hidden
           ${open ? 'translate-x-0' : '-translate-x-full'} ${transMobile}
           md:translate-x-0 ${transDesktop} ${open ? 'md:w-56' : 'md:w-20'}`
        }
        aria-label="Sidebar"
      >
        <div className="h-16 border-b border-slate-800 flex items-center justify-center px-4">
          <span className={`${open ? 'inline' : 'hidden md:inline'} font-bold tracking-wide text-lg md:text-lg`}>
            SPPE Kab. Bone
          </span>
        </div>

        <div className="h-[calc(100%-4rem)] flex flex-col">
          <nav className="px-2 py-3 mt-2 space-y-2">
            {items.map(({ label, href, icon: I, active }) => (
              <Link
                key={label}
                href={href}
                onClick={handleNavClick}
                className={`flex items-center gap-3 rounded-lg px-3 py-2 text-sm
                  ${active ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-300'}`}
              >
                <I className="h-5 w-5" />
                <span className={`${open ? 'inline' : 'hidden md:inline'}`}>{label}</span>
              </Link>
            ))}
          </nav>

          <div className="px-2 py-3 border-t border-slate-800/60 mt-auto">
            <Link
              href={route('logout')}
              method="post"
              as="button"
              onClick={handleNavClick}
              className="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-left text-sm hover:bg-slate-800/60 text-slate-300"
            >
              <IconLogout className="h-5 w-5" />
              <span className={`${open ? 'inline' : 'hidden md:inline'}`}>Log Out</span>
            </Link>
          </div>
        </div>
      </aside>
      <button
        aria-label="Close sidebar overlay"
        onClick={() => setOpen(false)}
        className={`fixed inset-0 z-30 bg-black/30 md:hidden
          ${ready ? 'transition-opacity' : 'transition-none'}
          ${open ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'}`}
      />

      <section className={`min-h-screen flex flex-col ${contentPad}`}>
        <header className="h-16 bg-white border-b flex items-center justify-between px-4 sticky top-0 z-20">
          <div className="flex items-center gap-3">
            {!mdUp && (
              <button
                className="auth-burger inline-flex items-center justify-center rounded border px-2 py-1 text-slate-600"
                onClick={() => setOpen(v => !v)}
                aria-label="Toggle sidebar"
                aria-expanded={open}
              >
                <svg className="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path strokeWidth="2" strokeLinecap="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
            )}

            <div className="flex items-center gap-2">
              <img
                src={headerLogoSrc}
                alt={headerLogoAlt}
                className="h-9 w-9 object-contain"
                loading="lazy"
                onError={(e) => { e.currentTarget.src = '/images/bone.svg'; }}
              />
              <h1 className="text-lg md:text-lg font-bold">
                {computedHeader}
              </h1>
            </div>
          </div>

          <div className="flex items-center gap-3">
            <div className="h-9 w-9 rounded-full bg-slate-200 flex items-center justify-center">
              <svg className="h-5 w-5 text-slate-600" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5Zm0 2c-4 0-8 2-8 6v2h16v-2c0-4-4-6-8-6Z" />
              </svg>
            </div>
            <div className="hidden sm:block">
              <div className="text-sm font-medium text-slate-700">{user?.name ?? 'User'}</div>
              <div className="text-xs text-slate-500">{user?.role ?? ''}</div>
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
function IconEdit(props){ return (
  <svg {...props} viewBox="0 0 24 24" fill="none" stroke="currentColor">
    <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4 12.5-12.5z" />
  </svg>
);}
function IconLogout(props){ return (
  <svg {...props} viewBox="0 0 24 24" fill="none" stroke="currentColor">
    <path strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" />
  </svg>
);}
