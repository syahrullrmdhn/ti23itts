<template>
  <section id="timebox" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="font-display text-5xl md:text-6xl font-black mb-4 text-gray-900">
          The Time Box
        </h2>
        <p class="text-lg text-gray-600 font-medium">Perjalanan angkatan dari awal sampai sekarang</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16 max-w-5xl mx-auto">
        <div class="bg-white border-2 border-gray-200 rounded-xl p-8 shadow-lg text-center transition hover:border-green-500 hover:shadow-xl">
          <div class="text-5xl font-black mb-2 text-green-500">{{ stats.initialCount }}</div>
          <div class="font-bold text-gray-700">Mahasiswa Awal</div>
        </div>
        
        <div class="bg-white border-2 border-gray-200 rounded-xl p-8 shadow-lg text-center transition hover:border-green-500 hover:shadow-xl">
          <div class="text-5xl font-black mb-2 text-green-500">{{ stats.currentCount }}</div>
          <div class="font-bold text-gray-700">Masih Survive</div>
        </div>
        
        <div class="bg-white border-2 border-gray-200 rounded-xl p-8 shadow-lg text-center transition hover:border-green-500 hover:shadow-xl">
          <div class="text-5xl font-black mb-2 text-green-500">{{ stats.totalSemesters }}</div>
          <div class="font-bold text-gray-700">Semester Dilalui</div>
        </div>
      </div>

      <div class="max-w-6xl mx-auto">
        <div class="space-y-8">
          <div 
            v-for="(semester, index) in timeline" 
            :key="semester.id"
            class="flex flex-col md:flex-row gap-6 items-start"
          >
            <div class="flex-shrink-0">
              <div class="w-20 h-20 rounded-xl flex items-center justify-center text-3xl font-black bg-green-500 text-white shadow-lg">
                {{ semester.semester }}
              </div>
            </div>
            
            <div class="flex-1 bg-white rounded-xl p-6 shadow-lg border-l-4 border-green-500">
              <h3 class="text-2xl font-black text-gray-900 mb-2">
                Semester {{ semester.semester }}
              </h3>
              <p class="text-gray-600 mb-4 font-semibold">{{ semester.period }}</p>
              
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <span class="text-sm font-bold text-gray-700">Mahasiswa:</span>
                  <span class="text-sm text-gray-600 font-semibold">{{ semester.studentCount }} orang</span>
                </div>
                
                <div class="mt-4">
                  <p class="text-sm font-bold text-gray-700 mb-2">Dosen:</p>
                  <div class="flex flex-wrap gap-2">
                    <span 
                      v-for="lecturer in semester.lecturers" 
                      :key="lecturer.name"
                      class="inline-flex items-center px-3 py-1 rounded-md text-xs font-bold"
                      :class="lecturer.isAnomaly ? 'bg-red-100 text-red-700 border border-red-300' : 'bg-gray-100 text-gray-700 border border-gray-300'"
                    >
                      {{ lecturer.name }}
                      <span v-if="lecturer.isAnomaly" class="ml-2 text-[10px] uppercase tracking-wider px-1.5 py-0.5 bg-red-600 rounded text-white font-bold">Anomaly</span>
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
import { ref, onMounted } from 'vue'

const config = useRuntimeConfig()

const stats = ref({
  initialCount: 0,
  currentCount: 0,
  totalSemesters: 0
})

const timeline = ref([])

onMounted(async () => {
  try {
    const { data: timelineData } = await useFetch(`${config.public.apiBase}/timeline`)
    if (timelineData.value) {
      timeline.value = timelineData.value.semesters || []
      stats.value = {
        initialCount: timelineData.value.initialCount || 45,
        currentCount: timelineData.value.currentCount || 38,
        totalSemesters: timeline.value.length || 2
      }
    }
  } catch (error) {
    stats.value = {
      initialCount: 45,
      currentCount: 38,
      totalSemesters: 2
    }
    
    timeline.value = [
      {
        id: 1,
        semester: 1,
        period: 'Sep 2023 - Jan 2024',
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
