<template>
  <div class="space-y-8">
    <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
      <div>
        <h2 class="inline-block -rotate-1 bg-green-500 px-6 py-2 text-4xl font-black uppercase text-gray-900 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
          Manajemen Episodes
        </h2>
        <p class="mt-4 text-lg font-bold text-gray-600">Kelola Hall of Shame dengan dukungan gambar dan video.</p>
      </div>
      <button
        class="border-4 border-gray-900 bg-gray-900 px-6 py-3 font-black uppercase tracking-wider text-white shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 hover:bg-green-500 hover:text-gray-900"
        @click="openAddModal"
      >
        + Tambah Episode
      </button>
    </div>

    <div class="grid gap-4 border-4 border-gray-900 bg-white p-6 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)] md:grid-cols-3">
      <input
        v-model="searchQuery"
        type="search"
        placeholder="Cari judul episode..."
        class="border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500 md:col-span-2"
      >
      <select v-model="filterCategory" class="border-2 border-gray-900 bg-white px-4 py-3 font-bold">
        <option value="">Semua Kategori</option>
        <option value="ZOOM FAILS">Zoom Fails</option>
        <option value="ZOOM MYSTERY">Zoom Mystery</option>
        <option value="DRAMA">Drama</option>
        <option value="LEGENDARY">Legendary</option>
      </select>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
      <article
        v-for="episode in filteredEpisodes"
        :key="episode.id"
        class="overflow-hidden border-4 border-gray-900 bg-white shadow-[6px_6px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-2"
      >
        <div class="relative aspect-video overflow-hidden border-b-4 border-gray-900 bg-gray-900">
          <img
            v-if="episode.media_type === 'image'"
            :src="episode.imageUrl"
            :alt="episode.title"
            class="h-full w-full object-cover"
          >
          <video
            v-else
            :src="episode.videoUrl || undefined"
            class="h-full w-full object-cover"
            muted
            playsinline
            preload="metadata"
            autoplay
            loop
          />
          <div class="absolute left-3 top-3 flex gap-2">
            <span class="inline-block -rotate-3 border-2 border-gray-900 bg-green-500 px-3 py-1 text-xs font-black uppercase tracking-wider text-gray-900 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
              {{ episode.category }}
            </span>
            <span class="inline-block border-2 border-white bg-gray-900 px-3 py-1 text-xs font-black uppercase tracking-wider text-white">
              {{ episode.media_type === 'video' ? 'Video' : 'Image' }}
            </span>
          </div>
        </div>

        <div class="space-y-4 p-6">
          <div>
            <h3 class="text-xl font-black uppercase text-gray-900">{{ episode.title }}</h3>
            <p class="mt-3 border-l-4 border-green-500 pl-3 text-sm font-bold text-gray-600">
              {{ episode.short_description }}
            </p>
          </div>

          <div class="flex gap-2 border-t-2 border-gray-200 pt-4">
            <button
              class="flex-1 border-2 border-gray-900 bg-gray-900 px-4 py-2 text-xs font-black uppercase text-white shadow-[2px_2px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 hover:bg-green-500 hover:text-gray-900"
              @click="openEditModal(episode)"
            >
              Edit
            </button>
            <button
              class="flex-1 border-2 border-gray-900 bg-white px-4 py-2 text-xs font-black uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all hover:-translate-y-1 hover:bg-red-500 hover:text-white"
              @click="deleteEpisode(episode)"
            >
              Hapus
            </button>
          </div>
        </div>
      </article>
    </div>

    <div v-if="loading || filteredEpisodes.length === 0" class="border-4 border-gray-900 bg-white p-10 text-center font-black uppercase text-gray-500 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
      {{ loading ? 'Memuat episodes...' : 'Episode tidak ditemukan.' }}
    </div>

    <Teleport to="body">
      <div
        v-if="showModal"
        class="fixed inset-0 z-[9999] min-h-screen overflow-y-auto bg-gray-900/95 px-4 py-8 backdrop-blur-sm"
        @click="closeModal"
      >
        <div class="mx-auto w-full max-w-4xl border-4 border-gray-900 bg-white p-6 shadow-[16px_16px_0px_0px_rgba(34,197,94,1)] sm:p-8" @click.stop>
          <div class="mb-6 flex items-center justify-between gap-4">
            <h3 class="text-3xl font-black uppercase text-gray-900">{{ editMode ? 'Edit' : 'Tambah' }} Episode</h3>
            <button type="button" class="h-11 w-11 shrink-0 bg-gray-900 font-black text-white hover:bg-red-500" @click="closeModal">X</button>
          </div>

          <form class="grid gap-5 md:grid-cols-2" @submit.prevent="saveEpisode">
            <label class="block">
              <span class="mb-2 block text-sm font-black uppercase">Judul Episode</span>
              <input v-model="form.title" required class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500">
            </label>

            <label class="block">
              <span class="mb-2 block text-sm font-black uppercase">Kategori</span>
              <select v-model="form.category" class="w-full border-2 border-gray-900 bg-white px-4 py-3 font-bold">
                <option value="ZOOM FAILS">Zoom Fails</option>
                <option value="ZOOM MYSTERY">Zoom Mystery</option>
                <option value="DRAMA">Drama</option>
                <option value="LEGENDARY">Legendary</option>
              </select>
            </label>

            <label class="block">
              <span class="mb-2 block text-sm font-black uppercase">Tipe Media</span>
              <select v-model="form.mediaType" class="w-full border-2 border-gray-900 bg-white px-4 py-3 font-bold">
                <option value="image">Gambar</option>
                <option value="video">Video</option>
              </select>
            </label>

            <label class="block">
              <span class="mb-2 block text-sm font-black uppercase">Sumber Media</span>
              <select v-model="form.mediaSource" class="w-full border-2 border-gray-900 bg-white px-4 py-3 font-bold">
                <option value="url">Link URL</option>
                <option value="upload">Upload File</option>
              </select>
            </label>

            <div class="grid gap-5 border-4 border-gray-900 bg-gray-50 p-5 md:col-span-2 md:grid-cols-[1.2fr_0.8fr]">
              <div class="space-y-4">
                <label v-if="form.mediaSource === 'url'" class="block">
                  <span class="mb-2 block text-sm font-black uppercase">
                    {{ form.mediaType === 'video' ? 'Link Video' : 'Link Gambar' }}
                  </span>
                  <input
                    v-model="form.mediaUrl"
                    :type="form.mediaType === 'video' ? 'url' : 'url'"
                    :placeholder="form.mediaType === 'video' ? 'https://example.com/video.mp4' : 'https://example.com/image.jpg'"
                    class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500"
                  >
                </label>

                <label v-else class="block">
                  <span class="mb-2 block text-sm font-black uppercase">
                    {{ form.mediaType === 'video' ? 'Upload Video' : 'Upload Gambar' }}
                  </span>
                  <input
                    type="file"
                    :accept="form.mediaType === 'video' ? 'video/mp4,video/webm,video/quicktime' : 'image/jpeg,image/png,image/webp'"
                    class="w-full border-2 border-gray-900 bg-white p-3 text-sm font-bold file:mr-3 file:border-0 file:bg-gray-900 file:px-3 file:py-2 file:font-black file:uppercase file:text-white"
                    @change="selectMediaFile"
                  >
                  <p class="mt-2 text-xs font-bold text-gray-500">
                    {{ form.mediaType === 'video' ? 'MP4, WebM, atau MOV maksimal 50 MB.' : 'JPG, PNG, atau WebP maksimal 50 MB.' }}
                  </p>
                </label>
              </div>

              <div class="overflow-hidden border-2 border-gray-900 bg-gray-900">
                <img v-if="previewType === 'image' && previewUrl" :src="previewUrl" alt="Preview media" class="h-full min-h-48 w-full object-cover">
                <video v-else-if="previewType === 'video' && previewUrl" :src="previewUrl" class="h-full min-h-48 w-full object-cover" controls muted playsinline />
                <div v-else class="flex min-h-48 items-center justify-center px-6 text-center text-sm font-black uppercase text-gray-400">
                  Preview media bakal muncul di sini.
                </div>
              </div>
            </div>

            <label class="block md:col-span-2">
              <span class="mb-2 block text-sm font-black uppercase">Deskripsi Singkat</span>
              <input v-model="form.shortDescription" required class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500">
            </label>

            <label class="block md:col-span-2">
              <span class="mb-2 block text-sm font-black uppercase">Deskripsi Lengkap</span>
              <textarea v-model="form.fullDescription" rows="5" required class="w-full resize-none border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500" />
            </label>

            <label class="block">
              <span class="mb-2 block text-sm font-black uppercase">Tanggal</span>
              <input v-model="form.date" required placeholder="Misal: Oktober 2023" class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500">
            </label>

            <label class="block">
              <span class="mb-2 block text-sm font-black uppercase">Partisipan</span>
              <input v-model="form.participants" required placeholder="Misal: Willy & The Monkey" class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500">
            </label>

            <div class="flex flex-col gap-4 pt-3 sm:flex-row md:col-span-2">
              <button :disabled="saving" class="flex-1 border-4 border-gray-900 bg-gray-900 px-6 py-3 font-black uppercase text-white shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] hover:bg-green-500 hover:text-gray-900 disabled:opacity-50">
                {{ saving ? 'Menyimpan...' : 'Simpan' }}
              </button>
              <button type="button" class="flex-1 border-4 border-gray-900 bg-white px-6 py-3 font-black uppercase" @click="closeModal">
                Batal
              </button>
            </div>
          </form>
        </div>
      </div>
    </Teleport>

    <div v-if="errorMessage" class="fixed bottom-6 right-6 z-[80] max-w-md border-4 border-gray-900 bg-red-500 p-5 font-bold text-white shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
      {{ errorMessage }}
      <button class="ml-3 font-black" @click="errorMessage = ''">×</button>
    </div>
  </div>
