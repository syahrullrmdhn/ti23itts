<template>
  <aside
    class="fixed inset-y-0 left-0 z-50 flex w-[85vw] max-w-72 flex-col border-r-4 border-white bg-gray-900 text-white transition-transform duration-300 overscroll-contain lg:w-72 lg:translate-x-0"
    :class="open ? 'translate-x-0 lg:translate-x-0' : '-translate-x-full lg:translate-x-0'"
  >
    <div class="border-b-4 border-white px-5 py-6">
      <div class="mb-4 flex items-center justify-between lg:hidden">
        <p class="text-xs font-black uppercase tracking-[0.2em] text-green-400">Navigasi Admin</p>
        <button
          type="button"
          class="flex h-11 w-11 items-center justify-center border-2 border-white bg-white text-gray-900 transition-colors hover:bg-green-500"
          @click="$emit('close')"
        >
          <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <NuxtLink
        to="/admin/dashboard"
        class="block border-4 border-gray-900 bg-green-500 p-5 shadow-[6px_6px_0px_0px_rgba(255,255,255,1)] transition-transform hover:-translate-y-1 hover:-translate-x-1 hover:shadow-[8px_8px_0px_0px_rgba(255,255,255,1)]"
        @click="$emit('close')"
      >
        <p class="text-xs font-black uppercase tracking-[0.2em] text-gray-900">TI'23 ITTS</p>
        <h1 class="mt-2 text-3xl font-black uppercase leading-none text-gray-900">Markas Admin</h1>
        <p class="mt-3 text-sm font-bold text-gray-800 border-t-2 border-gray-900 pt-2 leading-snug">
          Kendali penuh angkatan ada di sini.
        </p>
      </NuxtLink>
    </div>

    <nav class="flex-1 space-y-4 overflow-y-auto px-5 py-6 pb-24">
      <NuxtLink
        v-for="item in navigation"
        :key="item.to"
        :to="item.to"
        class="group flex items-center justify-between gap-3 border-4 px-4 py-3 text-sm font-black uppercase tracking-[0.15em] transition-all"
        :class="route.path === item.to
          ? 'border-gray-900 bg-white text-gray-900 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)] translate-x-1'
          : 'border-transparent text-gray-400 hover:border-white hover:bg-gray-800 hover:text-white'"
        @click="$emit('close')"
      >
        <span class="flex min-w-0 items-center gap-3">
          <span
            class="flex h-10 w-10 shrink-0 items-center justify-center border-2 transition-all duration-300"
            :class="route.path === item.to ? 'border-gray-900 bg-green-500 text-gray-900' : 'border-gray-700 bg-gray-900 text-gray-300 group-hover:border-white group-hover:bg-white group-hover:text-gray-900'"
          >
            <svg v-if="item.icon === 'dashboard'" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5h8V3H3v10.5Zm10 7.5h8v-6.5h-8V21Zm0-16.5v6.5h8V4.5h-8ZM3 21h8v-4.5H3V21Z" />
            </svg>
            <svg v-else-if="item.icon === 'students'" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 0 0-3-3.87" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
            <svg v-else-if="item.icon === 'episodes'" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2">
              <rect x="3" y="4" width="18" height="16" rx="2" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M7 4v16M17 4v16M3 9h4M3 15h4M17 9h4M17 15h4" />
            </svg>
            <svg v-else-if="item.icon === 'timeline'" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 3v4M18 3v4M3 11h18" />
              <rect x="3" y="5" width="18" height="16" rx="2" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 15h3M13 15h3M8 18h8" />
            </svg>
            <svg v-else-if="item.icon === 'posts'" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 4h14v16H5z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 8h8M8 12h8M8 16h5" />
            </svg>
            <svg v-else-if="item.icon === 'audit'" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 11.25 11 13l4-4m5 3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <svg v-else viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="8.5" cy="7" r="4" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M20 8v6M17 11h6" />
            </svg>
          </span>
          <span class="truncate">{{ item.label }}</span>
        </span>

        <svg
          viewBox="0 0 24 24"
          class="h-4 w-4 shrink-0 transition-transform duration-300 group-hover:translate-x-1"
          fill="none"
          stroke="currentColor"
          stroke-width="2.2"
        >
          <path stroke-linecap="round" stroke-linejoin="round" d="m9 6 6 6-6 6" />
        </svg>
      </NuxtLink>
    </nav>

    <div class="border-t-4 border-white px-5 py-6">
      <div class="mb-5 border-4 border-gray-700 bg-gray-800 px-4 py-3 relative">
        <span class="absolute -top-3 left-3 bg-white px-2 py-0.5 text-[10px] font-black uppercase tracking-widest text-gray-900 border-2 border-gray-900">
          Lagi Login
        </span>
        <p class="mt-1 truncate text-lg font-black text-white">{{ auth.user.value?.name || 'Admin' }}</p>
        <p class="truncate text-xs font-bold text-gray-400 uppercase tracking-wider mt-1">{{ auth.user.value?.email }}</p>
      </div>

      <button
        class="flex w-full items-center justify-center border-4 border-gray-900 bg-white px-4 py-3 text-sm font-black uppercase tracking-[0.2em] text-gray-900 shadow-[6px_6px_0px_0px_rgba(239,68,68,1)] transition-all hover:-translate-y-1 hover:-translate-x-1 hover:shadow-[8px_8px_0px_0px_rgba(239,68,68,1)] active:translate-y-0 active:translate-x-0 active:shadow-[0px_0px_0px_0px_rgba(239,68,68,1)]"
        @click="$emit('logout')"
      >
        <svg viewBox="0 0 24 24" class="mr-2 h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
          <path stroke-linecap="round" stroke-linejoin="round" d="m16 17 5-5-5-5" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 12H9" />
        </svg>
        Logout
      </button>
    </div>
  </aside>
</template>

<script setup lang="ts">
defineProps<{
  open: boolean
}>()

defineEmits<{
  close: []
  logout: []
}>()

const route = useRoute()
const auth = useAdminAuth()

const navigation = [
  { to: '/admin/dashboard', label: 'Dashboard', icon: 'dashboard' },
  { to: '/admin/students', label: 'Mahasiswa', icon: 'students' },
  { to: '/admin/episodes', label: 'Episodes', icon: 'episodes' },
  { to: '/admin/posts', label: 'Cerita', icon: 'posts' },
  { to: '/admin/timeline', label: 'Timeline', icon: 'timeline' },
  { to: '/admin/users', label: 'Users', icon: 'users' },
  { to: '/admin/audit-logs', label: 'Audit Log', icon: 'audit' },
]
</script>
