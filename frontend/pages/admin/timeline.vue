<template>
  <div class="space-y-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <div>
        <h2 class="inline-block -rotate-1 bg-green-500 px-6 py-2 text-4xl font-black uppercase text-gray-900 shadow-[6px_6px_0_rgba(0,0,0,1)]">
          Manajemen Semester
        </h2>
        <p class="mt-4 text-lg font-bold text-gray-600">Data yang sama dengan timeline landing page.</p>
      </div>
      <button class="border-4 border-gray-900 bg-gray-900 px-6 py-3 font-black uppercase text-white shadow-[4px_4px_0_rgba(34,197,94,1)] hover:bg-green-500 hover:text-gray-900" @click="openAdd">
        + Tambah Semester
      </button>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
      <article v-for="semester in semesters" :key="semester.id" class="border-4 border-gray-900 bg-white p-6 shadow-[6px_6px_0_rgba(34,197,94,1)]">
        <div class="flex items-start justify-between gap-4">
          <div class="flex gap-3">
            <div class="flex h-14 w-14 items-center justify-center border-2 border-gray-900 bg-gray-900 text-2xl">{{ semester.icon }}</div>
            <div>
              <h3 class="text-2xl font-black uppercase">Semester {{ semester.semester }}</h3>
              <p class="font-bold text-gray-600">{{ semester.period }}</p>
            </div>
          </div>
          <button class="border-2 border-gray-900 px-3 py-2 text-xs font-black uppercase hover:bg-green-500" @click="openEdit(semester)">Edit</button>
        </div>
        <p class="mt-5 border-y-2 border-gray-200 py-3 font-black">{{ semester.student_count }} mahasiswa</p>
        <div class="mt-4 flex flex-wrap gap-2">
          <span v-for="lecturer in semester.lecturers" :key="lecturer.name" class="border border-gray-900 bg-gray-100 px-2 py-1 text-xs font-bold">
            {{ lecturer.name }}{{ lecturer.isAnomaly ? ' · ANOMALI' : '' }}
          </span>
        </div>
      </article>
    </div>

    <div v-if="loading" class="py-12 text-center font-black uppercase text-gray-500">Memuat semester...</div>

    <div v-if="showModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/90 p-4" @click="closeModal">
      <div class="w-full max-w-2xl border-4 border-gray-900 bg-white p-8 shadow-[16px_16px_0_rgba(34,197,94,1)]" @click.stop>
        <div class="mb-6 flex items-center justify-between">
          <h3 class="text-3xl font-black uppercase">{{ editMode ? 'Edit' : 'Tambah' }} Semester</h3>
          <button type="button" class="h-11 w-11 bg-gray-900 font-black text-white" @click="closeModal">X</button>
        </div>
        <form class="grid gap-5 md:grid-cols-2" @submit.prevent="saveSemester">
          <label><span class="mb-2 block text-sm font-black uppercase">Semester</span><input v-model.number="form.semester" required min="1" type="number" class="w-full border-2 border-gray-900 px-4 py-3 font-bold"></label>
          <label><span class="mb-2 block text-sm font-black uppercase">Icon</span><input v-model="form.icon" required class="w-full border-2 border-gray-900 px-4 py-3 font-bold"></label>
          <label class="md:col-span-2"><span class="mb-2 block text-sm font-black uppercase">Periode</span><input v-model="form.period" required class="w-full border-2 border-gray-900 px-4 py-3 font-bold"></label>
          <label><span class="mb-2 block text-sm font-black uppercase">Jumlah Mahasiswa</span><input v-model.number="form.student_count" required min="0" type="number" class="w-full border-2 border-gray-900 px-4 py-3 font-bold"></label>
          <label><span class="mb-2 block text-sm font-black uppercase">Dosen (pisahkan koma)</span><textarea v-model="form.lecturersText" rows="3" class="w-full border-2 border-gray-900 px-4 py-3 font-bold"></textarea></label>
          <div class="flex gap-4 pt-3 md:col-span-2">
            <button :disabled="saving" class="flex-1 border-4 border-gray-900 bg-gray-900 px-6 py-3 font-black uppercase text-white hover:bg-green-500 hover:text-gray-900">{{ saving ? 'Menyimpan...' : 'Simpan' }}</button>
            <button type="button" class="flex-1 border-4 border-gray-900 px-6 py-3 font-black uppercase" @click="closeModal">Batal</button>
          </div>
        </form>
      </div>
    </div>

    <div
      v-if="errorMessage"
      class="fixed inset-0 z-[70] flex items-center justify-center bg-gray-900/90 p-4 backdrop-blur-md"
      @click="errorMessage = ''"
    >
      <div class="w-full max-w-md border-4 border-gray-900 bg-white p-8 shadow-[12px_12px_0_rgba(239,68,68,1)]" @click.stop>
        <div class="inline-block border-2 border-gray-900 bg-red-500 px-3 py-1 text-sm font-black uppercase text-white">Error</div>
        <h3 class="mt-5 text-3xl font-black uppercase text-gray-900">Proses Gagal</h3>
        <p class="mt-4 whitespace-pre-line font-bold text-gray-600">{{ errorMessage }}</p>
        <button type="button" class="mt-7 w-full border-4 border-gray-900 bg-gray-900 px-4 py-3 font-black uppercase text-white hover:bg-red-500" @click="errorMessage = ''">
          Tutup
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
type Lecturer = { name: string; isAnomaly: boolean }
type Semester = { id: number; semester: number; period: string; icon: string; student_count: number; lecturers: Lecturer[] }

