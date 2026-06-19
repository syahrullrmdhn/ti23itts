<template>
  <div class="space-y-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <div>
        <h2 class="inline-block -rotate-1 bg-green-500 px-6 py-2 text-4xl font-black uppercase text-gray-900 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
          Audit Log
        </h2>
        <p class="mt-4 text-lg font-bold text-gray-600">
          Lacak siapa ngapain di dashboard admin, biar data tetap aman dan jelas jejaknya.
        </p>
      </div>
    </div>

    <div class="border-4 border-gray-900 bg-white p-6 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
      <input
        v-model="searchQuery"
        type="search"
        placeholder="Cari aksi, target, atau ringkasan..."
        class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500"
        @keydown.enter.prevent="applySearch"
      >
    </div>

    <div class="overflow-hidden border-4 border-gray-900 bg-white shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]">
      <div class="overflow-x-auto">
        <table class="w-full min-w-[980px]">
          <thead class="bg-gray-900 text-white">
            <tr>
              <th class="px-6 py-4 text-left font-black uppercase tracking-wider">Waktu</th>
              <th class="px-6 py-4 text-left font-black uppercase tracking-wider">Admin</th>
              <th class="px-6 py-4 text-left font-black uppercase tracking-wider">Aksi</th>
              <th class="px-6 py-4 text-left font-black uppercase tracking-wider">Target</th>
              <th class="px-6 py-4 text-left font-black uppercase tracking-wider">Ringkasan</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="log in logs" :key="log.id" class="border-b-2 border-gray-200 align-top transition-colors hover:bg-green-50">
              <td class="px-6 py-4 text-sm font-bold text-gray-600">{{ formatDate(log.created_at) }}</td>
              <td class="px-6 py-4">
                <p class="font-black text-gray-900">{{ log.user?.name || 'Sistem' }}</p>
                <p class="mt-1 text-xs font-bold uppercase tracking-wider text-gray-500">{{ log.user?.email || 'system@local' }}</p>
              </td>
              <td class="px-6 py-4">
                <span class="inline-block border-2 border-gray-900 bg-green-500 px-3 py-1 text-xs font-black uppercase text-gray-900">
                  {{ formatAction(log.action) }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm font-bold text-gray-700">
                <p class="font-black uppercase text-gray-900">{{ log.target_type }}</p>
                <p class="mt-1">{{ log.target_label || 'Tanpa label' }}</p>
              </td>
              <td class="px-6 py-4 text-sm font-bold text-gray-700">
                <p>{{ log.summary }}</p>
                <div v-if="log.properties && Object.keys(log.properties).length" class="mt-3 flex flex-wrap gap-2">
                  <span
                    v-for="(value, key) in log.properties"
                    :key="key"
                    class="inline-block border border-gray-900 bg-white px-2 py-1 text-[10px] font-black uppercase tracking-wider text-gray-700"
                  >
                    {{ key }}: {{ value }}
                  </span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="loading" class="p-12 text-center font-black uppercase text-gray-500">
        Memuat audit log...
      </div>
      <div v-else-if="logs.length === 0" class="p-12 text-center font-black uppercase text-gray-500">
        Audit log belum ada yang cocok.
      </div>
    </div>

    <div v-if="meta.last_page > 1" class="flex flex-col items-center gap-4">
      <p class="text-sm font-black uppercase tracking-[0.2em] text-gray-500">
        Halaman {{ meta.current_page }} dari {{ meta.last_page }} · Total {{ meta.total }} log
      </p>
      <div class="flex flex-wrap items-center justify-center gap-3">
        <button
          type="button"
          class="border-4 border-gray-900 bg-white px-5 py-3 font-black uppercase text-gray-900 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 disabled:cursor-not-allowed disabled:opacity-40"
          :disabled="meta.current_page === 1 || loading"
          @click="changePage(meta.current_page - 1)"
        >
          Prev
        </button>
        <button
          v-for="page in paginationWindow"
          :key="page"
          type="button"
          class="min-w-12 border-4 px-4 py-3 font-black uppercase transition-all hover:-translate-y-1"
          :class="page === meta.current_page ? 'border-green-500 bg-green-500 text-gray-900 shadow-[4px_4px_0px_0px_rgba(255,255,255,1)]' : 'border-gray-900 bg-white text-gray-900 shadow-[4px_4px_0px_0px_rgba(17,24,39,1)]'"
          @click="changePage(page)"
        >
          {{ page }}
        </button>
        <button
          type="button"
          class="border-4 border-gray-900 bg-white px-5 py-3 font-black uppercase text-gray-900 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 disabled:cursor-not-allowed disabled:opacity-40"
          :disabled="meta.current_page === meta.last_page || loading"
          @click="changePage(meta.current_page + 1)"
        >
          Next
        </button>
      </div>
    </div>

    <div
      v-if="errorMessage"
      class="fixed inset-0 z-[80] flex items-center justify-center bg-gray-900/90 p-4 backdrop-blur-md"
      @click="errorMessage = ''"
    >
      <div class="w-full max-w-md border-4 border-gray-900 bg-white p-8 shadow-[12px_12px_0px_0px_rgba(239,68,68,1)]" @click.stop>
        <div class="inline-block border-2 border-gray-900 bg-red-500 px-3 py-1 text-sm font-black uppercase text-white">Error</div>
        <h3 class="mt-5 text-3xl font-black uppercase text-gray-900">Audit Log Gagal Dimuat</h3>
        <p class="mt-4 whitespace-pre-line font-bold text-gray-600">{{ errorMessage }}</p>
        <button class="mt-7 w-full border-4 border-gray-900 bg-gray-900 px-4 py-3 font-black uppercase text-white hover:bg-red-500" @click="errorMessage = ''">
          Tutup
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
type AuditLog = {
  id: number
  action: string
  target_type: string
  target_label: string | null
  summary: string
  properties: Record<string, string> | null
  created_at: string
  user: {
    name: string
    email: string
  } | null
}

definePageMeta({
  layout: 'admin',
  middleware: 'admin-auth',
})

const auth = useAdminAuth()
const config = useRuntimeConfig()
const logs = ref<AuditLog[]>([])
const loading = ref(true)
const errorMessage = ref('')
const searchQuery = ref('')
const appliedSearch = ref('')
const meta = ref({
  current_page: 1,
  last_page: 1,
  per_page: 15,
  total: 0,
})

const requestHeaders = computed(() => ({
  Authorization: `Bearer ${auth.token.value}`,
}))

const paginationWindow = computed(() => {
  const pages = []
  const start = Math.max(1, meta.value.current_page - 2)
  const end = Math.min(meta.value.last_page, start + 4)

  for (let page = Math.max(1, end - 4); page <= end; page += 1) {
    pages.push(page)
  }

  return pages
})

const formatDate = (date: string) => new Intl.DateTimeFormat('id-ID', {
  dateStyle: 'medium',
  timeStyle: 'short',
}).format(new Date(date))

const formatAction = (action: string) => action.replaceAll('.', ' · ')

const getErrorMessage = (error: any) => {
  const validationErrors = error?.data?.errors
  if (validationErrors) {
    return Object.values(validationErrors).flat().join('\n')
  }

  return error?.data?.message || error?.message || 'Terjadi kesalahan yang tidak diketahui.'
}

const loadLogs = async (page = 1) => {
  loading.value = true
  try {
    const response = await $fetch<{
      data: AuditLog[]
      current_page: number
      last_page: number
      per_page: number
      total: number
    }>(`${config.public.apiBase}/audit-logs`, {
      headers: requestHeaders.value,
      query: {
        page,
        per_page: meta.value.per_page,
        search: appliedSearch.value || undefined,
      },
    })
    logs.value = response.data
    meta.value = {
      current_page: response.current_page,
      last_page: response.last_page,
      per_page: response.per_page,
      total: response.total,
    }
  } catch (error) {
    errorMessage.value = getErrorMessage(error)
  } finally {
    loading.value = false
  }
}

const applySearch = () => {
  appliedSearch.value = searchQuery.value.trim()
  loadLogs(1)
}

const changePage = (page: number) => {
  if (page === meta.value.current_page || loading.value) return
  loadLogs(page)
}

onMounted(loadLogs)
</script>
