<template>
  <div class="space-y-8 text-gray-900">
    
    <!-- ==========================================
         1. WELCOME & ADMIN STATUS
    =========================================== -->
    <section class="grid gap-8 xl:grid-cols-[1.2fr_0.8fr]">
      <!-- Welcome Card -->
      <div class="bg-green-500 border-4 border-gray-900 p-8 sm:p-10 shadow-[8px_8px_0px_0px_rgba(17,24,39,1)]">
        <p class="inline-block bg-white text-gray-900 px-3 py-1 text-xs font-black uppercase tracking-[0.2em] border-2 border-gray-900 shadow-[2px_2px_0px_0px_rgba(17,24,39,1)] mb-6">
          Dashboard Overview
        </p>
        <h1 class="max-w-2xl text-4xl font-black uppercase leading-tight sm:text-5xl tracking-tight">
          Halo, {{ auth.user.value?.name || 'Admin TI23' }}! Siap ngurusin memori hari ini? ✨
        </h1>
        <p class="mt-4 max-w-2xl text-lg font-bold bg-green-400 border-l-4 border-gray-900 p-3">
          Semua ringkasan penting buat website yearbook TI'23 udah disiapin di sini. Tinggal klik-klik aja, beres deh!
        </p>
      </div>

      <!-- Admin Status Card -->
      <div class="bg-white border-4 border-gray-900 p-8 sm:p-10 shadow-[8px_8px_0px_0px_rgba(17,24,39,1)] flex flex-col justify-center">
        <p class="text-sm font-black uppercase tracking-[0.2em] text-gray-500 border-b-4 border-gray-900 pb-2 mb-6">
          Info Admin
        </p>
        <div class="space-y-4">
          <div class="bg-gray-100 border-2 border-gray-900 p-4 shadow-[4px_4px_0px_0px_rgba(17,24,39,1)]">
            <p class="text-xs font-black uppercase tracking-widest text-gray-500">Kamu lagi login pakai:</p>
            <p class="mt-1 text-lg font-black text-gray-900 truncate">{{ auth.user.value?.email || 'admin@ti23.com' }}</p>
          </div>
          <div class="bg-yellow-300 border-2 border-gray-900 p-4 shadow-[4px_4px_0px_0px_rgba(17,24,39,1)]">
            <p class="text-xs font-black uppercase tracking-widest text-gray-900">Status Sistem:</p>
            <p class="mt-1 text-lg font-black text-gray-900 flex items-center gap-2">
              <span>🚀</span> Ready to rock!
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- ==========================================
         2. STATS GRID
    =========================================== -->
    <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 2xl:grid-cols-4">
      <article
        v-for="item in stats"
        :key="item.label"
        class="bg-white border-4 border-gray-900 p-6 shadow-[6px_6px_0px_0px_rgba(17,24,39,1)] transition-all duration-300 hover:-translate-y-1 hover:-translate-x-1 hover:shadow-[10px_10px_0px_0px_rgba(34,197,94,1)] group"
      >
        <div class="flex items-start justify-between gap-4">
          <div class="text-4xl transform group-hover:scale-110 transition-transform duration-300">{{ item.icon }}</div>
          <span class="bg-gray-100 border-2 border-gray-900 px-3 py-1 text-xs font-black uppercase tracking-[0.2em] text-gray-900 shadow-[2px_2px_0px_0px_rgba(17,24,39,1)]">
            {{ item.tag }}
          </span>
        </div>
        <p class="mt-8 text-5xl font-black text-gray-900">{{ item.value }}</p>
        <p class="mt-2 text-sm font-black uppercase tracking-widest text-gray-500 border-t-2 border-gray-200 pt-2">{{ item.label }}</p>
      </article>
    </section>

    <!-- ==========================================
         3. ACTIONS & RECENT ACTIVITY
    =========================================== -->
    <section class="grid gap-8 xl:grid-cols-[1fr_1fr]">
      
      <!-- Quick Actions -->
      <div class="bg-white border-4 border-gray-900 p-8 shadow-[8px_8px_0px_0px_rgba(17,24,39,1)]">
        <p class="text-sm font-black uppercase tracking-[0.2em] text-gray-500 border-b-4 border-gray-900 pb-2 mb-6">
          Jalan Pintas
        </p>
        <div class="grid gap-5 sm:grid-cols-2">
          <NuxtLink
            v-for="action in actions"
            :key="action.to"
            :to="action.to"
            class="group bg-gray-900 border-4 border-gray-900 p-6 text-white transition-all hover:bg-green-500 hover:text-gray-900 hover:-translate-y-1 hover:-translate-x-1 hover:shadow-[6px_6px_0px_0px_rgba(17,24,39,1)]"
          >
            <div class="bg-white w-12 h-12 flex items-center justify-center border-2 border-gray-900 shadow-[2px_2px_0px_0px_rgba(17,24,39,1)] text-2xl mb-4 group-hover:bg-gray-900 group-hover:border-white">
              {{ action.icon }}
            </div>
            <p class="text-lg font-black uppercase tracking-tight">{{ action.title }}</p>
            <p class="mt-2 text-sm font-bold text-gray-400 group-hover:text-gray-800 leading-snug">{{ action.description }}</p>
          </NuxtLink>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="bg-white border-4 border-gray-900 p-8 shadow-[8px_8px_0px_0px_rgba(17,24,39,1)]">
        <p class="text-sm font-black uppercase tracking-[0.2em] text-gray-500 border-b-4 border-gray-900 pb-2 mb-6">
          Aktivitas Terakhir
        </p>
        <div class="space-y-4">
          <article
            v-for="activity in recentActivities"
            :key="activity.id"
            class="flex items-start gap-4 bg-gray-50 border-2 border-gray-900 p-4 shadow-[4px_4px_0px_0px_rgba(17,24,39,1)] hover:bg-white transition-colors"
          >
            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center border-2 border-gray-900 bg-green-400 text-2xl shadow-[2px_2px_0px_0px_rgba(17,24,39,1)]">
              {{ activity.icon }}
            </div>
            <div class="min-w-0 flex-1 pt-1">
              <p class="text-base font-black text-gray-900 uppercase tracking-tight">{{ activity.title }}</p>
              <p class="mt-1 text-sm font-bold text-gray-600">{{ activity.description }}</p>
            </div>
            <p class="text-xs font-black uppercase tracking-widest text-gray-400 pt-1 whitespace-nowrap">{{ activity.time }}</p>
          </article>
          
          <div v-if="recentActivities.length === 0" class="text-center py-8 border-2 border-dashed border-gray-300 bg-gray-50">
            <p class="font-bold text-gray-500">Belum ada aktivitas hari ini. Yuk mulai kerja!</p>
          </div>
        </div>
      </div>
      
    </section>

    <section class="bg-white border-4 border-gray-900 p-8 shadow-[8px_8px_0px_0px_rgba(17,24,39,1)]">
      <div class="flex flex-col gap-4 border-b-4 border-gray-900 pb-6 md:flex-row md:items-start md:justify-between">
        <div>
          <p class="text-sm font-black uppercase tracking-[0.2em] text-gray-500">Hero Background</p>
          <h2 class="mt-3 text-3xl font-black uppercase">Foto Belakang Hero Section</h2>
          <p class="mt-2 max-w-3xl font-bold text-gray-600">
            Default-nya pakai foto random dari Unsplash. Begitu kamu upload di sini, HeroSection langsung pakai foto custom ini.
          </p>
        </div>
        <label class="inline-flex cursor-pointer items-center justify-center border-4 border-gray-900 bg-gray-900 px-6 py-3 font-black uppercase text-white shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] hover:bg-green-500 hover:text-gray-900">
          {{ heroUploading ? 'Mengunggah...' : '+ Upload Foto Hero' }}
          <input
            type="file"
            accept="image/jpeg,image/png,image/webp"
            class="hidden"
            :disabled="heroUploading"
            @change="uploadHeroPhoto"
          >
        </label>
      </div>

      <div v-if="heroPhotos.length" class="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <article
          v-for="photo in heroPhotos"
          :key="photo.id"
          class="overflow-hidden border-4 border-gray-900 bg-gray-50 shadow-[6px_6px_0px_0px_rgba(17,24,39,1)]"
        >
          <div class="aspect-video bg-gray-900">
            <img :src="mediaUrl(photo.path)" alt="Hero background" class="h-full w-full object-cover">
          </div>
          <div class="flex items-center justify-between gap-3 border-t-4 border-gray-900 p-4">
            <p class="text-sm font-black uppercase text-gray-500">Custom Hero</p>
            <button
              class="border-2 border-gray-900 bg-white px-3 py-2 text-xs font-black uppercase hover:bg-red-500 hover:text-white disabled:opacity-50"
              :disabled="heroDeletingId === photo.id"
              @click="deleteHeroPhoto(photo.id)"
            >
              {{ heroDeletingId === photo.id ? 'Menghapus...' : 'Hapus' }}
            </button>
          </div>
        </article>
      </div>

      <div v-else class="mt-6 border-4 border-dashed border-gray-300 bg-gray-50 p-8 text-center">
        <p class="text-lg font-black uppercase text-gray-900">Belum ada foto hero custom.</p>
        <p class="mt-2 font-bold text-gray-600">Website masih pakai koleksi foto random dari Unsplash sampai kamu upload sendiri.</p>
      </div>
    </section>

    <div
      v-if="errorMessage"
      class="fixed bottom-6 right-6 z-[90] max-w-md border-4 border-gray-900 bg-red-500 p-5 font-bold text-white shadow-[6px_6px_0px_0px_rgba(17,24,39,1)]"
    >
      {{ errorMessage }}
      <button class="ml-3 font-black" @click="errorMessage = ''">×</button>
    </div>
  </div>
