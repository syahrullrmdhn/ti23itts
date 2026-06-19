<template>
  <aside
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r-4 border-white bg-gray-900 text-white transition-transform duration-300 lg:translate-x-0"
    :class="open ? 'translate-x-0' : '-translate-x-full'"
  >
    <div class="border-b-4 border-white px-5 py-6">
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

    <nav class="flex-1 space-y-4 overflow-y-auto px-5 py-6">
      <NuxtLink
        v-for="item in navigation"
        :key="item.to"
        :to="item.to"
        class="group flex items-center justify-between border-4 px-4 py-3 text-sm font-black uppercase tracking-[0.15em] transition-all"
        :class="route.path === item.to
          ? 'border-gray-900 bg-white text-gray-900 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)] translate-x-1'
          : 'border-transparent text-gray-400 hover:border-white hover:bg-gray-800 hover:text-white'"
        @click="$emit('close')"
      >
        <span>{{ item.label }}</span>
        <span 
          class="text-xl transition-transform duration-300 group-hover:scale-125" 
          :class="route.path === item.to ? 'scale-125' : ''"
        >
          {{ item.icon }}
        </span>
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
        Logout 👋
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
// Pastikan useAdminAuth tersedia di project Nuxt kamu
const auth = useAdminAuth()

const navigation = [
  { to: '/admin/dashboard', label: 'Dashboard', icon: '📊' },
  { to: '/admin/students', label: 'Mahasiswa', icon: '👥' },
  { to: '/admin/episodes', label: 'Episodes', icon: '🎬' },
  { to: '/admin/timeline', label: 'Timeline', icon: '🗓️' },
]
</script>