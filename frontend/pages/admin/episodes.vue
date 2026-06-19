<template>
  <div>
    <NuxtLayout name="admin">
      <div class="space-y-8">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
          <div>
            <h2 class="text-4xl font-black text-gray-900 uppercase inline-block bg-green-500 px-6 py-2 transform -rotate-1 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
              Manajemen Episodes
            </h2>
            <p class="text-gray-600 font-bold text-lg mt-4">Kelola Hall of Shame & Inside Jokes</p>
          </div>
          <button 
            @click="openAddModal"
            class="px-6 py-3 bg-gray-900 text-white border-4 border-gray-900 hover:bg-green-500 hover:text-gray-900 font-black uppercase tracking-wider shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] hover:shadow-[6px_6px_0px_0px_rgba(255,255,255,1)] hover:-translate-y-1 transition-all duration-200 transform"
          >
            ➕ Tambah Episode
          </button>
        </div>

        <!-- Filter -->
        <div class="bg-white border-4 border-gray-900 p-6 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input 
              v-model="searchQuery"
              type="text" 
              placeholder="Cari judul episode..."
              class="px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
            />
            <select 
              v-model="filterCategory"
              class="px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold bg-white"
            >
              <option value="">Semua Kategori</option>
              <option value="ZOOM FAILS">Zoom Fails</option>
              <option value="ZOOM MYSTERY">Zoom Mystery</option>
              <option value="DRAMA">Drama</option>
              <option value="LEGENDARY">Legendary</option>
            </select>
          </div>
        </div>

        <!-- Episodes Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="episode in filteredEpisodes" 
            :key="episode.id"
            class="bg-white border-4 border-gray-900 transform transition-all duration-300 hover:-translate-y-2 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)] hover:shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]"
          >
            <div class="relative aspect-video overflow-hidden border-b-4 border-gray-900 bg-gray-800">
              <img 
                :src="episode.image" 
                :alt="episode.title"
                class="w-full h-full object-cover"
              />
              <div class="absolute top-3 left-3">
                <span class="bg-green-500 text-gray-900 px-3 py-1 text-xs font-black uppercase tracking-wider border-2 border-gray-900 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transform -rotate-3 inline-block">
                  {{ episode.category }}
                </span>
              </div>
            </div>
            
            <div class="p-6">
              <h3 class="text-xl font-black text-gray-900 mb-3 uppercase">
                {{ episode.title }}
              </h3>
              <p class="text-sm text-gray-600 font-bold mb-4 border-l-4 border-green-500 pl-3">
                {{ episode.shortDescription }}
              </p>
              
              <div class="flex gap-2 pt-4 border-t-2 border-gray-200">
                <button 
                  @click="editEpisode(episode)"
                  class="flex-1 px-4 py-2 bg-gray-900 text-white border-2 border-gray-900 hover:bg-green-500 hover:text-gray-900 font-black uppercase text-xs shadow-[2px_2px_0px_0px_rgba(34,197,94,1)] hover:-translate-y-1 transition-all duration-200 transform"
                >
                  ✏️ Edit
                </button>
                <button 
                  @click="deleteEpisode(episode.id)"
                  class="flex-1 px-4 py-2 bg-white text-gray-900 border-2 border-gray-900 hover:bg-red-500 hover:text-white font-black uppercase text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 transition-all duration-200 transform"
                >
                  🗑️ Hapus
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Add/Edit Modal -->
        <div 
          v-if="showModal" 
          class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/90 backdrop-blur-md"
          @click="closeModal"
        >
          <div 
            class="bg-white border-4 border-gray-900 max-w-3xl w-full max-h-[90vh] overflow-y-auto shadow-[16px_16px_0px_0px_rgba(34,197,94,1)] transform rotate-1"
            @click.stop
          >
            <div class="p-8">
              <div class="flex justify-between items-center mb-6">
                <h3 class="text-3xl font-black text-gray-900 uppercase">
                  {{ editMode ? 'Edit' : 'Tambah' }} Episode
                </h3>
                <button 
                  @click="closeModal"
                  class="w-12 h-12 bg-gray-900 text-white border-2 border-gray-900 hover:bg-red-500 font-black text-xl shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all duration-200"
                >
                  X
                </button>
              </div>

              <form @submit.prevent="saveEpisode" class="space-y-6">
                <div>
                  <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Judul Episode</label>
                  <input 
                    v-model="formData.title"
                    type="text" 
                    required
                    class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                    placeholder="Misal: Monkey Business"
                  />
                </div>

                <div>
                  <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Kategori</label>
                  <select 
                    v-model="formData.category"
                    required
                    class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold bg-white"
                  >
                    <option value="ZOOM FAILS">Zoom Fails</option>
                    <option value="ZOOM MYSTERY">Zoom Mystery</option>
                    <option value="DRAMA">Drama</option>
                    <option value="LEGENDARY">Legendary</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Deskripsi Singkat</label>
                  <input 
                    v-model="formData.shortDescription"
                    type="text" 
                    required
                    class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                    placeholder="Deskripsi singkat untuk card"
                  />
                </div>

                <div>
                  <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Deskripsi Lengkap</label>
                  <textarea 
                    v-model="formData.fullDescription"
                    required
                    rows="5"
                    class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold resize-none"
                    placeholder="Cerita lengkap episode ini..."
                  ></textarea>
                </div>

                <div>
                  <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">URL Gambar/Thumbnail</label>
                  <input 
                    v-model="formData.image"
                    type="url" 
                    required
                    class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                    placeholder="https://example.com/image.jpg"
                  />
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Tanggal</label>
                    <input 
                      v-model="formData.date"
                      type="text" 
                      required
                      class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                      placeholder="Misal: Oktober 2023"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Partisipan</label>
                    <input 
                      v-model="formData.participants"
                      type="text" 
                      required
                      class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                      placeholder="Misal: Willy & The Monkey"
                    />
                  </div>
                </div>

                <div class="flex gap-4 pt-4">
                  <button 
                    type="submit"
                    class="flex-1 px-6 py-3 bg-gray-900 text-white border-4 border-gray-900 hover:bg-green-500 hover:text-gray-900 font-black uppercase tracking-wider shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] hover:shadow-[6px_6px_0px_0px_rgba(255,255,255,1)] hover:-translate-y-1 transition-all duration-200 transform"
                  >
                    💾 Simpan
                  </button>
                  <button 
                    type="button"
                    @click="closeModal"
                    class="flex-1 px-6 py-3 bg-white text-gray-900 border-4 border-gray-900 hover:bg-gray-100 font-black uppercase tracking-wider shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 transition-all duration-200 transform"
                  >
                    ❌ Batal
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </NuxtLayout>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

