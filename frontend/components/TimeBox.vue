<template>
  <section id="timebox" class="py-20 bg-white">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="font-display text-5xl md:text-6xl font-black text-gray-900 mb-4">
          The Time Box
        </h2>
        <p class="text-lg text-gray-600 font-medium">Perjalanan angkatan dari awal sampai sekarang</p>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16 max-w-5xl mx-auto">
        <div class="bg-gradient-to-br from-primary to-emerald-600 rounded-xl p-8 shadow-xl card-hover text-white">
          <div class="text-5xl font-black mb-2">{{ stats.initialCount }}</div>
          <div class="font-bold text-emerald-100">Mahasiswa Awal</div>
        </div>
        
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-8 shadow-xl card-hover text-white">
          <div class="text-5xl font-black mb-2">{{ stats.currentCount }}</div>
          <div class="font-bold text-blue-100">Masih Survive</div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-8 shadow-xl card-hover text-white">
          <div class="text-5xl font-black mb-2">{{ stats.totalSemesters }}</div>
          <div class="font-bold text-purple-100">Semester Dilalui</div>
        </div>
      </div>

      <!-- Timeline -->
      <div class="max-w-6xl mx-auto">
        <div class="space-y-8">
          <div 
            v-for="(semester, index) in timeline" 
            :key="semester.id"
            class="flex flex-col md:flex-row gap-6 items-start"
          >
            <!-- Timeline Icon -->
            <div class="flex-shrink-0">
              <div 
                class="w-20 h-20 rounded-xl flex items-center justify-center text-2xl font-black text-white shadow-lg"
                :class="getSemesterColor(index)"
              >
                {{ semester.icon }}
              </div>
            </div>
            
            <!-- Timeline Content -->
            <div class="flex-1 bg-gray-50 rounded-xl p-6 shadow-lg card-hover border-l-4"
              :class="getSemesterBorder(index)">
              <h3 class="text-2xl font-black text-gray-900 mb-2">
                Semester {{ semester.semester }}
              </h3>
              <p class="text-gray-600 mb-4 font-semibold">{{ semester.period }}</p>
              
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <span class="text-sm font-bold text-gray-700">👥 Mahasiswa:</span>
                  <span class="text-sm text-gray-600 font-semibold">{{ semester.studentCount }} orang</span>
                </div>
                
                <div class="mt-4">
                  <p class="text-sm font-bold text-gray-700 mb-2">👨‍🏫 Dosen:</p>
                  <div class="flex flex-wrap gap-2">
                    <span 
                      v-for="lecturer in semester.lecturers" 
                      :key="lecturer.name"
                      class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold"
                      :class="lecturer.isAnomaly ? 'bg-red-100 text-red-700 border border-red-300' : 'bg-white text-gray-700 border border-gray-200'"
                    >
                      {{ lecturer.name }}
                      <span v-if="lecturer.isAnomaly" class="ml-1">⚠️</span>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const config = useRuntimeConfig()

const stats = ref({
  initialCount: 0,
  currentCount: 0,
  totalSemesters: 0
})

const timeline = ref([])

const getSemesterColor = (index) => {
  const colors = [
    'bg-primary',
    'bg-blue-500',
    'bg-purple-500',
    'bg-emerald-500',
    'bg-indigo-500',
    'bg-teal-500'
  ]
  return colors[index % colors.length]
}

const getSemesterBorder = (index) => {
  const colors = [
    'border-primary',
    'border-blue-500',
    'border-purple-500',
    'border-emerald-500',
    'border-indigo-500',
    'border-teal-500'
  ]
  return colors[index % colors.length]
}

onMounted(async () => {
  try {
    const { data: timelineData } = await useFetch(`${config.public.apiBase}/timeline`)
    if (timelineData.value) {
      timeline.value = timelineData.value.semesters || []
      stats.value = {
        initialCount: timelineData.value.initialCount || 45,
        currentCount: timelineData.value.currentCount || 38,
        totalSemesters: timeline.value.length || 6
      }
    }
  } catch (error) {
    stats.value = {
      initialCount: 45,
      currentCount: 38,
      totalSemesters: 6
    }
    
    timeline.value = [
      {
        id: 1,
        semester: 1,
        period: 'Sep 2023 - Jan 2024',
        icon: '🎯',
        studentCount: 45,
        lecturers: [
          { name: 'Pak Budi', isAnomaly: false },
          { name: 'Bu Siti', isAnomaly: false },
          { name: 'Pak Anomali', isAnomaly: true }
        ]
      },
      {
        id: 2,
        semester: 2,
        period: 'Feb 2024 - Jun 2024',
        icon: '🚀',
        studentCount: 43,
        lecturers: [
          { name: 'Pak Ahmad', isAnomaly: false },
          { name: 'Bu Rina', isAnomaly: false }
        ]
      }
    ]
  }
})
</script>
