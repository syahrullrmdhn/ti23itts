<template>
  <div class="min-h-screen bg-[#f3f5ef] text-slate-900">
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden"
      @click="sidebarOpen = false"
    />

    <AdminSidebar
      :open="sidebarOpen"
      @close="sidebarOpen = false"
      @logout="handleLogout"
    />

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

const currentTitle = computed(() => {
  if (route.path === '/admin/dashboard') {
    return 'Dashboard'
  }

  if (route.path === '/admin/students') {
    return 'Mahasiswa'
  }

  if (route.path === '/admin/episodes') {
    return 'Episodes'
  }

  if (route.path === '/admin/timeline') {
    return 'Timeline'
  }

  return 'Admin Panel'
})

watch(() => route.fullPath, () => {
  sidebarOpen.value = false
})

const handleLogout = async () => {
  await auth.logout()
  await navigateTo('/admin')
}
</script>
