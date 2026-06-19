<template>
  <div class="min-h-screen bg-[#f3f5ef] text-slate-900">
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden"
      @click="sidebarOpen = false"
    />

    <aside
      class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r-4 border-slate-950 bg-slate-950 text-white transition-transform duration-300 lg:translate-x-0"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
      <div class="border-b border-white/10 px-6 py-6">
        <NuxtLink to="/admin/dashboard" class="block rounded-3xl border-2 border-emerald-400 bg-white/5 p-5 shadow-[8px_8px_0px_0px_rgba(16,185,129,0.25)]">
          <p class="text-xs font-black uppercase tracking-[0.35em] text-emerald-300">TI'23 ITTS</p>
          <h1 class="mt-3 text-3xl font-black uppercase leading-none">Admin Room</h1>
          <p class="mt-3 text-sm font-semibold text-slate-300">Kelola konten angkatan dari satu tempat yang lebih rapi.</p>
        </NuxtLink>
      </div>

      <nav class="flex-1 space-y-3 overflow-y-auto px-5 py-6">
        <NuxtLink
          v-for="item in navigation"
          :key="item.to"
          :to="item.to"
          class="flex items-center justify-between rounded-2xl border-2 px-4 py-3 text-sm font-black uppercase tracking-[0.2em] transition duration-200"
          :class="route.path === item.to
            ? 'border-emerald-400 bg-emerald-400 text-slate-950 shadow-[6px_6px_0px_0px_rgba(255,255,255,0.2)]'
            : 'border-white/10 bg-white/5 text-slate-100 hover:border-emerald-300 hover:bg-white/10 hover:text-white'"
          @click="sidebarOpen = false"
        >
          <span>{{ item.label }}</span>
          <span class="text-lg">{{ item.icon }}</span>
        </NuxtLink>
      </nav>

      <div class="border-t border-white/10 px-5 py-5">
        <div class="mb-4 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
          <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Login sebagai</p>
          <p class="mt-1 truncate text-lg font-black">{{ auth.user.value?.name || 'Admin' }}</p>
          <p class="truncate text-sm text-slate-300">{{ auth.user.value?.email }}</p>
        </div>

        <button
          class="flex w-full items-center justify-center rounded-2xl border-2 border-white bg-white px-4 py-3 text-sm font-black uppercase tracking-[0.2em] text-slate-950 transition hover:-translate-y-0.5 hover:bg-emerald-400"
          @click="handleLogout"
        >
          Logout
        </button>
      </div>
    </aside>

    <div class="lg:pl-72">
      <header class="sticky top-0 z-30 border-b-4 border-slate-950 bg-[#f3f5ef]/95 backdrop-blur">
        <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-10">
          <div class="flex items-center gap-3">
            <button
              class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border-2 border-slate-950 bg-white text-xl lg:hidden"
              @click="sidebarOpen = true"
            >
              ☰
            </button>
            <div>
              <p class="text-xs font-black uppercase tracking-[0.3em] text-slate-500">Admin Panel</p>
              <h2 class="text-xl font-black uppercase text-slate-950 sm:text-2xl">{{ currentTitle }}</h2>
            </div>
          </div>

          <div class="hidden rounded-2xl border-2 border-slate-950 bg-white px-4 py-3 shadow-[6px_6px_0px_0px_rgba(15,23,42,0.08)] sm:block">
            <p class="text-xs font-black uppercase tracking-[0.25em] text-slate-500">Session</p>
            <p class="text-sm font-bold text-slate-900">{{ auth.user.value?.name || 'Admin TI23' }}</p>
          </div>
        </div>
      </header>

      <main class="px-4 py-6 sm:px-6 lg:px-10 lg:py-8">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
const route = useRoute()
const sidebarOpen = ref(false)
const auth = useAdminAuth()

const navigation = [
  { to: '/admin/dashboard', label: 'Dashboard', icon: '📊' },
  { to: '/admin/students', label: 'Mahasiswa', icon: '👥' },
  { to: '/admin/episodes', label: 'Episodes', icon: '📺' },
  { to: '/admin/timeline', label: 'Timeline', icon: '📅' },
]

const currentTitle = computed(() => {
  const currentItem = navigation.find((item) => item.to === route.path)
  return currentItem?.label || 'Admin Panel'
})

watch(() => route.fullPath, () => {
  sidebarOpen.value = false
})

const handleLogout = async () => {
  await auth.logout()
  await navigateTo('/admin')
}
</script>