definePageMeta({ layout: 'admin', middleware: 'admin-auth' })
const auth = useAdminAuth()
const config = useRuntimeConfig()
const semesters = ref<Semester[]>([])
const loading = ref(true)
const saving = ref(false)
const showModal = ref(false)
const editMode = ref(false)
const errorMessage = ref('')
const blankForm = () => ({ id: null as number | null, semester: 1, period: '', icon: '📚', student_count: 0, lecturersText: '' })
const form = ref(blankForm())
const headers = computed(() => ({ Authorization: `Bearer ${auth.token.value}` }))

const getErrorMessage = (error: any) => {
  const validationErrors = error?.data?.errors
  if (validationErrors) {
    return Object.values(validationErrors).flat().join('\n')
  }

  return error?.data?.message || error?.message || 'Terjadi kesalahan saat menyimpan semester.'
}

const loadSemesters = async () => {
  loading.value = true
  try {
    semesters.value = await $fetch<Semester[]>(`${config.public.apiBase}/semesters`, { headers: headers.value })
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    loading.value = false
  }
}
const openAdd = () => { editMode.value = false; form.value = { ...blankForm(), semester: semesters.value.length + 1 }; showModal.value = true }
const openEdit = (semester: Semester) => {
  editMode.value = true
  form.value = { ...semester, lecturersText: semester.lecturers.map(item => item.name).join(', ') }
  showModal.value = true
}
const closeModal = () => { showModal.value = false; form.value = blankForm() }
const saveSemester = async () => {
  saving.value = true
  errorMessage.value = ''
  const lecturers = form.value.lecturersText.split(',').map(name => name.trim()).filter(Boolean).map(name => ({ name, isAnomaly: false }))
  try {
    await $fetch(editMode.value ? `${config.public.apiBase}/semesters/${form.value.id}` : `${config.public.apiBase}/semesters`, {
      method: editMode.value ? 'PUT' : 'POST',
      headers: headers.value,
      body: { semester: form.value.semester, period: form.value.period, icon: form.value.icon, student_count: form.value.student_count, lecturers },
    })
    closeModal()
    await loadSemesters()
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    saving.value = false
  }
}
onMounted(loadSemesters)
</script>
