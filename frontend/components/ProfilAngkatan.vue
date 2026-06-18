<template>
  <section id="profil" class="py-20 bg-gradient-to-br from-fun-pink/10 via-bright-yellow/10 to-mint-green/10">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="font-display text-5xl md:text-6xl font-black text-gray-900 mb-4">
          👥 Cast & Crew
        </h2>
        <p class="text-lg text-gray-600">Kenalan sama anak-anak TI'23 yang masih survive!</p>
      </div>

      <!-- Filter/Search -->
      <div class="max-w-md mx-auto mb-12">
        <input 
          v-model="searchQuery"
          type="text" 
          placeholder="Cari nama atau role..."
          class="w-full px-6 py-4 rounded-full border-2 border-gray-300 focus:border-fun-pink focus:outline-none text-center font-semibold"
        >
      </div>

      <!-- Students Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 max-w-7xl mx-auto">
        <div 
          v-for="student in filteredStudents" 
          :key="student.id"
          class="group relative"
        >
          <div class="bg-white rounded-3xl overflow-hidden shadow-xl card-hover">
            <!-- Photo Container with Hover Effect -->
            <div class="relative aspect-square overflow-hidden">
              <!-- Normal Photo -->
              <img 
                :src="student.photo" 
                :alt="student.name"
                class="w-full h-full object-cover transition-opacity duration-300 group-hover:opacity-0"
              >
              
              <!-- Aib Photo (shown on hover) -->
              <img 
                :src="student.aib_photo || student.photo" 
                :alt="student.name"
                class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 transition-opacity duration-300"
              >
              
              <!-- Status Badge -->
              <div class="absolute top-4 right-4">
                <span 
                  class="px-3 py-1 rounded-full text-xs font-bold text-white shadow-lg"
                  :class="getStatusColor(student.status)"
                >
                  {{ student.status }}
                </span>
              </div>
            </div>
            
            <!-- Student Info -->
            <div class="p-6">
              <h3 class="text-xl font-black text-gray-900 mb-2">
                {{ student.name }}
              </h3>
              
              <p class="text-sm font-semibold text-fun-pink mb-4">
                {{ student.role }}
              </p>
              
              <!-- Fun Facts -->
              <div v-if="student.fun_fact" class="text-xs text-gray-600 italic">
                "{{ student.fun_fact }}"
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredStudents.length === 0" class="text-center py-12">
        <p class="text-2xl font-bold text-gray-400">Tidak ada hasil ditemukan 😢</p>
      </div>
    </div>
  </section>
</template>

<script setup>
const config = useRuntimeConfig()
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
    'Aktif': 'bg-bright-blue',
    'Cuti': 'bg-bright-yellow text-gray-900',
    'Alumni': 'bg-mint-green text-gray-900'
  }
  return statusColors[status] || 'bg-gray-500'
}

onMounted(async () => {
  try {
    const { data } = await useFetch(`${config.public.apiBase}/students`)
    if (data.value) {
      students.value = data.value
    }
  } catch (error) {
    students.value = [
      {
        id: 1,
        name: 'Willy Kusuma',
        role: 'Korban Monyet 🐒',
        status: 'Aktif',
        photo: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop',
        aib_photo: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=400&h=400&fit=crop',
        fun_fact: 'Pernah dikejar monyet pas zoom'
      },
      {
        id: 2,
        name: 'Dhila Permata',
        role: 'Si Jidat Terang 💡',
        status: 'Aktif',
        photo: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&h=400&fit=crop',
        aib_photo: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&h=400&fit=crop',
        fun_fact: 'Jidat legendaris'
      },
      {
        id: 3,
        name: 'Nico Pratama',
        role: 'Mr. Fresh 🚿',
        status: 'Aktif',
        photo: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop',
        aib_photo: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400&h=400&fit=crop',
        fun_fact: 'Lupa matiin kamera habis mandi'
      },
      {
        id: 4,
        name: 'Andi Wijaya',
        role: 'Si Paling Rajin 📚',
        status: 'Aktif',
        photo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=400&fit=crop',
        aib_photo: 'https://images.unsplash.com/photo-1463453091185-61582044d556?w=400&h=400&fit=crop',
        fun_fact: 'Selalu ngumpulin duluan'
      },
      {
        id: 5,
        name: 'Siti Rahmawati',
        role: 'Tukang Tidur 💤',
        status: 'Aktif',
        photo: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&h=400&fit=crop',
        aib_photo: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=400&h=400&fit=crop',
        fun_fact: 'Tidur di semua kelas'
      },
      {
        id: 6,
        name: 'Budi Santoso',
        role: 'The Debugger 🐛',
        status: 'Aktif',
        photo: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=400&h=400&fit=crop',
        aib_photo: 'https://images.unsplash.com/photo-1531427186611-ecfd6d936c79?w=400&h=400&fit=crop',
        fun_fact: 'Bisa fix bug apapun'
      },
      {
        id: 7,
        name: 'Maya Anggraini',
        role: 'Queen of UI 👑',
        status: 'Aktif',
        photo: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=400&h=400&fit=crop',
        aib_photo: 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=400&h=400&fit=crop',
        fun_fact: 'Desain selalu on point'
      },
      {
        id: 8,
        name: 'Reza Firmansyah',
        role: 'Tukang Copas 📋',
        status: 'Aktif',
        photo: 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=400&h=400&fit=crop',
        aib_photo: 'https://images.unsplash.com/photo-1489980557514-251d61e3eeb6?w=400&h=400&fit=crop',
        fun_fact: 'Stack Overflow adalah teman'
      }
    ]
  }
})
</script>
