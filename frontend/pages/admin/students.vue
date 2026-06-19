<template>
  <div class="space-y-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <div>
        <h2 class="inline-block -rotate-1 bg-green-500 px-6 py-2 text-4xl font-black uppercase text-gray-900 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
          Data Mahasiswa
        </h2>
        <p class="mt-4 text-lg font-bold text-gray-600">Data pribadi hanya tersedia di dashboard admin.</p>
      </div>
      <button class="border-4 border-gray-900 bg-gray-900 px-6 py-3 font-black uppercase text-white shadow-[4px_4px_0_rgba(34,197,94,1)] hover:bg-green-500 hover:text-gray-900" @click="openAddModal">
        + Tambah Mahasiswa
      </button>
    </div>

    <div class="grid gap-4 border-4 border-gray-900 bg-white p-6 shadow-[6px_6px_0_rgba(34,197,94,1)] md:grid-cols-3">
      <input v-model="searchQuery" type="search" placeholder="Cari nama, NIM, atau email..." class="border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500 md:col-span-2">
      <select v-model="filterStatus" class="border-2 border-gray-900 bg-white px-4 py-3 font-bold">
        <option value="">Semua status</option>
        <option value="Aktif">Aktif</option>
        <option value="Cuti">Cuti</option>
        <option value="Alumni">Alumni</option>
      </select>
    </div>

    <div class="overflow-hidden border-4 border-gray-900 bg-white shadow-[8px_8px_0_rgba(34,197,94,1)]">
      <div class="overflow-x-auto">
        <table class="w-full min-w-[1100px]">
          <thead class="bg-gray-900 text-white">
            <tr>
              <th class="px-5 py-4 text-left font-black uppercase">Mahasiswa</th>
              <th class="px-5 py-4 text-left font-black uppercase">Kontak</th>
              <th class="px-5 py-4 text-left font-black uppercase">TTL</th>
              <th class="px-5 py-4 text-left font-black uppercase">Kelas</th>
              <th class="px-5 py-4 text-left font-black uppercase">Status</th>
              <th class="px-5 py-4 text-center font-black uppercase">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="student in filteredStudents" :key="student.id" class="border-b-2 border-gray-200 align-top hover:bg-green-50">
              <td class="px-5 py-4">
                <div class="flex gap-3">
                  <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden border-2 border-gray-900 bg-green-500 text-lg font-black">
                    <img v-if="student.photo" :src="mediaUrl(student.photo)" :alt="student.name" class="h-full w-full object-cover">
                    <svg v-else viewBox="0 0 24 24" aria-hidden="true" class="h-9 w-9 text-gray-900">
                      <path
                        fill="currentColor"
                        d="M12 12c2.76 0 5-2.69 5-6s-2.24-6-5-6-5 2.69-5 6 2.24 6 5 6Zm0 2c-4.42 0-8 2.91-8 6.5 0 .83.67 1.5 1.5 1.5h13c.83 0 1.5-.67 1.5-1.5C20 16.91 16.42 14 12 14Z"
                      />
                    </svg>
                  </div>
                  <div>
                    <p class="font-black text-gray-900">{{ student.name }}</p>
                    <p class="text-sm font-bold text-gray-600">{{ student.nim }}</p>
                    <p class="mt-1 text-xs font-bold text-gray-500">{{ student.gender || '—' }} · {{ student.religion || '—' }}</p>
                  </div>
                </div>
              </td>
              <td class="px-5 py-4 text-sm font-bold text-gray-700">
                <p>{{ student.email || '—' }}</p>
                <p class="mt-1">{{ student.phone || '—' }}</p>
              </td>
              <td class="px-5 py-4 text-sm font-bold text-gray-700">
                {{ student.birth_place || '—' }}<br>
                {{ formatBirthDate(student.birth_date) }}
              </td>
              <td class="px-5 py-4 text-sm font-bold text-gray-700">
                {{ student.class_type || '—' }}<br>
                <span class="text-gray-500">{{ student.entry_type || 'Reguler' }}</span>
              </td>
              <td class="px-5 py-4">
                <span class="inline-block border-2 border-gray-900 bg-green-500 px-3 py-1 text-xs font-black uppercase">{{ student.status }}</span>
              </td>
              <td class="px-5 py-4">
                <div class="flex justify-center gap-2">
                  <button class="border-2 border-gray-900 px-3 py-2 text-xs font-black uppercase hover:bg-green-500" @click="openEditModal(student)">Edit</button>
                  <button class="border-2 border-gray-900 px-3 py-2 text-xs font-black uppercase hover:bg-red-500 hover:text-white" @click="deleteStudent(student)">Hapus</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="loading || filteredStudents.length === 0" class="p-10 text-center font-black uppercase text-gray-500">
        {{ loading ? 'Memuat data...' : 'Mahasiswa tidak ditemukan.' }}
      </div>
    </div>

    <Teleport to="body">
      <div v-if="showModal" class="fixed inset-0 z-[9999] min-h-screen overflow-y-auto bg-gray-900/95 px-4 py-8 backdrop-blur-sm" @click="closeModal">
        <div class="mx-auto w-full max-w-4xl border-4 border-gray-900 bg-white p-6 shadow-[16px_16px_0_rgba(34,197,94,1)] sm:p-8" @click.stop>
          <div class="mb-6 flex items-center justify-between">
            <h3 class="text-3xl font-black uppercase">{{ editMode ? 'Edit' : 'Tambah' }} Mahasiswa</h3>
            <button class="h-11 w-11 shrink-0 bg-gray-900 font-black text-white hover:bg-red-500" @click="closeModal">X</button>
          </div>
          <form class="grid gap-5 md:grid-cols-2" @submit.prevent="saveStudent">
            <div class="grid gap-5 border-4 border-gray-900 bg-gray-50 p-5 md:col-span-2 md:grid-cols-2">
              <label class="block">
                <span class="mb-2 block text-sm font-black uppercase">Foto Profil</span>
                <div class="flex items-center gap-4">
                  <div class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden border-2 border-gray-900 bg-green-500 text-2xl font-black">
                    <img v-if="photoPreview" :src="photoPreview" alt="Preview foto profil" class="h-full w-full object-cover">
                    <svg v-else viewBox="0 0 24 24" aria-hidden="true" class="h-14 w-14 text-gray-900">
                      <path
                        fill="currentColor"
                        d="M12 12c2.76 0 5-2.69 5-6s-2.24-6-5-6-5 2.69-5 6 2.24 6 5 6Zm0 2c-4.42 0-8 2.91-8 6.5 0 .83.67 1.5 1.5 1.5h13c.83 0 1.5-.67 1.5-1.5C20 16.91 16.42 14 12 14Z"
                      />
                    </svg>
                  </div>
                  <input type="file" accept="image/jpeg,image/png,image/webp" class="min-w-0 flex-1 border-2 border-gray-900 bg-white p-3 text-sm font-bold file:mr-3 file:border-0 file:bg-gray-900 file:px-3 file:py-2 file:font-black file:uppercase file:text-white" @change="selectPhoto($event, 'photo')">
                </div>
                <p class="mt-2 text-xs font-bold text-gray-500">JPG, PNG, atau WebP. Maksimal 5 MB.</p>
              </label>
              <label class="block">
                <span class="mb-2 block text-sm font-black uppercase">Foto Hover / Aib</span>
                <div class="flex items-center gap-4">
                  <div class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden border-2 border-gray-900 bg-gray-900 text-2xl font-black text-green-400">
                    <img v-if="aibPhotoPreview" :src="aibPhotoPreview" alt="Preview foto hover" class="h-full w-full object-cover">
                    <svg v-else viewBox="0 0 24 24" aria-hidden="true" class="h-14 w-14 text-green-400">
                      <path
                        fill="currentColor"
                        d="M12 12c2.76 0 5-2.69 5-6s-2.24-6-5-6-5 2.69-5 6 2.24 6 5 6Zm0 2c-4.42 0-8 2.91-8 6.5 0 .83.67 1.5 1.5 1.5h13c.83 0 1.5-.67 1.5-1.5C20 16.91 16.42 14 12 14Z"
                      />
                    </svg>
                  </div>
                  <input type="file" accept="image/jpeg,image/png,image/webp" class="min-w-0 flex-1 border-2 border-gray-900 bg-white p-3 text-sm font-bold file:mr-3 file:border-0 file:bg-gray-900 file:px-3 file:py-2 file:font-black file:uppercase file:text-white" @change="selectPhoto($event, 'aib_photo')">
                </div>
                <p class="mt-2 text-xs font-bold text-gray-500">Opsional; muncul saat kartu mahasiswa di-hover.</p>
              </label>
            </div>
            <label v-for="field in textFields" :key="field.key" class="block">
              <span class="mb-2 block text-sm font-black uppercase">{{ field.label }}</span>
              <input v-model="form[field.key]" :type="field.type || 'text'" :required="field.required" class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500">
            </label>
            <label class="block md:col-span-2">
              <span class="mb-2 block text-sm font-black uppercase">Fun Fact</span>
              <textarea v-model="form.fun_fact" rows="3" class="w-full resize-none border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500"></textarea>
            </label>
            <label class="block md:col-span-2">
              <span class="mb-2 block text-sm font-black uppercase">Pesan Buat Teman-teman</span>
              <textarea v-model="form.message" rows="4" class="w-full resize-none border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500"></textarea>
            </label>
            <label class="block">
              <span class="mb-2 block text-sm font-black uppercase">Jenis Kelamin</span>
              <select v-model="form.gender" class="w-full border-2 border-gray-900 bg-white px-4 py-3 font-bold">
                <option value="">Pilih</option>
                <option>Laki-laki</option>
                <option>Perempuan</option>
              </select>
            </label>
            <label class="block">
              <span class="mb-2 block text-sm font-black uppercase">Status</span>
              <select v-model="form.status" class="w-full border-2 border-gray-900 bg-white px-4 py-3 font-bold">
                <option>Aktif</option>
                <option>Cuti</option>
                <option>Alumni</option>
              </select>
            </label>
            <div class="flex flex-col gap-4 pt-3 sm:flex-row md:col-span-2">
              <button :disabled="saving" class="flex-1 border-4 border-gray-900 bg-gray-900 px-6 py-3 font-black uppercase text-white hover:bg-green-500 hover:text-gray-900 disabled:opacity-50">
                {{ saving ? 'Mengunggah...' : 'Simpan' }}
              </button>
              <button type="button" class="flex-1 border-4 border-gray-900 px-6 py-3 font-black uppercase" @click="closeModal">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </Teleport>

    <div v-if="errorMessage" class="fixed bottom-6 right-6 z-[80] max-w-md border-4 border-gray-900 bg-red-500 p-5 font-bold text-white shadow-[6px_6px_0_rgba(0,0,0,1)]">
      {{ errorMessage }}
      <button class="ml-3 font-black" @click="errorMessage = ''">×</button>
    </div>
  </div>