</template>

<script setup lang="ts">
type EpisodeApi = {
  id: number
  category: string
  title: string
  image: string
  media_type: 'image' | 'video'
  media_source: 'url' | 'upload'
  video_url: string | null
  short_description: string
  full_description: string
  date: string
  participants: string
}

type EpisodeCard = EpisodeApi & {
  imageUrl: string
  videoUrl: string | null
}

definePageMeta({
  layout: 'admin',
  middleware: 'admin-auth',
})

const auth = useAdminAuth()
const config = useRuntimeConfig()
const { mediaUrl } = useApiMedia()
const episodes = ref<EpisodeCard[]>([])
const searchQuery = ref('')
const filterCategory = ref('')
const showModal = ref(false)
const editMode = ref(false)
const loading = ref(true)
const saving = ref(false)
const errorMessage = ref('')
const mediaFile = ref<File | null>(null)
const previewUrl = ref('')
const previewType = ref<'image' | 'video'>('image')

const emptyForm = () => ({
  id: null as number | null,
  category: 'ZOOM FAILS',
  title: '',
  mediaType: 'image' as 'image' | 'video',
  mediaSource: 'url' as 'url' | 'upload',
  mediaUrl: '',
  shortDescription: '',
  fullDescription: '',
  date: '',
  participants: '',
})

