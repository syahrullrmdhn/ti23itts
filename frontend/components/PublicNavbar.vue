<template>
  <header class="fixed inset-x-0 top-0 z-[120] border-b border-white/10 bg-black/90 px-4 py-3 text-white backdrop-blur-xl sm:px-6">
    <div class="mx-auto max-w-[1500px]">
      <div class="relative">
        <div class="pointer-events-none absolute inset-x-[18%] -top-6 hidden h-24 overflow-hidden opacity-70 lg:block" aria-hidden="true">
          <div class="absolute inset-0 bg-[radial-gradient(circle_at_15%_50%,rgba(34,197,94,0.65),transparent_24%),radial-gradient(circle_at_48%_45%,rgba(59,130,246,0.55),transparent_27%),radial-gradient(circle_at_80%_50%,rgba(250,204,21,0.45),transparent_25%)] blur-2xl"></div>
        </div>

        <div class="relative flex items-center justify-between gap-3">
          <NuxtLink to="/#hero" class="group inline-flex items-center gap-3">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-green-300/50 bg-green-500 text-sm font-black text-gray-950 shadow-[0_0_24px_rgba(34,197,94,0.28)] transition-transform group-hover:-translate-y-1">
              TI
            </span>
            <div class="hidden sm:block">
              <p class="text-xs font-black uppercase tracking-[0.2em] text-green-400">Digital Yearbook</p>
              <p class="text-lg font-black uppercase tracking-tight text-white">TI'23 ITTS</p>
            </div>
          </NuxtLink>

          <nav class="relative hidden items-center gap-1 overflow-hidden rounded-2xl border border-white/15 bg-white/[0.08] p-1.5 shadow-[inset_0_1px_0_rgba(255,255,255,0.12),0_12px_40px_rgba(0,0,0,0.35)] backdrop-blur-2xl lg:flex">
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-green-400/10 via-blue-400/10 to-yellow-300/10" aria-hidden="true"></div>
            <NuxtLink
              v-for="item in navItems"
              :key="item.to"
              :to="item.to"
              class="relative rounded-xl border border-transparent px-4 py-2.5 text-sm font-bold text-gray-200 transition-all hover:border-white/15 hover:bg-white/15 hover:text-white"
            >
              {{ item.label }}
            </NuxtLink>
          </nav>

          <div class="flex items-center gap-2">
            <NuxtLink
              to="/admin"
              class="hidden rounded-full border border-white/20 bg-white px-5 py-2.5 text-sm font-black text-gray-950 shadow-[0_8px_24px_rgba(255,255,255,0.12)] transition-all hover:-translate-y-0.5 hover:bg-green-400 lg:inline-flex"
            >
              Login
            </NuxtLink>

            <button
              type="button"
              class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/20 bg-white/10 text-white shadow-[inset_0_1px_0_rgba(255,255,255,0.15)] backdrop-blur-xl transition-all hover:bg-white hover:text-gray-900 lg:hidden"
              :aria-label="menuOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi'"
              :aria-expanded="menuOpen"
              aria-controls="mobile-navigation"
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
        </div>

        <div
          v-if="menuOpen"
          id="mobile-navigation"
          class="mt-3 grid max-h-[calc(100dvh-6rem)] gap-2 overflow-y-auto rounded-2xl border border-white/15 bg-gray-950/75 p-3 shadow-[0_18px_50px_rgba(0,0,0,0.55)] backdrop-blur-2xl lg:hidden"
        >
          <NuxtLink
            v-for="item in navItems"
            :key="item.to"
            :to="item.to"
            class="rounded-xl border border-white/10 bg-white/[0.07] px-4 py-3 text-sm font-bold text-white transition-all hover:bg-white/15"
            @click="menuOpen = false"
          >
            {{ item.label }}
          </NuxtLink>
          <NuxtLink
            to="/admin"
            class="rounded-xl bg-white px-4 py-3 text-sm font-black text-gray-950 transition-all hover:bg-green-400"
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