</template>

<script setup lang="ts">
type Student = {
  id: number
  nim: string
  name: string
  email: string | null
  gender: string | null
  birth_place: string | null
  birth_date: string | null
  religion: string | null
  phone: string | null
  class_type: string | null
  entry_type: string | null
  role: string
  status: 'Aktif' | 'Cuti' | 'Alumni'
  photo: string | null
  aib_photo: string | null
  fun_fact: string | null
  message: string | null
}

definePageMeta({ layout: 'admin', middleware: 'admin-auth' })

const auth = useAdminAuth()
const config = useRuntimeConfig()
const { mediaUrl } = useApiMedia()
const students = ref<Student[]>([])
const searchQuery = ref('')
const filterStatus = ref('')
const loading = ref(true)
const saving = ref(false)
const showModal = ref(false)
const editMode = ref(false)
const errorMessage = ref('')
const MAX_IMAGE_SIZE = 5 * 1024 * 1024
const photoFile = ref<File | null>(null)
const aibPhotoFile = ref<File | null>(null)
const photoPreview = ref('')
const aibPhotoPreview = ref('')
const emptyForm = () => ({
  id: null as number | null, nim: '', name: '', email: '', gender: '', birth_place: '',
  birth_date: '', religion: '', phone: '', class_type: '', entry_type: 'Reguler',
  role: "Mahasiswa TI '23", status: 'Aktif' as Student['status'], fun_fact: '', message: '',
})
const form = ref(emptyForm())
const textFields = [
  { key: 'nim', label: 'NIM', required: true },
  { key: 'name', label: 'Nama Lengkap', required: true },
  { key: 'email', label: 'Email', type: 'email' },
  { key: 'phone', label: 'Telepon' },
  { key: 'birth_place', label: 'Tempat Lahir' },
  { key: 'birth_date', label: 'Tanggal Lahir', type: 'date' },
  { key: 'religion', label: 'Agama' },
  { key: 'class_type', label: 'Kelas' },
  { key: 'entry_type', label: 'Jenis Masuk' },
  { key: 'role', label: 'Role/Karakter', required: true },
] as const