const form = ref(emptyForm())

const requestHeaders = computed(() => ({
  Authorization: `Bearer ${auth.token.value}`,
}))

const filteredEpisodes = computed(() => {
  const query = searchQuery.value.trim().toLowerCase()

  return episodes.value.filter((episode) => {
    const matchesSearch = !query || episode.title.toLowerCase().includes(query)
    const matchesCategory = !filterCategory.value || episode.category === filterCategory.value
    return matchesSearch && matchesCategory
  })
})

const apiError = (error: any) => {
  const validationErrors = error?.data?.errors
  if (validationErrors) {
    return Object.values(validationErrors).flat().join('\n')
  }

  return error?.data?.message || error?.message || 'Proses gagal.'
}

const releasePreview = () => {
  if (previewUrl.value.startsWith('blob:')) {
    URL.revokeObjectURL(previewUrl.value)
  }
}

const resetMedia = () => {
  releasePreview()
  mediaFile.value = null
  previewUrl.value = ''
  previewType.value = 'image'
}

const normalizeEpisode = (episode: EpisodeApi): EpisodeCard => ({
  ...episode,
  imageUrl: mediaUrl(episode.image || null),
  videoUrl: episode.video_url ? mediaUrl(episode.video_url) : null,
})

const loadEpisodes = async () => {
  loading.value = true
  try {
    const data = await $fetch<EpisodeApi[]>(`${config.public.apiBase}/episodes`, {
      headers: requestHeaders.value,
    })
    episodes.value = data.map(normalizeEpisode)
  } catch (error) {
    errorMessage.value = apiError(error)
  } finally {
    loading.value = false
  }
}

