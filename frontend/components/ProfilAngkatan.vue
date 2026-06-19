<template>
  <section id="profil" class="py-24 bg-gray-900 text-white">
    <div class="container mx-auto px-4">
      <!-- Section Header -->
      <div v-reveal="'up'" class="mb-16 flex flex-col items-center text-center">
        <h2 class="mb-6 flex flex-col items-center gap-2 font-display text-5xl font-black md:text-7xl">
          <span class="inline-block bg-white text-gray-900 px-6 py-1 transform -rotate-2 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
            CAST &
          </span>
          <span class="inline-block bg-green-500 text-gray-900 px-6 py-1 transform rotate-2 shadow-[6px_6px_0px_0px_rgba(255,255,255,1)]">
            CREW
          </span>
        </h2>
        <span class="inline-block px-4 py-2 bg-gray-800 text-white font-bold border border-gray-700 transform -rotate-1 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
          Kenalan sama anak-anak TI'23 yang masih survive!
        </span>
      </div>

      <!-- Search Bar -->
      <div v-reveal="'zoom'" class="relative mx-auto mb-16 max-w-xl">
        <div class="absolute inset-0 bg-green-500 transform rotate-1"></div>
        <input 
          v-model="searchQuery"
          type="text" 
          placeholder="CARI NAMA ATAU ROLE..."
          class="relative w-full px-6 py-5 border-4 border-gray-900 bg-white text-gray-900 focus:outline-none text-center font-black placeholder:text-gray-400 text-lg uppercase transition-transform focus:-translate-y-1 focus:-translate-x-1"
        >
      </div>

      <!-- Students Grid -->
      <div class="mx-auto grid max-w-7xl grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <NuxtLink
          v-for="student in paginatedStudents"
          :key="student.id"
          :to="`/crew/${student.id}`"
          v-reveal="'up'"
          class="group relative"
        >
          <div class="bg-gray-800 border-4 border-white shadow-[8px_8px_0px_0px_rgba(34,197,94,1)] transition-all duration-300 hover:-translate-y-2 hover:shadow-[12px_12px_0px_0px_rgba(255,255,255,1)] z-10 relative">
            <div class="relative aspect-square overflow-hidden bg-gray-900 border-b-4 border-white">
              <img
                v-if="student.photo"
                :src="student.photo" 
                :alt="student.name"
                class="w-full h-full object-cover transition-all duration-500 group-hover:opacity-0 grayscale group-hover:grayscale-0"
              >
              <img
                v-if="student.photo"
                :src="student.aib_photo || student.photo" 
                :alt="student.name"
                class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 transition-all duration-500 scale-110 group-hover:scale-100"
              >
              <div v-else class="flex h-full w-full items-center justify-center bg-green-500 text-gray-900">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-28 w-28">
                  <path
                    fill="currentColor"
                    d="M12 12c2.76 0 5-2.69 5-6s-2.24-6-5-6-5 2.69-5 6 2.24 6 5 6Zm0 2c-4.42 0-8 2.91-8 6.5 0 .83.67 1.5 1.5 1.5h13c.83 0 1.5-.67 1.5-1.5C20 16.91 16.42 14 12 14Z"
                  />
                </svg>
              </div>
              
              <div class="absolute top-4 right-4 transform rotate-3">
                <span 
                  class="px-3 py-1 text-xs font-black border-2 border-gray-900 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] tracking-wider uppercase inline-block"
                  :class="getStatusColor(student.status)"
                >
                  {{ student.status }}
                </span>
              </div>
            </div>
            
            <div class="p-6 relative bg-gray-800">
              <h3 class="text-2xl font-black text-white mb-2 uppercase tracking-tight">
                {{ student.name }}
              </h3>
              
              <div class="inline-block bg-gray-900 text-green-400 px-3 py-1 text-sm font-black mb-4 uppercase tracking-wider border border-green-500 transform -rotate-1">
                {{ student.role }}
              </div>
              
              <div v-if="student.fun_fact" class="bg-white text-gray-900 p-3 text-sm font-bold border-2 border-gray-900 shadow-[3px_3px_0px_0px_rgba(34,197,94,1)] transform rotate-1 mt-2">
                "{{ student.fun_fact }}"
              </div>
            </div>
          </div>
        </NuxtLink>
      </div>

      <div v-if="totalPages > 1" v-reveal="'up'" class="mt-12 flex flex-col items-center gap-4">
        <p class="text-sm font-black uppercase tracking-[0.2em] text-gray-400">
          Halaman {{ currentPage }} dari {{ totalPages }}
        </p>
        <div class="flex flex-wrap items-center justify-center gap-3">
          <button
            type="button"
            class="border-4 border-white bg-white px-5 py-3 font-black uppercase text-gray-900 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 disabled:cursor-not-allowed disabled:opacity-40"
            :disabled="currentPage === 1"
            @click="currentPage -= 1"
          >
            Prev
          </button>
          <button
            v-for="page in paginationWindow"
            :key="page"
            type="button"
            class="min-w-12 border-4 px-4 py-3 font-black uppercase transition-all hover:-translate-y-1"
            :class="page === currentPage ? 'border-green-500 bg-green-500 text-gray-900 shadow-[4px_4px_0px_0px_rgba(255,255,255,1)]' : 'border-white bg-gray-800 text-white shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]'"
            @click="currentPage = page"
          >
            {{ page }}
          </button>
          <button
            type="button"
            class="border-4 border-white bg-white px-5 py-3 font-black uppercase text-gray-900 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 disabled:cursor-not-allowed disabled:opacity-40"
            :disabled="currentPage === totalPages"
            @click="currentPage += 1"
          >
            Next
          </button>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredStudents.length === 0" class="text-center py-20">
        <div class="inline-block bg-red-500 text-white px-8 py-4 border-4 border-white shadow-[8px_8px_0px_0px_rgba(255,255,255,1)] transform -rotate-2">
          <p class="text-3xl font-black uppercase">Hilang dari Radar! 😢</p>
          <p class="font-bold mt-2 text-gray-900">Coba cari nama yang bener deh.</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'