</template>

<script setup lang="ts">
// Menyambungkan halaman ini dengan layouts/admin.vue
definePageMeta({
  layout: 'admin',
  middleware: 'admin-auth',
})

const auth = useAdminAuth()
const config = useRuntimeConfig()
const { mediaUrl } = useApiMedia()

const stats = ref([
  { label: 'Total Mahasiswa', value: 42, icon: '👥', tag: 'Active' },
  { label: 'Total Episodes', value: 8, icon: '📺', tag: 'Episodes' },
  { label: 'Total Semester', value: 6, icon: '📅', tag: 'Timeline' },
  { label: 'Total Dosen', value: 15, icon: '👨‍🏫', tag: 'Lecturers' },
])

const actions = [
  { to: '/admin/students', title: 'Kelola Mahasiswa', description: 'Masukin data anak-anak baru atau update profil mereka.', icon: '🧑‍🎓' },
  { to: '/admin/episodes', title: 'Kelola Episode', description: 'Atur aib, drama, dan inside jokes angkatan di sini.', icon: '🎬' },
  { to: '/admin/timeline', title: 'Update Timeline', description: 'Catet cerita semester dan statistik perjalanan bareng.', icon: '🗓️' },
  { to: '/', title: 'Lihat Website', description: 'Cek langsung hasil kerjaan kamu di web publik.', icon: '👀' },
]

