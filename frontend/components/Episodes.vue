<template>
  <section id="episodes" class="py-20 bg-white">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="font-display text-5xl md:text-6xl font-black text-gray-900 mb-4">
          🎬 Hall of Shame
        </h2>
        <p class="text-lg text-gray-600">Aib dan inside jokes legendaris angkatan</p>
      </div>

      <!-- Episodes Carousel -->
      <div class="relative max-w-7xl mx-auto">
        <div class="overflow-x-auto scrollbar-hide pb-8">
          <div class="flex gap-6 px-4">
            <div 
              v-for="episode in episodes" 
              :key="episode.id"
              @click="openModal(episode)"
              class="flex-shrink-0 w-80 cursor-pointer group"
            >
              <!-- Thumbnail Card -->
              <div class="bg-gradient-to-br rounded-3xl overflow-hidden shadow-xl card-hover relative aspect-video"
                :style="{ background: episode.gradientColor }">
                
                <!-- Thumbnail Image/Icon -->
                <div class="absolute inset-0 flex items-center justify-center text-8xl opacity-80 group-hover:scale-110 transition-transform duration-300">
                  {{ episode.icon }}
                </div>
                
                <!-- Overlay Content -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-6">
                  <span class="text-xs font-bold text-white/80 uppercase tracking-wider mb-2">
                    {{ episode.category }}
                  </span>
                  <h3 class="text-2xl font-black text-white mb-2 group-hover:text-bright-yellow transition-colors">
                    {{ episode.title }}
                  </h3>
                  <p class="text-sm text-white/90 line-clamp-2">
                    {{ episode.shortDescription }}
                  </p>
                </div>
                
                <!-- Play Button Overlay -->
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/30">
                  <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center shadow-2xl">
                    <svg class="w-10 h-10 text-fun-pink ml-1" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                    </svg>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div 
      v-if="selectedEpisode" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80"
      @click="closeModal"
    >
      <div 
        class="bg-white rounded-3xl max-w-3xl w-full max-h-[90vh] overflow-y-auto shadow-2xl"
        @click.stop
      >
        <!-- Modal Header -->
        <div class="relative aspect-video rounded-t-3xl overflow-hidden"
          :style="{ background: selectedEpisode.gradientColor }">
          <div class="absolute inset-0 flex items-center justify-center text-9xl opacity-60">
            {{ selectedEpisode.icon }}
          </div>
          <button 
            @click="closeModal"
            class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/90 hover:bg-white flex items-center justify-center shadow-lg transition-all"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <!-- Modal Content -->
        <div class="p-8">
          <span class="inline-block px-4 py-1 rounded-full text-xs font-bold text-white mb-4"
            :style="{ background: selectedEpisode.gradientColor }">
            {{ selectedEpisode.category }}
          </span>
          
          <h2 class="text-4xl font-black text-gray-900 mb-4">
            {{ selectedEpisode.title }}
          </h2>
          
          <p class="text-gray-700 leading-relaxed whitespace-pre-line">
            {{ selectedEpisode.fullDescription }}
          </p>
          
          <!-- Metadata -->
          <div class="mt-8 pt-6 border-t border-gray-200 flex flex-wrap gap-4 text-sm text-gray-600">
            <div class="flex items-center gap-2">
              <span>📅</span>
              <span>{{ selectedEpisode.date }}</span>
            </div>
            <div class="flex items-center gap-2">
              <span>👥</span>
              <span>{{ selectedEpisode.participants }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const config = useRuntimeConfig()
const episodes = ref([])
const selectedEpisode = ref(null)

const openModal = (episode) => {
  selectedEpisode.value = episode
  document.body.style.overflow = 'hidden'
}

const closeModal = () => {
  selectedEpisode.value = null
  document.body.style.overflow = 'auto'
}

onMounted(async () => {
  try {
    const { data } = await useFetch(`${config.public.apiBase}/episodes`)
    if (data.value) {
      episodes.value = data.value
    }
  } catch (error) {
    // Fallback data for development
    episodes.value = [
      {
        id: 1,
        category: 'ZOOM FAILS',
        title: 'Monkey Business',
        icon: '🐒',
        gradientColor: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        shortDescription: 'Tragedi Willy TI di kebon dikejar monyet pas absen Zoom',
        fullDescription: 'Ini adalah momen legendaris di mana Willy lagi di kebun pas Zoom class, tiba-tiba dikejar monyet sambil teriak-teriak. Semua mahasiswa dan dosen langsung ngakak ngeliat kejadian ini. Video-nya bahkan sempat viral di group WhatsApp angkatan dan jadi meme internal.',
        date: 'Oktober 2023',
        participants: 'Willy & The Monkey'
      },
      {
        id: 2,
        category: 'ZOOM FAILS',
        title: 'Leaked Jidat',
        icon: '💡',
        gradientColor: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
        shortDescription: 'Insiden Dhila yang legend',
        fullDescription: 'Momen ketika Dhila lupa nyalain kamera dengan posisi yang... unik. Jidat cemerlang memenuhi layar Zoom, bikin semua orang nahan ketawa. Sejak saat itu, Dhila jadi icon angkatan dengan julukan "Si Jidat Terang".',
        date: 'November 2023',
        participants: 'Dhila'
      },
      {
        id: 3,
        category: 'ZOOM FAILS',
        title: 'The Naked Truth',
        icon: '🚿',
        gradientColor: 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
        shortDescription: 'Insiden Nico habis mandi pas Zoom',
        fullDescription: 'Nico lupa matiin kamera pas habis mandi. Untungnya cuma keliatan bahu ke atas, tapi cukup bikin heboh satu kelas. Dosen sampai harus pause kuliah sebentar karena semua chat box penuh emoji surprised.',
        date: 'Desember 2023',
        participants: 'Nico'
      },
      {
        id: 4,
        category: 'ZOOM MYSTERY',
        title: 'Symphony of Zoom',
        icon: '💤',
        gradientColor: 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
        shortDescription: 'Misteri siapa yang ngorok pas kelas online',
        fullDescription: 'Ada suara ngorok misterius yang sering muncul di tengah kuliah Zoom. Sampai sekarang belum ketahuan siapa pelakunya. Dosen pernah bilang "Tolong yang tidur matiin mic-nya", tapi suara ngorok tetap terdengar. Plot twist: mungkin dosennya sendiri?',
        date: 'Januari 2024',
        participants: 'Unknown Legend'
      },
      {
        id: 5,
        category: 'DRAMA',
        title: 'Surat Terbuka Moment',
        icon: '✉️',
        gradientColor: 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
        shortDescription: 'Arsip momen-momen spicy surat terbuka',
        fullDescription: 'Koleksi surat terbuka legendaris angkatan. Dari yang bikin baper, bikin heboh, sampai yang bikin tercengang. Ini adalah dokumentasi drama terbaik sepanjang masa TI 23. (Detail dirahasiakan untuk keamanan bersama)',
        date: 'Various Dates',
        participants: 'Multiple Players'
      }
    ]
  }
})

onUnmounted(() => {
  document.body.style.overflow = 'auto'
})
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
