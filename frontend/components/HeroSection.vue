<template>
  <section id="hero" class="relative flex min-h-screen items-center justify-center overflow-hidden bg-gray-900 text-white">
    
    <div class="absolute inset-0 z-0 grid grid-cols-2 gap-1 opacity-50 md:grid-cols-4" aria-hidden="true">
      <div v-for="(photo, index) in heroPhotos" :key="`${photo}-${index}`" class="aspect-video bg-gray-800 overflow-hidden">
        <img 
          :src="photo"
          alt=""
          :loading="index < 4 ? 'eager' : 'lazy'"
          :fetchpriority="index === 0 ? 'high' : 'auto'"
          decoding="async"
          class="h-full w-full scale-105 object-cover opacity-50 grayscale transition-all duration-500 hover:scale-100 hover:grayscale-0"
        />
      </div>
    </div>
    
    <!-- Dark Overlay -->
    <!-- Efek group-hover: overlay menjadi lebih pekat saat di-hover -->
    <div class="absolute inset-0 z-0 bg-gray-900/60"></div>
    
    <!-- Hero Content -->
    <div class="relative z-10 mx-auto flex max-w-6xl flex-col items-center px-4 py-32 text-center sm:px-6">
      
      <!-- Badge / Tagline Awal -->
      <div v-reveal="'zoom'" class="mb-10 transform -rotate-2 transition-transform duration-300 hover:rotate-0">
        <span class="inline-block px-6 py-2 bg-gray-800 border-2 border-green-500 text-green-400 font-bold text-sm uppercase tracking-wider shadow-[4px_4px_0px_0px_rgba(34,197,94,1)]">
          Angkatan 2023
        </span>
      </div>
      
      <!-- Main Title (Boxed Text Style) -->
      <h1 v-reveal="'up'" class="mb-10 flex max-w-full flex-col items-center gap-4 font-display text-5xl font-black sm:text-6xl md:text-8xl lg:text-9xl">
        <!-- Teks Baris 1: Miring ke kiri -->
        <span class="inline-block max-w-full bg-green-500 px-5 py-1 text-gray-900 transform -rotate-3 hover:-translate-y-2 hover:rotate-0 transition-all duration-300 shadow-[8px_8px_0px_0px_rgba(255,255,255,0.1)] sm:px-8">
          TI'23
        </span>
        <!-- Teks Baris 2: Miring ke kanan -->
        <span class="inline-block max-w-full bg-white px-5 py-1 text-gray-900 transform rotate-2 hover:-translate-y-2 hover:rotate-0 transition-all duration-300 shadow-[8px_8px_0px_0px_rgba(34,197,94,0.6)] sm:px-8">
          INSIDERS
        </span>
      </h1>
      
      <!-- Subtitle Boxed -->
      <div v-reveal="'left'" class="mb-8 transform -rotate-1 transition-transform duration-300 hover:rotate-1">
        <span class="inline-block px-5 py-3 bg-gray-800 text-white text-xl sm:text-2xl md:text-3xl font-bold shadow-[6px_6px_0px_0px_rgba(0,0,0,0.5)] border border-gray-700">
          How fun we are!
        </span>
      </div>
      
      <!-- Description Panel -->
      <p v-reveal="'right'" class="mx-auto mb-12 max-w-2xl border-l-4 border-green-500 bg-gray-900/70 p-4 text-base font-medium text-gray-300 shadow-2xl backdrop-blur-sm sm:p-6 sm:text-lg md:text-xl">
        Digital Yearbook Angkatan TI 2023 Institut Teknologi Tangerang Selatan - Tempat nongkrong digital yang nyimpen semua memori, dari yang keren sampai aib legendaris!
      </p>
      
      <!-- Call to Action Buttons (Neo-Brutalism Style) -->
      <div v-reveal="'up'" class="mt-4 flex flex-col items-center justify-center gap-6 sm:flex-row">
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
const customHeroPhotos = ref([])
const fallbackHeroPhotos = [
  'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80',
  'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80',
  'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=1200&q=80',
  'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1200&q=80',
  'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80',
  'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1200&q=80',
  'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1200&q=80',
  'https://images.unsplash.com/photo-1491438590914-bc09fcaaf77a?auto=format&fit=crop&w=1200&q=80',
]

const heroPhotos = computed(() => {
  const photos = customHeroPhotos.value.length
    ? customHeroPhotos.value
    : fallbackHeroPhotos

  return Array.from({ length: 16 }, (_, index) => photos[index % photos.length])
})

onMounted(async () => {
  try {
    const heroPhotosFromApi = await $fetch(`${config.public.apiBase}/hero-photos`)
    customHeroPhotos.value = heroPhotosFromApi.map(photo => mediaUrl(photo.path))
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