const recentActivities = ref([
  {
    id: 1,
    icon: '✨',
    title: 'Mahasiswa baru ditambah',
    description: 'Ahmad Rizky sukses masuk radar database.',
    time: '2 jam lalu',
  },
  {
    id: 2,
    icon: '🐒',
    title: 'Episode baru rilis!',
    description: 'Aib "Monkey Business" berhasil di-publish.',
    time: '5 jam lalu',
  },
  {
    id: 3,
    icon: '🔥',
    title: 'Timeline di-update',
    description: 'Data perjalanan semester 5 udah disegarkan.',
    time: '1 hari lalu',
  },
])
const heroPhotos = ref<Array<{ id: number; path: string }>>([])
const heroUploading = ref(false)
const heroDeletingId = ref<number | null>(null)
const errorMessage = ref('')

type DashboardResponse = {
  stats: {
    students: number
    episodes: number
    semesters: number
    lecturers: number
  }
  recentActivities: Array<{
    id: number
    icon: string
    title: string
    description: string
    time: string
  }>
}

const requestHeaders = computed(() => ({
  Authorization: `Bearer ${auth.token.value}`,
}))

const apiError = (error: any) => {
  const validationErrors = error?.data?.errors
  if (validationErrors) {
    return Object.values(validationErrors).flat().join('\n')
  }

  return error?.data?.message || error?.message || 'Terjadi kesalahan saat memproses foto hero.'
}

const loadHeroPhotos = async () => {
  heroPhotos.value = await $fetch<Array<{ id: number; path: string }>>(`${config.public.apiBase}/hero-photos`)
}

const uploadHeroPhoto = async (event: Event) => {
  const input = event.target as HTMLInputElement
  const file = input.files?.[0]
  if (!file) return

  heroUploading.value = true
  errorMessage.value = ''

  try {
    const payload = new FormData()
    payload.append('photo', file)

    await $fetch(`${config.public.apiBase}/hero-photos`, {
      method: 'POST',
      headers: requestHeaders.value,
      body: payload,
    })

    await loadHeroPhotos()
  } catch (error) {
    errorMessage.value = apiError(error)
  } finally {
    heroUploading.value = false
    input.value = ''
  }
}

const deleteHeroPhoto = async (id: number) => {
  heroDeletingId.value = id
  errorMessage.value = ''

  try {
    await $fetch(`${config.public.apiBase}/hero-photos/${id}`, {
      method: 'DELETE',
      headers: requestHeaders.value,
    })

    heroPhotos.value = heroPhotos.value.filter(photo => photo.id !== id)
  } catch (error) {
    errorMessage.value = apiError(error)
  } finally {
    heroDeletingId.value = null
  }
}

onMounted(async () => {
  auth.restore()

  if (!auth.token.value) {
    return
  }

  try {
    const data = await $fetch<DashboardResponse>(`${config.public.apiBase}/dashboard`, {
      headers: requestHeaders.value,
    })

    stats.value = [
      { ...stats.value[0], value: data.stats.students },
      { ...stats.value[1], value: data.stats.episodes },
      { ...stats.value[2], value: data.stats.semesters },
      { ...stats.value[3], value: data.stats.lecturers },
    ]

    recentActivities.value = data.recentActivities
    await loadHeroPhotos()
  } catch (error) {
    errorMessage.value = apiError(error)
  }
})
</script>
