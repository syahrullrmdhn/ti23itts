<template>
  <section id="episodes" class="py-24 bg-gray-800 text-white border-y-4 border-white">
    <div class="container mx-auto px-4">
      <!-- Section Header -->
      <div class="text-center mb-16 flex flex-col items-center">
        <h2 class="font-display text-5xl md:text-7xl font-black mb-6 flex flex-col items-center gap-2">
          <span class="inline-block bg-green-500 text-gray-900 px-6 py-1 transform rotate-2 shadow-[6px_6px_0px_0px_rgba(255,255,255,1)]">
            HALL OF
          </span>
          <span class="inline-block bg-white text-gray-900 px-6 py-1 transform -rotate-2 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
            SHAME
          </span>
        </h2>
        <span class="inline-block px-4 py-2 bg-gray-900 text-white font-bold border-2 border-green-500 transform rotate-1 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
          Aib dan inside jokes legendaris angkatan
        </span>
      </div>

      <!-- Episodes Carousel -->
      <div class="relative max-w-7xl mx-auto">
        <div class="overflow-x-auto scrollbar-hide pb-12 pt-4">
          <div class="flex gap-8 px-4">
            <div 
              v-for="episode in episodes" 
              :key="episode.id"
              @click="openModal(episode)"
              class="flex-shrink-0 w-80 cursor-pointer group"
            >
              <div class="bg-gray-900 border-4 border-white transform transition-all duration-300 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)] group-hover:-translate-y-3 group-hover:shadow-[12px_12px_0px_0px_rgba(255,255,255,1)]">
                <div class="relative aspect-video overflow-hidden border-b-4 border-white bg-gray-800">
                  <img 
                    :src="episode.image" 
                    :alt="episode.title"
                    class="w-full h-full object-cover opacity-60 group-hover:scale-110 group-hover:opacity-100 transition-all duration-500 grayscale group-hover:grayscale-0"
                  />
                  <div class="absolute top-3 left-3">
                    <span class="bg-green-500 text-gray-900 px-3 py-1 text-xs font-black uppercase tracking-wider border-2 border-gray-900 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transform -rotate-3 inline-block">
                      {{ episode.category }}
                    </span>
                  </div>
                  
                  <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gray-900/40 backdrop-blur-sm">
                    <div class="w-16 h-16 bg-white border-4 border-gray-900 flex items-center justify-center shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transform rotate-6">
                      <svg class="w-8 h-8 text-gray-900 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                      </svg>
                    </div>
                  </div>
                </div>
                
                <div class="p-5 bg-gray-900">
                  <h3 class="text-2xl font-black text-white mb-3 leading-tight group-hover:text-green-400 transition-colors">
                    {{ episode.title }}
                  </h3>
                  <p class="text-sm text-gray-400 font-bold border-l-4 border-green-500 pl-3">
                    {{ episode.shortDescription }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div 
        v-if="selectedEpisode" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/90 backdrop-blur-md"
        @click="closeModal"
      >
        <div 
          class="bg-gray-900 border-4 border-white max-w-3xl w-full max-h-[90vh] overflow-y-auto shadow-[16px_16px_0px_0px_rgba(34,197,94,1)] transform rotate-1"
          @click.stop
        >
          <div class="relative aspect-video bg-gray-800 border-b-4 border-white">
            <img 
              :src="selectedEpisode.image" 
              :alt="selectedEpisode.title"
              class="absolute inset-0 w-full h-full object-cover"
            />
            <button 
              @click="closeModal"
              class="absolute top-4 right-4 w-12 h-12 bg-white border-4 border-gray-900 flex items-center justify-center shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] hover:bg-green-500 hover:text-gray-900 transition-colors text-gray-900 font-black text-xl"
            >
              X
            </button>
          </div>
          
          <div class="p-8">
            <span class="inline-block px-4 py-2 bg-green-500 text-gray-900 font-black border-2 border-gray-900 shadow-[4px_4px_0px_0px_rgba(255,255,255,1)] transform -rotate-2 mb-6 uppercase">
              {{ selectedEpisode.category }}
            </span>
            
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6 uppercase tracking-tight">
              {{ selectedEpisode.title }}
            </h2>
            
            <div class="bg-gray-800 border-2 border-gray-700 p-6 mb-8">
              <p class="text-gray-300 leading-relaxed whitespace-pre-line font-bold text-lg">
                {{ selectedEpisode.fullDescription }}
              </p>
            </div>
            
            <div class="flex flex-wrap gap-4 text-sm font-black">
              <div class="bg-white text-gray-900 px-4 py-2 border-2 border-gray-900 shadow-[3px_3px_0px_0px_rgba(34,197,94,1)]">
                📅 {{ selectedEpisode.date }}
              </div>
              <div class="bg-white text-gray-900 px-4 py-2 border-2 border-gray-900 shadow-[3px_3px_0px_0px_rgba(34,197,94,1)]">
                👥 {{ selectedEpisode.participants }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

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

onMounted(() => {
  episodes.value = [
    {
      id: 1,
      category: 'ZOOM FAILS',
      title: 'Monkey Business',
      image: 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=800&q=80',
      shortDescription: 'Tragedi Willy TI di kebon dikejar monyet pas absen Zoom',
      fullDescription: 'Ini adalah momen legendaris di mana Willy lagi di kebun pas Zoom class, tiba-tiba dikejar monyet sambil teriak-teriak. Semua mahasiswa dan dosen langsung ngakak ngeliat kejadian ini. Video-nya bahkan sempat viral di group WhatsApp angkatan dan jadi meme internal.',
      date: 'Oktober 2023',
      participants: 'Willy & The Monkey'
    },
    {
      id: 2,
      category: 'ZOOM FAILS',
      title: 'Leaked Jidat',
      image: 'https://images.unsplash.com/photo-1520182205149-1e5e4e7329b4?auto=format&fit=crop&w=800&q=80',
      shortDescription: 'Insiden Dhila yang legend',
      fullDescription: 'Momen ketika Dhila lupa nyalain kamera dengan posisi yang... unik. Jidat cemerlang memenuhi layar Zoom, bikin semua orang nahan ketawa. Sejak saat itu, Dhila jadi icon angkatan dengan julukan "Si Jidat Terang".',
      date: 'November 2023',
      participants: 'Dhila'
    },
    {
      id: 3,
      category: 'ZOOM FAILS',
      title: 'The Naked Truth',
      image: 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=800&q=80',
      shortDescription: 'Insiden Nico habis mandi pas Zoom',
      fullDescription: 'Nico lupa matiin kamera pas habis mandi. Untungnya cuma keliatan bahu ke atas, tapi cukup bikin heboh satu kelas. Dosen sampai harus pause kuliah sebentar karena semua chat box penuh emoji surprised.',
      date: 'Desember 2023',
      participants: 'Nico'
    },
    {
      id: 4,
      category: 'ZOOM MYSTERY',
      title: 'Symphony of Zoom',
      image: 'https://images.unsplash.com/photo-1541781774459-bb2af2f05b55?auto=format&fit=crop&w=800&q=80',
      shortDescription: 'Misteri siapa yang ngorok pas kelas online',
      fullDescription: 'Ada suara ngorok misterius yang sering muncul di tengah kuliah Zoom. Sampai sekarang belum ketahuan siapa pelakunya. Dosen pernah bilang "Tolong yang tidur matiin mic-nya", tapi suara ngorok tetap terdengar. Plot twist: mungkin dosennya sendiri?',
      date: 'Januari 2024',
      participants: 'Unknown Legend'
    },
    {
      id: 5,
      category: 'DRAMA',
      title: 'Surat Terbuka Moment',
      image: 'https://images.unsplash.com/photo-1577563908411-5077b6dc7624?auto=format&fit=crop&w=800&q=80',
      shortDescription: 'Arsip momen-momen spicy surat terbuka',
      fullDescription: 'Koleksi surat terbuka legendaris angkatan. Dari yang bikin baper, bikin heboh, sampai yang bikin tercengang. Ini adalah dokumentasi drama terbaik sepanjang masa TI 23. (Detail dirahasiakan untuk keamanan bersama)',
      date: 'Various Dates',
      participants: 'Multiple Players'
    }
  ]
})

onUnmounted(() => {
  document.body.style.overflow = 'auto'
})
</script>

<style scoped>
.font-display {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>