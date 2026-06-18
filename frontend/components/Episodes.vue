<template>
  <section id="episodes" class="py-20 bg-white">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="font-display text-5xl md:text-6xl font-black mb-4 text-gray-900">
          Hall of Shame
        </h2>
        <p class="text-lg text-gray-600 font-medium">Aib dan inside jokes legendaris angkatan</p>
      </div>

      <div class="relative max-w-7xl mx-auto">
        <div class="overflow-x-auto scrollbar-hide pb-8">
          <div class="flex gap-6 px-4">
            <div 
              v-for="episode in episodes" 
              :key="episode.id"
              @click="openModal(episode)"
              class="flex-shrink-0 w-80 cursor-pointer group"
            >
              <div class="bg-white rounded-xl overflow-hidden shadow-xl relative aspect-video border-2 border-gray-200 hover:border-green-500 transition-colors duration-300">
                
                <img 
                  :src="episode.image" 
                  :alt="episode.title"
                  class="absolute inset-0 w-full h-full object-cover opacity-90 group-hover:scale-105 group-hover:opacity-100 transition-all duration-500"
                />
                
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/50 to-transparent flex flex-col justify-end p-6">
                  <span class="text-xs font-bold text-green-400 uppercase tracking-wider mb-2">
                    {{ episode.category }}
                  </span>
                  <h3 class="text-2xl font-black text-white mb-2 group-hover:text-green-400 transition-colors">
                    {{ episode.title }}
                  </h3>
                  <p class="text-sm text-gray-200 line-clamp-2 font-medium">
                    {{ episode.shortDescription }}
                  </p>
                </div>
                
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/40">
                  <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center shadow-2xl">
                    <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 20 20">
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

    <div 
      v-if="selectedEpisode" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
      @click="closeModal"
    >
      <div 
        class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto shadow-2xl"
        @click.stop
      >
        <div class="relative aspect-video rounded-t-2xl overflow-hidden bg-gray-100">
          <img 
            :src="selectedEpisode.image" 
            :alt="selectedEpisode.title"
            class="absolute inset-0 w-full h-full object-cover"
          />
          <button 
            @click="closeModal"
            class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white hover:bg-gray-100 flex items-center justify-center shadow-lg transition-all"
          >
            <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="p-8">
          <span class="inline-block px-4 py-2 rounded-md text-xs font-bold text-white mb-4 bg-green-500 uppercase tracking-wider">
            {{ selectedEpisode.category }}
          </span>
          
          <h2 class="text-4xl font-black text-gray-900 mb-4">
            {{ selectedEpisode.title }}
          </h2>
          
          <p class="text-gray-700 leading-relaxed whitespace-pre-line font-medium text-lg">
            {{ selectedEpisode.fullDescription }}
          </p>
          
          <div class="mt-8 pt-6 border-t border-gray-200 flex flex-wrap gap-6 text-sm text-gray-600 font-semibold">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
              <span>{{ selectedEpisode.date }}</span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
              <span>{{ selectedEpisode.participants }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

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