const filteredStudents = computed(() => {
  const query = searchQuery.value.trim().toLowerCase()
  return students.value.filter(student => {
    const matchesQuery = !query || [student.name, student.nim, student.email || ''].some(value => value.toLowerCase().includes(query))
    return matchesQuery && (!filterStatus.value || student.status === filterStatus.value)
  })
})
const headers = computed(() => ({ Authorization: `Bearer ${auth.token.value}` }))
const formatBirthDate = (date: string | null) => date ? new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium' }).format(new Date(`${date}T00:00:00`)) : '—'
const apiError = (error: any) => {
  if (error?.status === 413 || error?.response?.status === 413) {
    return 'Ukuran file terlalu besar. Tolong upload gambar yang lebih kecil dari 5 MB ya.'
  }

  return Object.values(error?.data?.errors || {}).flat().join('\n') || error?.data?.message || error?.message || 'Proses gagal.'
}
const releasePreview = (preview: string) => {
  if (preview.startsWith('blob:')) URL.revokeObjectURL(preview)
}
const resetPhotos = () => {
  releasePreview(photoPreview.value)
  releasePreview(aibPhotoPreview.value)
  photoFile.value = null
  aibPhotoFile.value = null
  photoPreview.value = ''
  aibPhotoPreview.value = ''
}
const selectPhoto = (event: Event, type: 'photo' | 'aib_photo') => {
  const input = event.target as HTMLInputElement
  const file = input.files?.[0] || null
  if (!file) return

  if (file.size > MAX_IMAGE_SIZE) {
    errorMessage.value = 'Ukuran file terlalu besar. Maksimal 5 MB untuk foto mahasiswa.'
    input.value = ''
    return
  }

  const targetPreview = type === 'photo' ? photoPreview : aibPhotoPreview
  releasePreview(targetPreview.value)
  targetPreview.value = URL.createObjectURL(file)

  if (type === 'photo') photoFile.value = file
  else aibPhotoFile.value = file
}