definePageMeta({
  layout: false,
  middleware: 'admin-auth'
})

const episodes = ref([])
const searchQuery = ref('')
const filterCategory = ref('')
const showModal = ref(false)
const editMode = ref(false)
const formData = ref({
  id: null,
  category: 'ZOOM FAILS',
  title: '',
  image: '',
  shortDescription: '',
  fullDescription: '',
  date: '',
  participants: ''
})

const filteredEpisodes = computed(() => {
  return episodes.value.filter(episode => {
    const matchesSearch = episode.title.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesCategory = !filterCategory.value || episode.category === filterCategory.value
    return matchesSearch && matchesCategory
  })
})

const openAddModal = () => {
  editMode.value = false
  resetForm()
  showModal.value = true
}

const editEpisode = (episode) => {
  editMode.value = true
  formData.value = { ...episode }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  resetForm()
}

const resetForm = () => {
  formData.value = {
    id: null,
    category: 'ZOOM FAILS',
    title: '',
    image: '',
    shortDescription: '',
    fullDescription: '',
    date: '',
    participants: ''
  }
}

const saveEpisode = () => {
  if (editMode.value) {
    const index = episodes.value.findIndex(e => e.id === formData.value.id)
    if (index !== -1) {
      episodes.value[index] = { ...formData.value }
    }
  } else {
    const newEpisode = {
      ...formData.value,
      id: Date.now()
    }
    episodes.value.push(newEpisode)
  }
  closeModal()
}

const deleteEpisode = (id) => {
  if (confirm('Yakin ingin menghapus episode ini?')) {
    episodes.value = episodes.value.filter(e => e.id !== id)
  }
}

onMounted(() => {
  episodes.value = [
    {
      id: 1,
      category: 'ZOOM FAILS',
      title: 'Monkey Business',
      image: 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=800&q=80',
      shortDescription: 'Tragedi Willy TI di kebon dikejar monyet pas absen Zoom',
      fullDescription: 'Ini adalah momen legendaris di mana Willy lagi di kebun pas Zoom class, tiba-tiba dikejar monyet sambil teriak-teriak. Semua mahasiswa dan dosen langsung ngakak ngeliat kejadian ini.',
      date: 'Oktober 2023',
      participants: 'Willy & The Monkey'
    },
    {
      id: 2,
      category: 'ZOOM FAILS',
      title: 'Leaked Jidat',
      image: 'https://images.unsplash.com/photo-1520182205149-1e5e4e7329b4?auto=format&fit=crop&w=800&q=80',
      shortDescription: 'Insiden Dhila yang legend',
      fullDescription: 'Momen ketika Dhila lupa nyalain kamera dengan posisi yang... unik. Jidat cemerlang memenuhi layar Zoom, bikin semua orang nahan ketawa.',
      date: 'November 2023',
      participants: 'Dhila'
    },
    {
      id: 3,
      category: 'ZOOM FAILS',
      title: 'The Naked Truth',
      image: 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=800&q=80',
      shortDescription: 'Insiden Nico habis mandi pas Zoom',
      fullDescription: 'Nico lupa matiin kamera pas habis mandi. Untungnya cuma keliatan bahu ke atas, tapi cukup bikin heboh satu kelas.',
      date: 'Desember 2023',
      participants: 'Nico'
    }
  ]
})
</script>

<style scoped>
.font-display {
  font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
}
</style>
