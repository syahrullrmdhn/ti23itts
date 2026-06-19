<template>
  <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gray-900 text-white group">
    
    <!-- Background Image Grid dari foto mahasiswa di database -->
    <!-- Efek group-hover: opacity menurun agar teks makin jelas saat kursor masuk -->
    <div class="absolute inset-0 z-0 grid grid-cols-2 md:grid-cols-4 gap-1 opacity-50 transition-opacity duration-700 group-hover:opacity-20">
      <div v-for="(photo, index) in heroPhotos" :key="`${photo}-${index}`" class="aspect-video bg-gray-800 overflow-hidden">
        <img 
          :src="photo"
          alt="Memories" 
          class="w-full h-full object-cover opacity-40 grayscale hover:grayscale-0 transition-all duration-500 scale-105 hover:scale-100" 
        />
      </div>
    </div>
    
    <!-- Dark Overlay -->
    <!-- Efek group-hover: overlay menjadi lebih pekat saat di-hover -->
    <div class="absolute inset-0 bg-gray-900/60 transition-colors duration-700 group-hover:bg-gray-900/85 z-0"></div>
    
    <!-- Hero Content -->
    <div class="relative z-10 text-center px-4 max-w-6xl mx-auto flex flex-col items-center">
      
      <!-- Badge / Tagline Awal -->
      <div class="mb-10 transform -rotate-2 hover:rotate-0 transition-transform duration-300">
        <span class="inline-block px-6 py-2 bg-gray-800 border-2 border-green-500 text-green-400 font-bold text-sm uppercase tracking-wider shadow-[4px_4px_0px_0px_rgba(34,197,94,1)]">
          Angkatan 2023
        </span>
      </div>
      
      <!-- Main Title (Boxed Text Style) -->
      <h1 class="font-display text-6xl md:text-8xl lg:text-9xl font-black mb-10 flex flex-col items-center gap-4">
        <!-- Teks Baris 1: Miring ke kiri -->
        <span class="inline-block bg-green-500 text-gray-900 px-8 py-1 transform -rotate-3 hover:-translate-y-2 hover:rotate-0 transition-all duration-300 shadow-[8px_8px_0px_0px_rgba(255,255,255,0.1)]">
          TI'23
        </span>
        <!-- Teks Baris 2: Miring ke kanan -->
        <span class="inline-block bg-white text-gray-900 px-8 py-1 transform rotate-2 hover:-translate-y-2 hover:rotate-0 transition-all duration-300 shadow-[8px_8px_0px_0px_rgba(34,197,94,0.6)]">
          INSIDERS
        </span>
      </h1>
      
      <!-- Subtitle Boxed -->
      <div class="mb-8 transform -rotate-1 hover:rotate-1 transition-transform duration-300">
        <span class="inline-block px-6 py-3 bg-gray-800 text-white text-2xl md:text-3xl font-bold shadow-[6px_6px_0px_0px_rgba(0,0,0,0.5)] border border-gray-700">
          How fun we are!
        </span>
      </div>
      
      <!-- Description Panel -->
      <p class="text-lg md:text-xl text-gray-300 mb-12 max-w-2xl mx-auto font-medium bg-gray-900/60 p-6 rounded-none border-l-4 border-green-500 backdrop-blur-sm shadow-2xl transition-all duration-500 group-hover:bg-gray-900/80">
        Digital Yearbook Angkatan TI 2023 Institut Teknologi Tangerang Selatan - Tempat nongkrong digital yang nyimpen semua memori, dari yang keren sampai aib legendaris!
      </p>
      
      <!-- Call to Action Buttons (Neo-Brutalism Style) -->
      <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mt-4">
        <a 
          href="#episodes" 
          class="px-8 py-4 bg-green-500 text-gray-900 font-black uppercase tracking-wider transform hover:-translate-y-1 hover:scale-105 transition-all duration-200 shadow-[6px_6px_0px_0px_rgba(255,255,255,0.2)] active:shadow-[2px_2px_0px_0px_rgba(255,255,255,0.2)] active:translate-y-1"
        >
          Stalk Our Momen
        </a>
        
        <a 
          href="#timebox" 
          class="px-8 py-4 bg-white text-gray-900 font-black uppercase tracking-wider transform hover:-translate-y-1 hover:scale-105 transition-all duration-200 shadow-[6px_6px_0px_0px_rgba(34,197,94,0.6)] active:shadow-[2px_2px_0px_0px_rgba(34,197,94,0.6)] active:translate-y-1"
        >
          Perjalanan Kita
        </a>
      </div>

    </div>
  </section>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'

const config = useRuntimeConfig()
const { mediaUrl } = useApiMedia()
const studentPhotos = ref([])

const heroPhotos = computed(() => {
  const photos = studentPhotos.value.length
    ? studentPhotos.value
    : [mediaUrl(null)]

  return Array.from({ length: 16 }, (_, index) => photos[index % photos.length])
})

onMounted(async () => {
  try {
    const students = await $fetch(`${config.public.apiBase}/students`)
    studentPhotos.value = students
      .flatMap(student => [student.photo, student.aib_photo])
      .filter(Boolean)
      .map(mediaUrl)
  } catch (error) {
    console.error('Gagal memuat foto hero dari database', error)
  }
})
</script>

<style scoped>
/* Opsional: memastikan font-display diterapkan, jika di tailwind.config.js belum disetting */
.font-display {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}
</style>
