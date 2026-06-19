<template>
  <div class="space-y-8">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
          <div>
            <h2 class="text-4xl font-black text-gray-900 uppercase inline-block bg-green-500 px-6 py-2 transform -rotate-1 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
              Manajemen Timeline
            </h2>
            <p class="text-gray-600 font-bold text-lg mt-4">Kelola perjalanan angkatan per semester</p>
          </div>
          <button 
            @click="openAddModal"
            class="px-6 py-3 bg-gray-900 text-white border-4 border-gray-900 hover:bg-green-500 hover:text-gray-900 font-black uppercase tracking-wider shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] hover:shadow-[6px_6px_0px_0px_rgba(255,255,255,1)] hover:-translate-y-1 transition-all duration-200 transform"
          >
            ➕ Tambah Semester
          </button>
        </div>

        <!-- Timeline List -->
        <div class="space-y-6">
          <div 
            v-for="semester in semesters" 
            :key="semester.id"
            class="bg-white border-4 border-gray-900 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)] hover:shadow-[8px_8px_0px_0px_rgba(34,197,94,1)] transition-all duration-300"
          >
            <div class="p-6 md:p-8">
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div class="flex items-center gap-4">
                  <div class="w-16 h-16 bg-gray-900 border-2 border-gray-900 flex items-center justify-center text-2xl">
                    {{ semester.icon }}
                  </div>
                  <div>
                    <h3 class="text-2xl font-black text-gray-900 uppercase">{{ semester.name }}</h3>
                    <p class="text-gray-600 font-bold">{{ semester.period }}</p>
                  </div>
                </div>
                <div class="flex gap-2">
                  <button 
                    @click="editSemester(semester)"
                    class="px-4 py-2 bg-gray-900 text-white border-2 border-gray-900 hover:bg-green-500 hover:text-gray-900 font-black uppercase text-xs shadow-[2px_2px_0px_0px_rgba(34,197,94,1)] hover:-translate-y-1 transition-all duration-200 transform"
                  >
                    ✏️ Edit
                  </button>
                  <button 
                    @click="deleteSemester(semester.id)"
                    class="px-4 py-2 bg-white text-gray-900 border-2 border-gray-900 hover:bg-red-500 hover:text-white font-black uppercase text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 transition-all duration-200 transform"
                  >
                    🗑️
                  </button>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gray-50 border-2 border-gray-200 p-4">
                  <p class="text-sm font-black text-gray-600 uppercase mb-2">Mahasiswa Aktif</p>
                  <p class="text-3xl font-black text-gray-900">{{ semester.activeStudents }}</p>
                </div>
                <div class="bg-gray-50 border-2 border-gray-200 p-4">
                  <p class="text-sm font-black text-gray-600 uppercase mb-2">Mahasiswa Cuti</p>
                  <p class="text-3xl font-black text-gray-900">{{ semester.cutiStudents }}</p>
                </div>
                <div class="bg-gray-50 border-2 border-gray-200 p-4">
                  <p class="text-sm font-black text-gray-600 uppercase mb-2">Total Dosen</p>
                  <p class="text-3xl font-black text-gray-900">{{ semester.totalLecturers }}</p>
                </div>
              </div>

              <div class="border-t-2 border-gray-200 pt-6">
                <h4 class="text-lg font-black text-gray-900 uppercase mb-4 border-b-4 border-green-500 pb-2 inline-block">
                  Dosen Pengajar
                </h4>
                <div class="flex flex-wrap gap-2">
                  <span 
                    v-for="(lecturer, index) in semester.lecturers" 
                    :key="index"
                    class="px-4 py-2 bg-gray-900 text-white border-2 border-gray-900 font-bold text-sm"
                  >
                    {{ lecturer }}
                  </span>
                </div>
              </div>

              <div v-if="semester.anomalyLecturer" class="mt-6 bg-yellow-50 border-2 border-yellow-400 p-4">
                <p class="text-sm font-black text-gray-900 uppercase mb-2">⚠️ Dosen Anomali</p>
                <p class="text-lg font-bold text-gray-900">{{ semester.anomalyLecturer }}</p>
                <p class="text-sm text-gray-600 font-bold mt-1">{{ semester.anomalyReason }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Add/Edit Modal -->
        <div 
          v-if="showModal" 
          class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/90 backdrop-blur-md overflow-y-auto"
          @click="closeModal"
        >
          <div 
            class="bg-white border-4 border-gray-900 max-w-3xl w-full my-8 shadow-[16px_16px_0px_0px_rgba(34,197,94,1)] transform rotate-1"
            @click.stop
          >
            <div class="p-8">
              <div class="flex justify-between items-center mb-6">
                <h3 class="text-3xl font-black text-gray-900 uppercase">
                  {{ editMode ? 'Edit' : 'Tambah' }} Semester
                </h3>
                <button 
                  @click="closeModal"
                  class="w-12 h-12 bg-gray-900 text-white border-2 border-gray-900 hover:bg-red-500 font-black text-xl shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all duration-200"
                >
                  X
                </button>
              </div>

              <form @submit.prevent="saveSemester" class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Nama Semester</label>
                    <input 
                      v-model="formData.name"
                      type="text" 
                      required
                      class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                      placeholder="Semester 1"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Icon (Emoji)</label>
                    <input 
                      v-model="formData.icon"
                      type="text" 
                      required
                      class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold text-2xl text-center"
                      placeholder="📚"
                    />
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Periode</label>
                  <input 
                    v-model="formData.period"
                    type="text" 
                    required
                    class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                    placeholder="Agustus 2023 - Desember 2023"
                  />
                </div>

                <div class="grid grid-cols-3 gap-4">
                  <div>
                    <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Mhs Aktif</label>
                    <input 
                      v-model.number="formData.activeStudents"
                      type="number" 
                      required
                      min="0"
                      class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Mhs Cuti</label>
                    <input 
                      v-model.number="formData.cutiStudents"
                      type="number" 
                      required
                      min="0"
                      class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Total Dosen</label>
                    <input 
                      v-model.number="formData.totalLecturers"
                      type="number" 
                      required
                      min="0"
                      class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                    />
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Dosen Pengajar (pisahkan dengan koma)</label>
                  <textarea 
                    v-model="formData.lecturersText"
                    required
                    rows="3"
                    class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold resize-none"
                    placeholder="Dr. Ahmad, Prof. Budi, Ir. Citra"
                  ></textarea>
                  <p class="text-xs text-gray-600 font-bold mt-1">Pisahkan nama dosen dengan koma (,)</p>
                </div>

                <div class="border-t-2 border-gray-200 pt-6">
                  <h4 class="text-lg font-black text-gray-900 uppercase mb-4">Dosen Anomali (Optional)</h4>
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Nama Dosen</label>
                      <input 
                        v-model="formData.anomalyLecturer"
                        type="text" 
                        class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                        placeholder="Dr. Anomali"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Alasan Anomali</label>
                      <input 
                        v-model="formData.anomalyReason"
                        type="text" 
                        class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                        placeholder="Sering telat atau metode ngajar unik"
                      />
                    </div>
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
</template>

<script setup>
import { ref, onMounted } from 'vue'

definePageMeta({
  layout: 'admin',
  middleware: 'admin-auth'
})

const semesters = ref([])
const showModal = ref(false)
const editMode = ref(false)
const formData = ref({
  id: null,
  name: '',
  icon: '📚',
  period: '',
  activeStudents: 0,
  cutiStudents: 0,
  totalLecturers: 0,
  lecturersText: '',
  lecturers: [],
  anomalyLecturer: '',
  anomalyReason: ''
})

const openAddModal = () => {
  editMode.value = false
  resetForm()
  showModal.value = true
}

const editSemester = (semester) => {
  editMode.value = true
  formData.value = { 
    ...semester,
    lecturersText: semester.lecturers.join(', ')
  }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  resetForm()
}

const resetForm = () => {
  formData.value = {
    id: null,
    name: '',
    icon: '📚',
    period: '',
    activeStudents: 0,
    cutiStudents: 0,
    totalLecturers: 0,
    lecturersText: '',
    lecturers: [],
    anomalyLecturer: '',
    anomalyReason: ''
  }
}

const saveSemester = () => {
  const lecturersArray = formData.value.lecturersText
    .split(',')
    .map(l => l.trim())
    .filter(l => l.length > 0)

  const semesterData = {
    ...formData.value,
    lecturers: lecturersArray
  }
  delete semesterData.lecturersText

  if (editMode.value) {
    const index = semesters.value.findIndex(s => s.id === semesterData.id)
    if (index !== -1) {
      semesters.value[index] = semesterData
    }
  } else {
    semesterData.id = Date.now()
    semesters.value.push(semesterData)
  }
  
  closeModal()
}

const deleteSemester = (id) => {
  if (confirm('Yakin ingin menghapus semester ini?')) {
    semesters.value = semesters.value.filter(s => s.id !== id)
  }
}

onMounted(() => {
  semesters.value = [
    {
      id: 1,
      name: 'Semester 1',
      icon: '📚',
      period: 'Agustus 2023 - Desember 2023',
      activeStudents: 45,
      cutiStudents: 0,
      totalLecturers: 8,
      lecturers: ['Dr. Ahmad Fauzi', 'Prof. Budi Santoso', 'Ir. Citra Dewi', 'Dr. Dian Permata'],
      anomalyLecturer: 'Dr. Ahmad Fauzi',
      anomalyReason: 'Sering telat masuk kelas tapi materinya bagus'
    },
    {
      id: 2,
      name: 'Semester 2',
      icon: '💻',
      period: 'Januari 2024 - Mei 2024',
      activeStudents: 44,
      cutiStudents: 1,
      totalLecturers: 9,
      lecturers: ['Prof. Budi Santoso', 'Dr. Eko Prasetyo', 'Ir. Farida Hanum', 'Dr. Gunawan'],
      anomalyLecturer: '',
      anomalyReason: ''
    },
    {
      id: 3,
      name: 'Semester 3',
      icon: '🚀',
      period: 'Agustus 2024 - Desember 2024',
      activeStudents: 42,
      cutiStudents: 2,
      totalLecturers: 10,
      lecturers: ['Dr. Hartono', 'Prof. Intan Sari', 'Ir. Joko Widodo', 'Dr. Kartika'],
      anomalyLecturer: 'Prof. Intan Sari',
      anomalyReason: 'Ngasih tugas banyak banget tapi nilai bagus semua'
    }
  ]
})
</script>

<style scoped>
.font-display {
  font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
}
</style>
