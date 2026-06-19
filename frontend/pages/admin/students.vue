<template>
  <div class="space-y-8">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
          <div>
            <h2 class="text-4xl font-black text-gray-900 uppercase inline-block bg-green-500 px-6 py-2 transform -rotate-1 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
              Manajemen Mahasiswa
            </h2>
            <p class="text-gray-600 font-bold text-lg mt-4">Kelola data mahasiswa TI'23</p>
          </div>
          <button 
            @click="openAddModal"
            class="px-6 py-3 bg-gray-900 text-white border-4 border-gray-900 hover:bg-green-500 hover:text-gray-900 font-black uppercase tracking-wider shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] hover:shadow-[6px_6px_0px_0px_rgba(255,255,255,1)] hover:-translate-y-1 transition-all duration-200 transform"
          >
            ➕ Tambah Mahasiswa
          </button>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white border-4 border-gray-900 p-6 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input 
              v-model="searchQuery"
              type="text" 
              placeholder="Cari nama mahasiswa..."
              class="px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
            />
            <select 
              v-model="filterStatus"
              class="px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold bg-white"
            >
              <option value="">Semua Status</option>
              <option value="active">Aktif</option>
              <option value="cuti">Cuti</option>
              <option value="alumni">Alumni</option>
            </select>
            <button 
              @click="resetFilters"
              class="px-4 py-3 bg-gray-900 text-white border-2 border-gray-900 hover:bg-green-500 hover:text-gray-900 font-black uppercase tracking-wider transition-all duration-200"
            >
              Reset Filter
            </button>
          </div>
        </div>

        <!-- Students Table -->
        <div class="bg-white border-4 border-gray-900 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)] overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-900 text-white">
                <tr>
                  <th class="px-6 py-4 text-left font-black uppercase tracking-wider border-r-2 border-gray-700">Foto</th>
                  <th class="px-6 py-4 text-left font-black uppercase tracking-wider border-r-2 border-gray-700">Nama</th>
                  <th class="px-6 py-4 text-left font-black uppercase tracking-wider border-r-2 border-gray-700">NIM</th>
                  <th class="px-6 py-4 text-left font-black uppercase tracking-wider border-r-2 border-gray-700">Role</th>
                  <th class="px-6 py-4 text-left font-black uppercase tracking-wider border-r-2 border-gray-700">Status</th>
                  <th class="px-6 py-4 text-center font-black uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr 
                  v-for="(student, index) in filteredStudents" 
                  :key="student.id"
                  :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
                  class="border-b-2 border-gray-200 hover:bg-green-50 transition-colors duration-200"
                >
                  <td class="px-6 py-4">
                    <img :src="student.photo" :alt="student.name" class="w-16 h-16 object-cover border-2 border-gray-900" />
                  </td>
                  <td class="px-6 py-4 font-bold text-gray-900">{{ student.name }}</td>
                  <td class="px-6 py-4 font-bold text-gray-700">{{ student.nim }}</td>
                  <td class="px-6 py-4 font-bold text-gray-700">{{ student.role }}</td>
                  <td class="px-6 py-4">
                    <span 
                      :class="{
                        'bg-green-500 text-white': student.status === 'active',
                        'bg-yellow-500 text-gray-900': student.status === 'cuti',
                        'bg-gray-500 text-white': student.status === 'alumni'
                      }"
                      class="px-3 py-1 text-xs font-black uppercase border-2 border-gray-900 inline-block"
                    >
                      {{ student.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex justify-center gap-2">
                      <button 
                        @click="editStudent(student)"
                        class="px-4 py-2 bg-white text-gray-900 border-2 border-gray-900 hover:bg-green-500 font-black uppercase text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 transition-all duration-200 transform"
                      >
                        ✏️
                      </button>
                      <button 
                        @click="deleteStudent(student.id)"
                        class="px-4 py-2 bg-white text-gray-900 border-2 border-gray-900 hover:bg-red-500 hover:text-white font-black uppercase text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 transition-all duration-200 transform"
                      >
                        🗑️
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Add/Edit Modal -->
        <div 
          v-if="showModal" 
          class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/90 backdrop-blur-md"
          @click="closeModal"
        >
          <div 
            class="bg-white border-4 border-gray-900 max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-[16px_16px_0px_0px_rgba(34,197,94,1)] transform rotate-1"
            @click.stop
          >
            <div class="p-8">
              <div class="flex justify-between items-center mb-6">
                <h3 class="text-3xl font-black text-gray-900 uppercase">
                  {{ editMode ? 'Edit' : 'Tambah' }} Mahasiswa
                </h3>
                <button 
                  @click="closeModal"
                  class="w-12 h-12 bg-gray-900 text-white border-2 border-gray-900 hover:bg-red-500 font-black text-xl shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all duration-200"
                >
                  X
                </button>
              </div>

              <form @submit.prevent="saveStudent" class="space-y-6">
                <div>
                  <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Nama Lengkap</label>
                  <input 
                    v-model="formData.name"
                    type="text" 
                    required
                    class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                    placeholder="Masukkan nama lengkap"
                  />
                </div>

                <div>
                  <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">NIM</label>
                  <input 
                    v-model="formData.nim"
                    type="text" 
                    required
                    class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                    placeholder="Masukkan NIM"
                  />
                </div>

                <div>
                  <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Role/Karakter</label>
                  <input 
                    v-model="formData.role"
                    type="text" 
                    required
                    class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                    placeholder="Misal: Tukang Tidur, Si Paling Rajin"
                  />
                </div>

                <div>
                  <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">Status</label>
                  <select 
                    v-model="formData.status"
                    required
                    class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold bg-white"
                  >
                    <option value="active">Aktif</option>
                    <option value="cuti">Cuti</option>
                    <option value="alumni">Alumni</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">URL Foto Normal</label>
                  <input 
                    v-model="formData.photo"
                    type="url" 
                    required
                    class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                    placeholder="https://example.com/photo.jpg"
                  />
                </div>

                <div>
                  <label class="block text-sm font-black text-gray-900 mb-2 uppercase tracking-wider">URL Foto Aib (Optional)</label>
                  <input 
                    v-model="formData.photoAib"
                    type="url" 
                    class="w-full px-4 py-3 border-2 border-gray-900 focus:border-green-500 focus:outline-none font-bold"
                    placeholder="https://example.com/photo-aib.jpg"
                  />
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
import { ref, computed, onMounted } from 'vue'

definePageMeta({
  layout: 'admin',
  middleware: 'admin-auth'
})

const students = ref([])
const searchQuery = ref('')
const filterStatus = ref('')
const showModal = ref(false)
const editMode = ref(false)
const formData = ref({
  id: null,
  name: '',
  nim: '',
  role: '',
  status: 'active',
  photo: '',
  photoAib: ''
})

const filteredStudents = computed(() => {
  return students.value.filter(student => {
    const matchesSearch = student.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         student.nim.includes(searchQuery.value)
    const matchesStatus = !filterStatus.value || student.status === filterStatus.value
    return matchesSearch && matchesStatus
  })
})

const openAddModal = () => {
  editMode.value = false
  resetForm()
  showModal.value = true
}

const editStudent = (student) => {
  editMode.value = true
  formData.value = { ...student }
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
    nim: '',
    role: '',
    status: 'active',
    photo: '',
    photoAib: ''
  }
}

const saveStudent = () => {
  if (editMode.value) {
    const index = students.value.findIndex(s => s.id === formData.value.id)
    if (index !== -1) {
      students.value[index] = { ...formData.value }
    }
  } else {
    const newStudent = {
      ...formData.value,
      id: Date.now()
    }
    students.value.push(newStudent)
  }
  closeModal()
}

const deleteStudent = (id) => {
  if (confirm('Yakin ingin menghapus mahasiswa ini?')) {
    students.value = students.value.filter(s => s.id !== id)
  }
}

const resetFilters = () => {
  searchQuery.value = ''
  filterStatus.value = ''
}

onMounted(() => {
  students.value = [
    {
      id: 1,
      name: 'Willy Pratama',
      nim: '2023001',
      role: 'Korban Monyet',
      status: 'active',
      photo: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80',
      photoAib: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80'
    },
    {
      id: 2,
      name: 'Dhila Azzahra',
      nim: '2023002',
      role: 'Si Jidat Terang',
      status: 'active',
      photo: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80',
      photoAib: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80'
    },
    {
      id: 3,
      name: 'Nico Hartanto',
      nim: '2023003',
      role: 'The Showman',
      status: 'active',
      photo: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80',
      photoAib: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80'
    }
  ]
})
</script>

<style scoped>
.font-display {
  font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
}
</style>
