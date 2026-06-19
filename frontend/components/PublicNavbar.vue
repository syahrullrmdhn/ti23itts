<template>
  <header class="fixed inset-x-0 top-0 z-[120] px-4 py-4 sm:px-6">
    <div class="mx-auto max-w-7xl">
      <div class="border-4 border-white bg-gray-900/90 px-4 py-3 text-white shadow-[8px_8px_0px_0px_rgba(34,197,94,1)] backdrop-blur-md">
        <div class="flex items-center justify-between gap-4">
          <NuxtLink to="/#hero" class="group inline-flex items-center gap-3">
            <span class="inline-flex h-11 w-11 items-center justify-center border-2 border-gray-900 bg-green-500 text-sm font-black text-gray-900 shadow-[3px_3px_0px_0px_rgba(255,255,255,1)] transition-transform group-hover:-translate-y-1">
              TI
            </span>
            <div>
              <p class="text-xs font-black uppercase tracking-[0.2em] text-green-400">Digital Yearbook</p>
              <p class="text-lg font-black uppercase tracking-tight text-white">TI'23 ITTS</p>
            </div>
          </NuxtLink>

          <nav class="hidden items-center gap-3 lg:flex">
            <NuxtLink
              v-for="item in navItems"
              :key="item.to"
              :to="item.to"
              class="border-2 border-transparent px-4 py-2 text-sm font-black uppercase tracking-[0.15em] text-gray-300 transition-all hover:-translate-y-1 hover:border-white hover:bg-white hover:text-gray-900"
            >
              {{ item.label }}
            </NuxtLink>
            <NuxtLink
              to="/admin"
              class="border-2 border-gray-900 bg-green-500 px-4 py-2 text-sm font-black uppercase tracking-[0.15em] text-gray-900 shadow-[3px_3px_0px_0px_rgba(255,255,255,1)] transition-all hover:-translate-y-1"
            >
              Admin
            </NuxtLink>
          </nav>

          <button
            type="button"
            class="flex h-11 w-11 items-center justify-center border-2 border-white bg-white text-gray-900 transition-all hover:-translate-y-1 hover:bg-green-500 lg:hidden"
            aria-label="Buka menu"
            @click="menuOpen = !menuOpen"
          >
            <svg v-if="!menuOpen" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
            </svg>
            <svg v-else viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div v-if="menuOpen" class="mt-4 grid gap-3 border-t-2 border-gray-700 pt-4 lg:hidden">
          <NuxtLink
            v-for="item in navItems"
            :key="item.to"
            :to="item.to"
            class="border-2 border-white bg-gray-800 px-4 py-3 text-sm font-black uppercase tracking-[0.15em] text-white shadow-[3px_3px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 hover:bg-green-500 hover:text-gray-900"
            @click="menuOpen = false"
          >
            {{ item.label }}
          </NuxtLink>
          <NuxtLink
            to="/admin"
            class="border-2 border-gray-900 bg-white px-4 py-3 text-sm font-black uppercase tracking-[0.15em] text-gray-900 shadow-[3px_3px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1"
            @click="menuOpen = false"
          >
            Admin
          </NuxtLink>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
const menuOpen = ref(false)

const navItems = [
  { to: '/#hero', label: 'Home' },
  { to: '/#timebox', label: 'Timeline' },
  { to: '/#episodes', label: 'Episodes' },
  { to: '/#profil', label: 'Cast & Crew' },
  { to: '/cerita', label: 'Cerita' },
]
</script>