const config = useRuntimeConfig()
const { mediaUrl } = useApiMedia()
const students = ref([])
const searchQuery = ref('')
const currentPage = ref(1)
const perPage = 8

const filteredStudents = computed(() => {
  if (!searchQuery.value) return students.value
  const query = searchQuery.value.toLowerCase()
  return students.value.filter(student => 
    student.name.toLowerCase().includes(query) ||
    student.role.toLowerCase().includes(query)
  )
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredStudents.value.length / perPage)))

const paginatedStudents = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return filteredStudents.value.slice(start, start + perPage)
})

const paginationWindow = computed(() => {
  const pages = []
  const start = Math.max(1, currentPage.value - 2)
  const end = Math.min(totalPages.value, start + 4)

  for (let page = Math.max(1, end - 4); page <= end; page += 1) {
    pages.push(page)
  }

  return pages
})

watch(searchQuery, () => {
  currentPage.value = 1
})

watch(totalPages, (pages) => {
  if (currentPage.value > pages) {
    currentPage.value = pages
  }
})

const getStatusColor = (status) => {
  const statusColors = {
    'Aktif': 'bg-green-500 text-gray-900',
    'Cuti': 'bg-yellow-400 text-gray-900',
    'Alumni': 'bg-white text-gray-900'
  }
  return statusColors[status] || 'bg-gray-500 text-white'
}

onMounted(async () => {
  try {
    const data = await $fetch(`${config.public.apiBase}/students`)
    students.value = data.map(student => ({
      ...student,
      photo: mediaUrl(student.photo),
      aib_photo: mediaUrl(student.aib_photo || student.photo),
    }))
  } catch (error) {
    console.error('Gagal memuat mahasiswa dari database', error)
  }
})
</script>

<style scoped>
.font-display {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}
</style>
