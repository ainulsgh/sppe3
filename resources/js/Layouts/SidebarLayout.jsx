import { Link, usePage } from '@inertiajs/react';
import { useState } from 'react';

export default function SidebarLayout({ title, children }) {
  const user = usePage().props?.auth?.user ?? {};
  const [open, setOpen] = useState(true);

  // bantu cek role
  const hasRole = (...roles) => roles.includes(user?.role);

  return (
    <div className="min-h-screen flex bg-gray-100">
      {/* SIDEBAR */}
      <aside className={`bg-white border-r w-64 shrink-0 ${open ? '' : 'hidden'} md:block`}>
        <div className="px-4 py-4 border-b">
          <Link href="/" className="font-semibold">Ekonomi Monitor</Link>
          <div className="text-xs text-gray-500 mt-1">{user?.name} • {user?.role}</div>
        </div>

        <nav className="p-2 space-y-1">
          <NavItem href={route('dashboard')} active={route().current('dashboard')}>Dashboard</NavItem>

          {hasRole('dinas perikanan') && (
            <NavItem href={route('perikanan.index')} active={route().current('perikanan.*')}>
              Dinas Perikanan
            </NavItem>
          )}

          {hasRole('dinas pertanian') && (
            <NavItem href={route('pertanian.index')} active={route().current('pertanian.*')}>
              Dinas Pertanian
            </NavItem>
          )}

          {hasRole('admin') && (
            <NavItem href={route('admin.index')} active={route().current('admin.index')}>
              Admin
            </NavItem>
          )}

          <div className="mt-4 border-t pt-2">
            <NavItem href={route('profile.edit')} active={route().current('profile.edit')}>
              Profile
            </NavItem>
            <form method="post" action={route('logout')}>
              <button className="w-full text-left px-3 py-2 rounded hover:bg-gray-100 text-gray-700 text-sm">Log Out</button>
            </form>
          </div>
        </nav>
      </aside>

      {/* KONTEN */}
      <section className="flex-1 flex flex-col">
        <header className="bg-white border-b h-14 flex items-center px-3 justify-between">
          <h1 className="font-semibold">{title}</h1>
          <button className="md:hidden px-3 py-1 text-sm border rounded" onClick={()=>setOpen(!open)}>
            {open ? 'Tutup Menu' : 'Buka Menu'}
          </button>
        </header>

        <main className="p-6">{children}</main>
      </section>
    </div>
  );
}

function NavItem({ href, active, children }) {
  return (
    <Link
      href={href}
      className={`block px-3 py-2 rounded text-sm ${
        active ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100'
      }`}
    >
      {children}
    </Link>
  );
}
