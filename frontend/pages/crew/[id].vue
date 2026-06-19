<template>
  <div class="min-h-screen bg-gray-900 px-4 py-12 text-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-6xl">
      <NuxtLink
        to="/#profil"
        class="inline-flex items-center gap-2 border-4 border-white bg-white px-5 py-3 font-black uppercase text-gray-900 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1"
      >
        ← Kembali ke Cast & Crew
      </NuxtLink>

      <div v-if="pending" class="mt-10 border-4 border-white bg-gray-800 p-10 text-center font-black uppercase text-gray-300 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]">
        Memuat profil crew...
      </div>

      <div v-else-if="student" class="mt-10 grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">
        <section v-reveal="'left'" class="overflow-hidden border-4 border-white bg-gray-800 shadow-[10px_10px_0px_0px_rgba(34,197,94,1)]">
          <div class="relative aspect-square overflow-hidden border-b-4 border-white bg-gray-900">
            <img
              :src="activePhoto"
              :alt="student.name"
              class="h-full w-full object-cover"
            >
            <span class="absolute right-4 top-4 inline-block rotate-3 border-2 border-gray-900 bg-green-500 px-3 py-1 text-xs font-black uppercase tracking-wider text-gray-900 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
              {{ student.status }}
            </span>
          </div>
          <div class="grid grid-cols-2 border-t-4 border-white">
            <button
              type="button"
              class="border-r-2 border-white px-4 py-4 text-sm font-black uppercase tracking-[0.2em] transition-colors"
              :class="showAltPhoto ? 'bg-gray-700 text-gray-300' : 'bg-white text-gray-900'"
              @click="showAltPhoto = false"
            >
              Foto Utama
            </button>
            <button
              type="button"
              class="px-4 py-4 text-sm font-black uppercase tracking-[0.2em] transition-colors"
              :class="showAltPhoto ? 'bg-green-500 text-gray-900' : 'bg-gray-900 text-green-400'"
              @click="showAltPhoto = true"
            >
              Mode Aib
            </button>
          </div>
        </section>

        <section class="space-y-6">
          <div v-reveal="'up'" class="border-4 border-white bg-white p-6 text-gray-900 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)] sm:p-8">
            <p class="text-sm font-black uppercase tracking-[0.25em] text-gray-500">Crew Profile</p>
            <h1 class="mt-4 text-4xl font-black uppercase tracking-tight sm:text-5xl">{{ student.name }}</h1>
            <div class="mt-4 inline-block -rotate-1 border-2 border-gray-900 bg-green-500 px-4 py-2 text-sm font-black uppercase tracking-wider">
              {{ student.role }}
            </div>
            <p class="mt-6 text-lg font-bold leading-relaxed text-gray-700">
              {{ student.fun_fact || 'Setiap orang punya cerita seru sendiri, dan crew ini jelas bagian penting dari perjalanan TI23.' }}
            </p>
          </div>

          <div v-reveal="'up'" class="border-4 border-white bg-gray-800 p-6 shadow-[8px_8px_0px_0px_rgba(255,255,255,0.15)] sm:p-8">
            <p class="inline-block -rotate-1 border-2 border-green-500 bg-gray-900 px-3 py-1 text-xs font-black uppercase tracking-[0.25em] text-green-400">
              Pesan ke Teman-teman
            </p>
            <blockquote class="mt-5 border-l-4 border-green-500 bg-gray-900/70 p-5 text-lg font-bold leading-relaxed text-gray-200">
              “{{ student.message || 'Terima kasih sudah tumbuh bareng. Semoga setelah semua drama, tugas, dan aib yang lewat, kita tetap saling dukung sampai kapan pun.' }}”
            </blockquote>
          </div>

          <div v-reveal="'right'" class="grid gap-4 sm:grid-cols-2">
            <div class="border-4 border-white bg-gray-800 p-5 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
              <p class="text-xs font-black uppercase tracking-[0.25em] text-gray-400">Status</p>
              <p class="mt-3 text-2xl font-black uppercase">{{ student.status }}</p>
            </div>
            <div class="border-4 border-white bg-gray-800 p-5 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
              <p class="text-xs font-black uppercase tracking-[0.25em] text-gray-400">Angkatan</p>
              <p class="mt-3 text-2xl font-black uppercase">TI 2023</p>
            </div>
          </div>
        </section>
      </div>

      <div v-else class="mt-10 border-4 border-red-500 bg-white p-10 text-center text-gray-900 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]">
        <p class="text-3xl font-black uppercase">Crew tidak ditemukan</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
type CrewProfile = {
  id: number
  name: string
  role: string
  status: string
  photo: string | null
  aib_photo: string | null
  fun_fact: string | null
  message: string | null
}

const route = useRoute()
const config = useRuntimeConfig()
const { mediaUrl } = useApiMedia()
const showAltPhoto = ref(false)

const { data: student, pending } = await useFetch<CrewProfile>(`${config.public.apiBase}/students/${route.params.id}/public`, {
  transform: (value) => ({
    ...value,
    photo: mediaUrl(value.photo),
    aib_photo: mediaUrl(value.aib_photo || value.photo),
  }),
})

const activePhoto = computed(() => {
  if (!student.value) return mediaUrl(null)
  return showAltPhoto.value ? (student.value.aib_photo || student.value.photo || mediaUrl(null)) : (student.value.photo || mediaUrl(null))
})
</script>
