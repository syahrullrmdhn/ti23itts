<template>
  <section id="profil" class="py-24 bg-gray-900 text-white">
    <div class="container mx-auto px-4">
      <!-- Section Header -->
      <div class="text-center mb-16 flex flex-col items-center">
        <h2 class="font-display text-5xl md:text-7xl font-black mb-6 flex flex-col items-center gap-2">
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
      <div class="max-w-xl mx-auto mb-16 relative">
        <div class="absolute inset-0 bg-green-500 transform rotate-1"></div>
        <input 
          v-model="searchQuery"
          type="text" 
          placeholder="CARI NAMA ATAU ROLE..."
          class="relative w-full px-6 py-5 border-4 border-gray-900 bg-white text-gray-900 focus:outline-none text-center font-black placeholder:text-gray-400 text-lg uppercase transition-transform focus:-translate-y-1 focus:-translate-x-1"
        >
      </div>

      <!-- Students Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 max-w-7xl mx-auto">
        <div 
          v-for="student in filteredStudents" 
          :key="student.id"
          class="group relative"
        >
          <div class="bg-gray-800 border-4 border-white shadow-[8px_8px_0px_0px_rgba(34,197,94,1)] transition-all duration-300 hover:-translate-y-2 hover:shadow-[12px_12px_0px_0px_rgba(255,255,255,1)] z-10 relative">
            <div class="relative aspect-square overflow-hidden bg-gray-900 border-b-4 border-white">
              <img 
                :src="student.photo" 
                :alt="student.name"
                class="w-full h-full object-cover transition-all duration-500 group-hover:opacity-0 grayscale group-hover:grayscale-0"
              >
              <img 
                :src="student.aib_photo || student.photo" 
                :alt="student.name"
                class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 transition-all duration-500 scale-110 group-hover:scale-100"
              >
              
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
import { ref, computed, onMounted } from 'vue'

const students = ref([])
const searchQuery = ref('')

const filteredStudents = computed(() => {
  if (!searchQuery.value) return students.value
  const query = searchQuery.value.toLowerCase()
  return students.value.filter(student => 
    student.name.toLowerCase().includes(query) ||
    student.role.toLowerCase().includes(query)
  )
})

const getStatusColor = (status) => {
  const statusColors = {
    'Aktif': 'bg-green-500 text-gray-900',
    'Cuti': 'bg-yellow-400 text-gray-900',
    'Alumni': 'bg-white text-gray-900'
  }
  return statusColors[status] || 'bg-gray-500 text-white'
}

onMounted(() => {
  students.value = [
    {
      id: 1,
      name: 'Willy Kusuma',
      role: 'Korban Monyet',
      status: 'Aktif',
      photo: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop',
      aib_photo: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=400&h=400&fit=crop',
      fun_fact: 'Pernah dikejar monyet pas zoom'
    },
    {
      id: 2,
      name: 'Dhila Permata',
      role: 'Si Jidat Terang',
      status: 'Aktif',
      photo: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&h=400&fit=crop',
      aib_photo: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&h=400&fit=crop',
      fun_fact: 'Jidat legendaris'
    },
    {
      id: 3,
      name: 'Nico Pratama',
      role: 'Mr. Fresh',
      status: 'Aktif',
      photo: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop',
      aib_photo: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400&h=400&fit=crop',
      fun_fact: 'Lupa matiin kamera habis mandi'
    },
    {
      id: 4,
      name: 'Andi Wijaya',
      role: 'Si Paling Rajin',
      status: 'Aktif',
      photo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=400&fit=crop',
      aib_photo: 'https://images.unsplash.com/photo-1463453091185-61582044d556?w=400&h=400&fit=crop',
      fun_fact: 'Selalu ngumpulin duluan'
    },
    {
      id: 5,
      name: 'Siti Rahmawati',
      role: 'Tukang Tidur',
      status: 'Aktif',
      photo: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&h=400&fit=crop',
      aib_photo: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=400&h=400&fit=crop',
      fun_fact: 'Tidur di semua kelas'
    },
    {
      id: 6,
      name: 'Budi Santoso',
      role: 'The Debugger',
      status: 'Aktif',
      photo: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=400&h=400&fit=crop',
      aib_photo: 'https://images.unsplash.com/photo-1531427186611-ecfd6d936c79?w=400&h=400&fit=crop',
      fun_fact: 'Bisa fix bug apapun'
    },
    {
      id: 7,
      name: 'Maya Anggraini',
      role: 'Queen of UI',
      status: 'Cuti',
      photo: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=400&h=400&fit=crop',
      aib_photo: 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=400&h=400&fit=crop',
      fun_fact: 'Desain selalu on point'
    },
    {
      id: 8,
      name: 'Reza Firmansyah',
      role: 'Tukang Copas',
      status: 'Aktif',
      photo: 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=400&h=400&fit=crop',
      aib_photo: 'https://images.unsplash.com/photo-1489980557514-251d61e3eeb6?w=400&h=400&fit=crop',
      fun_fact: 'Stack Overflow adalah teman'
    }
  ]
})
</script>

<style scoped>
.font-display {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}
</style>