const loadStudents = async () => {
  loading.value = true
  try {
    students.value = await $fetch<Student[]>(`${config.public.apiBase}/admin/students`, { headers: headers.value })
  } catch (error) {
    errorMessage.value = apiError(error)
  } finally {
    loading.value = false
  }
}
const openAddModal = () => {
  editMode.value = false
  errorMessage.value = ''
  resetPhotos()
  form.value = emptyForm()
  showModal.value = true
}
const openEditModal = (student: Student) => {
  editMode.value = true
  errorMessage.value = ''
  resetPhotos()
  form.value = { ...emptyForm(), ...student }
  photoPreview.value = student.photo ? mediaUrl(student.photo) : ''
  aibPhotoPreview.value = student.aib_photo ? mediaUrl(student.aib_photo) : ''
  showModal.value = true
}
const closeModal = () => {
  showModal.value = false
  resetPhotos()
  form.value = emptyForm()
}
const saveStudent = async () => {
  saving.value = true
  errorMessage.value = ''
  try {
    const payload = new FormData()
    for (const [key, value] of Object.entries(form.value)) {
      if (key === 'id' || key === 'photo' || key === 'aib_photo' || value === null || value === '') continue
      payload.append(key, String(value))
    }
    if (photoFile.value) payload.append('photo', photoFile.value)
    if (aibPhotoFile.value) payload.append('aib_photo', aibPhotoFile.value)
    if (editMode.value) payload.append('_method', 'PUT')

    await $fetch(editMode.value ? `${config.public.apiBase}/students/${form.value.id}` : `${config.public.apiBase}/students`, {
      method: 'POST',
      headers: headers.value,
      body: payload,
    })
    closeModal()
    await loadStudents()
  } catch (error) {
    errorMessage.value = apiError(error)
  } finally {
    saving.value = false
  }
}
const deleteStudent = async (student: Student) => {
  if (!confirm(`Hapus ${student.name}?`)) return
  try {
    await $fetch(`${config.public.apiBase}/students/${student.id}`, { method: 'DELETE', headers: headers.value })
    await loadStudents()
  } catch (error) {
    errorMessage.value = apiError(error)
  }
}

onMounted(loadStudents)
onBeforeUnmount(() => {
  resetPhotos()
  if (import.meta.client) document.body.style.overflow = ''
})

watch(showModal, (open) => {
  if (import.meta.client) document.body.style.overflow = open ? 'hidden' : ''
})
</script>
