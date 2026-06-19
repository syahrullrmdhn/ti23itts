<template>
  <div class="space-y-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <div>
        <h2 class="inline-block -rotate-1 bg-green-500 px-6 py-2 text-4xl font-black uppercase text-gray-900 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
          User Management
        </h2>
        <p class="mt-4 text-lg font-bold text-gray-600">
          Kelola akun admin Institut Teknologi Tangerang Selatan.
        </p>
      </div>
      <button
        class="border-4 border-gray-900 bg-gray-900 px-6 py-3 font-black uppercase tracking-wider text-white shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 hover:bg-green-500 hover:text-gray-900"
        @click="openAddModal"
      >
        + Tambah User
      </button>
    </div>

    <div class="border-4 border-gray-900 bg-white p-6 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
      <input
        v-model="searchQuery"
        type="search"
        placeholder="Cari nama atau email user..."
        class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500"
      >
    </div>

    <div class="overflow-hidden border-4 border-gray-900 bg-white shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-900 text-white">
            <tr>
              <th class="border-r-2 border-gray-700 px-6 py-4 text-left font-black uppercase tracking-wider">Nama</th>
              <th class="border-r-2 border-gray-700 px-6 py-4 text-left font-black uppercase tracking-wider">Email</th>
              <th class="border-r-2 border-gray-700 px-6 py-4 text-left font-black uppercase tracking-wider">Dibuat</th>
              <th class="px-6 py-4 text-center font-black uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(managedUser, index) in filteredUsers"
              :key="managedUser.id"
              class="border-b-2 border-gray-200 transition-colors hover:bg-green-50"
              :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
            >
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <span class="flex h-11 w-11 items-center justify-center border-2 border-gray-900 bg-green-500 text-lg font-black">
                    {{ managedUser.name.charAt(0).toUpperCase() }}
                  </span>
                  <div>
                    <p class="font-black text-gray-900">{{ managedUser.name }}</p>
                    <span
                      v-if="managedUser.id === auth.user.value?.id"
                      class="mt-1 inline-block border border-gray-900 bg-yellow-300 px-2 py-0.5 text-[10px] font-black uppercase"
                    >
                      Akun Aktif
                    </span>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 font-bold text-gray-700">{{ managedUser.email }}</td>
              <td class="px-6 py-4 font-bold text-gray-600">{{ formatDate(managedUser.created_at) }}</td>
              <td class="px-6 py-4">
                <div class="flex justify-center gap-2">
                  <button
                    class="border-2 border-gray-900 bg-white px-4 py-2 text-xs font-black uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all hover:-translate-y-1 hover:bg-green-500"
                    @click="openEditModal(managedUser)"
                  >
                    Edit
                  </button>
                  <button
                    class="border-2 border-gray-900 bg-white px-4 py-2 text-xs font-black uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all hover:-translate-y-1 hover:bg-red-500 hover:text-white disabled:cursor-not-allowed disabled:opacity-40"
                    :disabled="managedUser.id === auth.user.value?.id"
                    @click="requestDelete(managedUser)"
                  >
                    Hapus
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="!loading && filteredUsers.length === 0" class="p-12 text-center font-black uppercase text-gray-500">
        User tidak ditemukan.
      </div>
      <div v-if="loading" class="p-12 text-center font-black uppercase text-gray-500">
        Memuat data user...
      </div>
    </div>

    <div
      v-if="showFormModal"
      class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/90 p-4 backdrop-blur-md"
      @click="closeFormModal"
    >
      <div
        class="max-h-[90vh] w-full max-w-xl overflow-y-auto border-4 border-gray-900 bg-white p-8 shadow-[16px_16px_0px_0px_rgba(34,197,94,1)]"
        @click.stop
      >
        <div class="mb-6 flex items-center justify-between">
          <h3 class="text-3xl font-black uppercase text-gray-900">
            {{ editMode ? 'Edit User' : 'Tambah User' }}
          </h3>
          <button class="h-11 w-11 bg-gray-900 font-black text-white hover:bg-red-500" @click="closeFormModal">X</button>
        </div>

        <form class="space-y-5" @submit.prevent="saveUser">
          <label class="block">
            <span class="mb-2 block text-sm font-black uppercase tracking-wider">Nama</span>
            <input v-model="form.name" required class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500">
          </label>
          <label class="block">
            <span class="mb-2 block text-sm font-black uppercase tracking-wider">Email</span>
            <input v-model="form.email" type="email" required class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500">
          </label>
          <label class="block">
            <span class="mb-2 block text-sm font-black uppercase tracking-wider">
              Password {{ editMode ? '(kosongkan jika tidak diubah)' : '' }}
            </span>
            <input v-model="form.password" type="password" :required="!editMode" minlength="8" class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500">
          </label>
          <label class="block">
            <span class="mb-2 block text-sm font-black uppercase tracking-wider">Konfirmasi Password</span>
            <input v-model="form.password_confirmation" type="password" :required="!editMode || Boolean(form.password)" minlength="8" class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500">
          </label>
          <div class="flex gap-4 pt-3">
            <button
              type="submit"
              :disabled="saving"
              class="flex-1 border-4 border-gray-900 bg-gray-900 px-6 py-3 font-black uppercase text-white shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] hover:bg-green-500 hover:text-gray-900 disabled:opacity-50"
            >
              {{ saving ? 'Menyimpan...' : 'Simpan' }}
            </button>
            <button type="button" class="flex-1 border-4 border-gray-900 bg-white px-6 py-3 font-black uppercase" @click="closeFormModal">
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>

    <div
      v-if="deleteTarget"
      class="fixed inset-0 z-[70] flex items-center justify-center bg-gray-900/90 p-4 backdrop-blur-md"
      @click="deleteTarget = null"
    >
      <div class="w-full max-w-md border-4 border-gray-900 bg-white p-8 shadow-[12px_12px_0px_0px_rgba(239,68,68,1)]" @click.stop>
        <h3 class="text-2xl font-black uppercase">Hapus User?</h3>
        <p class="mt-4 font-bold text-gray-600">Akun {{ deleteTarget.name }} tidak akan bisa login lagi.</p>
        <div class="mt-7 flex gap-3">
          <button class="flex-1 border-4 border-gray-900 bg-red-500 px-4 py-3 font-black uppercase text-white" @click="deleteUser">Ya, Hapus</button>
          <button class="flex-1 border-4 border-gray-900 bg-white px-4 py-3 font-black uppercase" @click="deleteTarget = null">Batal</button>
        </div>
      </div>
    </div>

    <div
      v-if="errorMessage"
      class="fixed inset-0 z-[80] flex items-center justify-center bg-gray-900/90 p-4 backdrop-blur-md"
      @click="errorMessage = ''"
    >
      <div class="w-full max-w-md border-4 border-gray-900 bg-white p-8 shadow-[12px_12px_0px_0px_rgba(239,68,68,1)]" @click.stop>
        <div class="inline-block border-2 border-gray-900 bg-red-500 px-3 py-1 text-sm font-black uppercase text-white">Error</div>
        <h3 class="mt-5 text-3xl font-black uppercase text-gray-900">Proses Gagal</h3>
        <p class="mt-4 whitespace-pre-line font-bold text-gray-600">{{ errorMessage }}</p>
        <button class="mt-7 w-full border-4 border-gray-900 bg-gray-900 px-4 py-3 font-black uppercase text-white hover:bg-red-500" @click="errorMessage = ''">
          Tutup
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
type ManagedUser = {
  id: number
  name: string
  email: string
  created_at: string
}

