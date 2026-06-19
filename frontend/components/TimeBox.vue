<template>
  <section id="timebox" class="py-24 bg-gray-900 text-white relative overflow-hidden border-t-4 border-white">
    <div class="absolute top-10 left-10 w-32 h-32 bg-green-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>

    <div class="container mx-auto px-4 relative z-10">
      <!-- Section Header -->
      <div class="text-center mb-20 flex flex-col items-center">
        <h2 class="font-display text-5xl md:text-7xl font-black mb-6 flex flex-col items-center gap-3">
          <span class="inline-block bg-white text-gray-900 px-6 py-1 transform -rotate-2 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)] hover:-translate-y-1 hover:rotate-0 transition-all duration-300">
            THE TIME
          </span>
          <span class="inline-block bg-green-500 text-gray-900 px-6 py-1 transform rotate-2 shadow-[6px_6px_0px_0px_rgba(255,255,255,0.2)] hover:-translate-y-1 hover:rotate-0 transition-all duration-300">
            BOX
          </span>
        </h2>
        <span class="inline-block px-4 py-2 bg-gray-800 border border-gray-700 text-gray-300 font-bold transform -rotate-1 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.5)]">
          Perjalanan angkatan dari awal sampai sekarang
        </span>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20 max-w-5xl mx-auto">
        <div class="bg-gray-800 border-2 border-white p-8 text-center transform -rotate-1 hover:rotate-0 hover:-translate-y-2 transition-all duration-300 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]">
          <div class="text-6xl font-black mb-2 text-white drop-shadow-[4px_4px_0px_rgba(34,197,94,0.5)]">{{ stats.initialCount }}</div>
          <div class="font-black text-green-400 uppercase tracking-widest text-sm bg-gray-900 py-1 inline-block px-4 border border-green-500">Mahasiswa Awal</div>
        </div>
        
        <div class="bg-green-500 border-2 border-gray-900 p-8 text-center transform rotate-1 hover:rotate-0 hover:-translate-y-2 transition-all duration-300 shadow-[8px_8px_0px_0px_rgba(255,255,255,1)]">
          <div class="text-6xl font-black mb-2 text-gray-900 drop-shadow-[4px_4px_0px_rgba(255,255,255,0.5)]">{{ stats.currentCount }}</div>
          <div class="font-black text-white uppercase tracking-widest text-sm bg-gray-900 py-1 inline-block px-4 border border-gray-900">Masih Survive</div>
        </div>
        
        <div class="bg-gray-800 border-2 border-white p-8 text-center transform -rotate-1 hover:rotate-0 hover:-translate-y-2 transition-all duration-300 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]">
          <div class="text-6xl font-black mb-2 text-white drop-shadow-[4px_4px_0px_rgba(34,197,94,0.5)]">{{ stats.totalSemesters }}</div>
          <div class="font-black text-green-400 uppercase tracking-widest text-sm bg-gray-900 py-1 inline-block px-4 border border-green-500">Semester Dilalui</div>
        </div>
      </div>

      <!-- Timeline -->
      <div class="max-w-4xl mx-auto">
        <div class="space-y-12">
          <div 
            v-for="semester in timeline" 
            :key="semester.id"
            class="flex flex-col md:flex-row gap-6 items-start group"
          >
            <div class="flex-shrink-0 relative z-10">
              <div class="w-20 h-20 flex items-center justify-center text-4xl font-black bg-white text-gray-900 border-4 border-gray-900 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)] transform group-hover:rotate-6 transition-transform duration-300">
                {{ semester.semester }}
              </div>
            </div>
            
            <div class="flex-1 bg-gray-800 border-2 border-green-500 p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,0.5)] group-hover:-translate-y-1 group-hover:shadow-[8px_8px_0px_0px_rgba(255,255,255,0.2)] transition-all duration-300">
              <h3 class="inline-block bg-green-500 text-gray-900 px-4 py-1 text-2xl font-black mb-4 transform -rotate-1">
                SEMESTER {{ semester.semester }}
              </h3>
              <br>
              <p class="text-white mb-6 font-bold bg-gray-900 inline-block px-3 py-1 border border-gray-700">{{ semester.period }}</p>
              
              <div class="space-y-4">
                <div class="flex items-center gap-3">
                  <span class="px-2 py-1 bg-white text-gray-900 font-black text-xs uppercase shadow-[2px_2px_0px_0px_rgba(34,197,94,1)]">Mahasiswa</span>
                  <span class="text-white font-bold">{{ semester.studentCount }} orang</span>
                </div>
                
                <div class="pt-4 border-t border-gray-700">
                  <p class="text-sm font-black text-gray-400 mb-3 uppercase tracking-wider">Dosen Pengajar:</p>
                  <div class="flex flex-wrap gap-3">
                    <span 
                      v-for="lecturer in semester.lecturers" 
                      :key="lecturer.name"
                      class="inline-flex items-center px-3 py-1 text-sm font-bold shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] border"
                      :class="lecturer.isAnomaly ? 'bg-red-500 text-white border-gray-900 transform rotate-2' : 'bg-gray-200 text-gray-900 border-gray-900 transform -rotate-1 hover:rotate-0'"
                    >
                      {{ lecturer.name }}
                      <span v-if="lecturer.isAnomaly" class="ml-2 text-[10px] uppercase tracking-wider px-1.5 py-0.5 bg-gray-900 text-red-400 font-black">ANOMALI</span>
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

const stats = ref({
  initialCount: 0,
  currentCount: 0,
  totalSemesters: 0
})
const timeline = ref([])

onMounted(() => {
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
})
</script>

<style scoped>
.font-display {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}
</style>