const openAddModal = () => {
  editMode.value = false
  resetMedia()
  form.value = emptyForm()
  showModal.value = true
}

const openEditModal = (episode: EpisodeCard) => {
  editMode.value = true
  resetMedia()
  form.value = {
    id: episode.id,
    category: episode.category,
    title: episode.title,
    mediaType: episode.media_type,
    mediaSource: episode.media_source,
    mediaUrl: episode.media_type === 'video' ? (episode.video_url || '') : episode.image,
    shortDescription: episode.short_description,
    fullDescription: episode.full_description,
    date: episode.date,
    participants: episode.participants,
  }
  previewType.value = episode.media_type
  previewUrl.value = episode.media_type === 'video' ? (episode.videoUrl || '') : episode.imageUrl
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  resetMedia()
  form.value = emptyForm()
}

const selectMediaFile = (event: Event) => {
  const input = event.target as HTMLInputElement
  const file = input.files?.[0] || null
  if (!file) return

  resetMedia()
  mediaFile.value = file
  previewType.value = form.value.mediaType
  previewUrl.value = URL.createObjectURL(file)
}

const saveEpisode = async () => {
  saving.value = true
  errorMessage.value = ''

  try {
    const payload = new FormData()
    payload.append('category', form.value.category)
    payload.append('title', form.value.title)
    payload.append('media_type', form.value.mediaType)
    payload.append('media_source', form.value.mediaSource)
    payload.append('short_description', form.value.shortDescription)
    payload.append('full_description', form.value.fullDescription)
    payload.append('date', form.value.date)
    payload.append('participants', form.value.participants)

    if (form.value.mediaSource === 'upload') {
      if (mediaFile.value) {
        payload.append('media_file', mediaFile.value)
      }
    } else if (form.value.mediaType === 'video') {
      payload.append('video_url_input', form.value.mediaUrl)
    } else {
      payload.append('image_url', form.value.mediaUrl)
    }

    if (editMode.value) {
      payload.append('_method', 'PUT')
    }

    await $fetch(editMode.value ? `${config.public.apiBase}/episodes/${form.value.id}` : `${config.public.apiBase}/episodes`, {
      method: 'POST',
      headers: requestHeaders.value,
      body: payload,
    })

    closeModal()
    await loadEpisodes()
  } catch (error) {
    errorMessage.value = apiError(error)
  } finally {
    saving.value = false
  }
}

const deleteEpisode = async (episode: EpisodeCard) => {
  if (!confirm(`Hapus episode ${episode.title}?`)) return

  try {
    await $fetch(`${config.public.apiBase}/episodes/${episode.id}`, {
      method: 'DELETE',
      headers: requestHeaders.value,
    })
    await loadEpisodes()
  } catch (error) {
    errorMessage.value = apiError(error)
  }
}

watch(() => form.value.mediaType, (value) => {
  previewType.value = value

  if (form.value.mediaSource === 'url') {
    const activeUrl = form.value.mediaUrl.trim()
    previewUrl.value = activeUrl ? mediaUrl(activeUrl) : ''
  } else if (!mediaFile.value) {
    previewUrl.value = ''
  }
})

watch(() => form.value.mediaSource, (value) => {
  form.value.mediaUrl = value === 'url' ? form.value.mediaUrl : ''
  if (value === 'upload' && !mediaFile.value) {
    previewUrl.value = ''
  }
  if (value === 'url') {
    resetMedia()
  }
})

watch(() => form.value.mediaUrl, (value) => {
  if (form.value.mediaSource !== 'url') return
  previewUrl.value = value.trim() ? mediaUrl(value.trim()) : ''
})

watch(showModal, (open) => {
  if (import.meta.client) {
    document.body.style.overflow = open ? 'hidden' : ''
  }
})

onMounted(loadEpisodes)

onBeforeUnmount(() => {
  resetMedia()
  if (import.meta.client) {
    document.body.style.overflow = ''
  }
})
</script>