definePageMeta({
  layout: 'admin',
  middleware: 'admin-auth',
})

const auth = useAdminAuth()
const config = useRuntimeConfig()
const users = ref<ManagedUser[]>([])
const searchQuery = ref('')
const loading = ref(true)
const saving = ref(false)
const showFormModal = ref(false)
const editMode = ref(false)
const deleteTarget = ref<ManagedUser | null>(null)
const errorMessage = ref('')
const form = ref({
  id: null as number | null,
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const filteredUsers = computed(() => {
  const query = searchQuery.value.trim().toLowerCase()
  if (!query) return users.value

  return users.value.filter(user =>
    user.name.toLowerCase().includes(query) || user.email.toLowerCase().includes(query),
  )
})

const requestHeaders = computed(() => ({
  Authorization: `Bearer ${auth.token.value}`,
}))

const getErrorMessage = (error: any) => {
  const validationErrors = error?.data?.errors
  if (validationErrors) {
    return Object.values(validationErrors).flat().join('\n')
  }

  return error?.data?.message || error?.message || 'Terjadi kesalahan yang tidak diketahui.'
}

const loadUsers = async () => {
  loading.value = true
  try {
    users.value = await $fetch<ManagedUser[]>(`${config.public.apiBase}/users`, {
      headers: requestHeaders.value,
    })
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    loading.value = false
  }
}

const resetForm = () => {
  form.value = { id: null, name: '', email: '', password: '', password_confirmation: '' }
}

const openAddModal = () => {
  editMode.value = false
  resetForm()
  showFormModal.value = true
}

const openEditModal = (user: ManagedUser) => {
  editMode.value = true
  form.value = {
    id: user.id,
    name: user.name,
    email: user.email,
    password: '',
    password_confirmation: '',
  }
  showFormModal.value = true
}

const closeFormModal = () => {
  showFormModal.value = false
  resetForm()
}

const saveUser = async () => {
  saving.value = true
  try {
    const endpoint = editMode.value
      ? `${config.public.apiBase}/users/${form.value.id}`
      : `${config.public.apiBase}/users`

    await $fetch(endpoint, {
      method: editMode.value ? 'PUT' : 'POST',
      headers: requestHeaders.value,
      body: {
        name: form.value.name,
        email: form.value.email,
        password: form.value.password || undefined,
        password_confirmation: form.value.password_confirmation || undefined,
      },
    })

    closeFormModal()
    await loadUsers()
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    saving.value = false
  }
}

const requestDelete = (user: ManagedUser) => {
  deleteTarget.value = user
}

const deleteUser = async () => {
  if (!deleteTarget.value) return

  try {
    await $fetch(`${config.public.apiBase}/users/${deleteTarget.value.id}`, {
      method: 'DELETE',
      headers: requestHeaders.value,
    })
    deleteTarget.value = null
    await loadUsers()
  } catch (error) {
    deleteTarget.value = null
    errorMessage.value = getErrorMessage(error)
  }
}

const formatDate = (date: string) => new Intl.DateTimeFormat('id-ID', {
  day: '2-digit',
  month: 'short',
  year: 'numeric',
}).format(new Date(date))

onMounted(loadUsers)
</script>
