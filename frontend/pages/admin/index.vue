<template>
  <div class="relative min-h-screen overflow-hidden bg-gray-900 font-sans selection:bg-green-500 selection:text-gray-900 flex items-center justify-center p-4 sm:p-8">
    
    <!-- Background Pattern (Dot Grid) -->
    <div class="absolute inset-0 z-0 opacity-20" style="background-image: radial-gradient(#22c55e 2px, transparent 2px); background-size: 32px 32px;"></div>

    <div class="relative z-10 mx-auto flex w-full max-w-7xl items-center">
      <div class="grid w-full gap-8 lg:gap-0 lg:grid-cols-[1.15fr_0.85fr] items-center">
        
        <!-- ==========================================
             LEFT SECTION (Info Panel)
        =========================================== -->
        <section class="bg-green-500 border-4 border-white p-8 sm:p-12 shadow-[12px_12px_0px_0px_rgba(255,255,255,1)] z-10 lg:translate-x-4 lg:-translate-y-4">
          
          <div class="mb-6">
            <span class="inline-block bg-white text-gray-900 px-4 py-2 text-sm font-black border-4 border-gray-900 shadow-[4px_4px_0px_0px_rgba(17,24,39,1)] uppercase tracking-[0.2em]">
              TI'23 ITTS
            </span>
          </div>
          
          <!-- Copywriting yang lebih hangat -->
          <h1 class="mt-5 max-w-xl text-5xl font-black uppercase leading-none text-gray-900 sm:text-6xl tracking-tight">
            Halo Admin! Siap ngurusin memori TI'23 hari ini? ✨
          </h1>
          
          <p class="mt-6 max-w-2xl text-lg font-bold text-gray-900 border-l-8 border-gray-900 bg-green-400 p-4">
            Masuk dulu yuk buat update data anak-anak, nambahin perjalanan di timeline, atau nyimpen aib baru angkatan kita. Santai aja, semuanya udah dibikin gampang buat kamu.
          </p>

          <!-- Stats Grid -->
          <div class="mt-10 grid gap-4 sm:grid-cols-3">
            <div class="border-4 border-gray-900 bg-white p-5 text-center shadow-[4px_4px_0px_0px_rgba(17,24,39,1)] hover:-translate-y-1 transition-transform">
              <p class="text-4xl font-black text-gray-900">42</p>
              <p class="mt-2 text-xs font-black uppercase tracking-wider text-gray-600 border-t-2 border-gray-200 pt-2">Mahasiswa Aktif</p>
            </div>
            <div class="border-4 border-gray-900 bg-white p-5 text-center shadow-[4px_4px_0px_0px_rgba(17,24,39,1)] hover:-translate-y-1 transition-transform">
              <p class="text-4xl font-black text-gray-900">8</p>
              <p class="mt-2 text-xs font-black uppercase tracking-wider text-gray-600 border-t-2 border-gray-200 pt-2">Inside Episodes</p>
            </div>
            <div class="border-4 border-gray-900 bg-white p-5 text-center shadow-[4px_4px_0px_0px_rgba(17,24,39,1)] hover:-translate-y-1 transition-transform">
              <p class="text-4xl font-black text-gray-900">6</p>
              <p class="mt-2 text-xs font-black uppercase tracking-wider text-gray-600 border-t-2 border-gray-200 pt-2">Semester Story</p>
            </div>
          </div>
        </section>

        <!-- ==========================================
             RIGHT SECTION (Login Form)
        =========================================== -->
        <section class="bg-white border-4 border-gray-900 p-8 sm:p-10 shadow-[12px_12px_0px_0px_rgba(34,197,94,1)] z-20">
          
          <div class="flex items-start justify-between gap-4 border-b-4 border-gray-900 pb-6 mb-8">
            <div>
              <p class="text-sm font-black uppercase tracking-[0.2em] text-green-600">Admin Login</p>
              <h2 class="mt-1 text-4xl font-black uppercase text-gray-900">Masuk Dashboard</h2>
            </div>
            <div class="border-4 border-gray-900 bg-green-500 px-4 py-2 text-sm font-black uppercase text-gray-900 shadow-[4px_4px_0px_0px_rgba(17,24,39,1)]">
              Secure
            </div>
          </div>

          <form class="space-y-6" @submit.prevent="handleLogin">
            <!-- Username Input -->
            <div>
              <label for="login" class="mb-2 block text-sm font-black uppercase tracking-[0.1em] text-gray-900">
                Username atau Email
              </label>
              <input
                id="login"
                v-model="form.login"
                type="text"
                autocomplete="username"
                placeholder="Masukkan username kamu"
                class="w-full border-4 border-gray-900 bg-gray-50 px-5 py-4 text-lg font-bold text-gray-900 outline-none transition-all placeholder:text-gray-400 focus:bg-white focus:-translate-y-1 focus:-translate-x-1 focus:shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]"
              />
            </div>

            <!-- Password Input -->
            <div>
              <label for="password" class="mb-2 block text-sm font-black uppercase tracking-[0.1em] text-gray-900">
                Password
              </label>
              <input
                id="password"
                v-model="form.password"
                type="password"
                autocomplete="current-password"
                placeholder="Masukkan password"
                class="w-full border-4 border-gray-900 bg-gray-50 px-5 py-4 text-lg font-bold text-gray-900 outline-none transition-all placeholder:text-gray-400 focus:bg-white focus:-translate-y-1 focus:-translate-x-1 focus:shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]"
              />
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              class="w-full border-4 border-gray-900 bg-gray-900 px-5 py-5 text-lg font-black uppercase tracking-[0.15em] text-white transition-all shadow-[6px_6px_0px_0px_rgba(34,197,94,1)] hover:bg-gray-800 hover:shadow-[2px_2px_0px_0px_rgba(34,197,94,1)] hover:translate-y-1 hover:translate-x-1 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:translate-y-0 disabled:hover:translate-x-0 disabled:hover:shadow-[6px_6px_0px_0px_rgba(34,197,94,1)] mt-4"
              :disabled="isSubmitting"
            >
              {{ isSubmitting ? 'MEMPROSES...' : 'YUK MASUK!' }}
            </button>
          </form>
          
        </section>
      </div>
    </div>

    <div
      v-if="errorModal.open"
      class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/70 p-4"
      @click.self="closeErrorModal"
    >
      <div class="w-full max-w-lg border-4 border-gray-900 bg-white p-6 shadow-[12px_12px_0px_0px_rgba(239,68,68,1)] sm:p-8">
        <div class="flex items-start justify-between gap-4 border-b-4 border-gray-900 pb-4">
          <div>
            <p class="text-sm font-black uppercase tracking-[0.2em] text-red-500">Login Gagal</p>
            <h3 class="mt-1 text-3xl font-black uppercase text-gray-900">Perlu Dicek Lagi</h3>
          </div>
          <button
            type="button"
            class="border-4 border-gray-900 bg-yellow-300 px-3 py-2 text-sm font-black uppercase text-gray-900 shadow-[4px_4px_0px_0px_rgba(17,24,39,1)]"
            @click="closeErrorModal"
          >
            Tutup
          </button>
        </div>

        <div class="mt-6 border-4 border-gray-900 bg-red-500 p-5 text-white shadow-[4px_4px_0px_0px_rgba(17,24,39,1)]">
          <p class="text-sm font-black uppercase tracking-[0.15em]">Error Detail</p>
          <p class="mt-3 text-base font-bold leading-7">
            {{ errorModal.message }}
          </p>
        </div>

        <div class="mt-6 flex justify-end">
          <button
            type="button"
            class="border-4 border-gray-900 bg-gray-900 px-5 py-3 text-sm font-black uppercase tracking-[0.15em] text-white shadow-[4px_4px_0px_0px_rgba(34,197,94,1)]"
            @click="closeErrorModal"
          >
            Oke, ngerti
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'admin-guest',
})

const auth = useAdminAuth()

const form = reactive({
  login: '',
  password: '',
})
const isSubmitting = ref(false)
const errorModal = reactive({
  open: false,
  message: '',
})

const closeErrorModal = () => {
  errorModal.open = false
}

const openErrorModal = (message: string) => {
  errorModal.message = message
  errorModal.open = true
}

const extractErrorMessage = (error: any) => {
  const validationErrors = error?.data?.errors

  if (validationErrors && typeof validationErrors === 'object') {
    const firstMessage = Object.values(validationErrors)
      .flat()
      .find((message) => typeof message === 'string')

    if (typeof firstMessage === 'string') {
      return firstMessage
    }
  }

  return error?.data?.message || 'Waduh, login gagal nih. Coba cek lagi username atau passwordnya ya.'
}

const handleLogin = async () => {
  closeErrorModal()
  isSubmitting.value = true

  try {
    await auth.login({
      login: form.login,
      password: form.password,
    })

    await navigateTo('/admin/dashboard')
  } catch (error: any) {
    openErrorModal(extractErrorMessage(error))
  } finally {
    isSubmitting.value = false
  }
}
</script>
