<template>
  <section id="timebox" class="py-20 bg-gradient-to-br from-mint-green/20 to-bright-blue/20">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="font-display text-5xl md:text-6xl font-black text-gray-900 mb-4">
          ⏰ The Time Box
        </h2>
        <p class="text-lg text-gray-600">Perjalanan angkatan dari awal sampai sekarang</p>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16 max-w-5xl mx-auto">
        <div class="bg-white rounded-3xl p-8 shadow-xl card-hover border-4 border-fun-pink">
          <div class="text-5xl font-black text-fun-pink mb-2">{{ stats.initialCount }}</div>
          <div class="text-gray-700 font-semibold">Mahasiswa Awal</div>
        </div>
        
        <div class="bg-white rounded-3xl p-8 shadow-xl card-hover border-4 border-bright-yellow">
          <div class="text-5xl font-black text-vibrant-orange mb-2">{{ stats.currentCount }}</div>
          <div class="text-gray-700 font-semibold">Masih Survive</div>
        </div>
        
        <div class="bg-white rounded-3xl p-8 shadow-xl card-hover border-4 border-bright-blue">
          <div class="text-5xl font-black text-bright-blue mb-2">{{ stats.totalSemesters }}</div>
          <div class="text-gray-700 font-semibold">Semester Dilalui</div>
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
                class="w-20 h-20 rounded-full flex items-center justify-center text-2xl font-black text-white shadow-lg"
                :class="getSemesterColor(index)"
              >
                {{ semester.icon }}
              </div>
            </div>
            
            <!-- Timeline Content -->
            <div class="flex-1 bg-white rounded-3xl p-6 shadow-xl card-hover">
              <h3 class="text-2xl font-bold text-gray-900 mb-2">
                Semester {{ semester.semester }}
              </h3>
              <p class="text-gray-600 mb-4">{{ semester.period }}</p>
              
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <span class="text-sm font-semibold text-gray-700">👥 Mahasiswa:</span>
                  <span class="text-sm text-gray-600">{{ semester.studentCount }} orang</span>
                </div>
                
                <div class="mt-4">
                  <p class="text-sm font-semibold text-gray-700 mb-2">👨‍🏫 Dosen:</p>
                  <div class="flex flex-wrap gap-2">
                    <span 
                      v-for="lecturer in semester.lecturers" 
                      :key="lecturer.name"
                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                      :class="lecturer.isAnomaly ? 'bg-fun-pink text-white' : 'bg-gray-100 text-gray-700'"
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
    'bg-fun-pink',
    'bg-bright-yellow',
    'bg-bright-blue',
    'bg-mint-green',
    'bg-vibrant-orange',
    'bg-soft-purple'
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
    // Fallback data for development
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
