<template>
  <div class="space-y-8">
    <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
      <div>
        <h2 class="inline-block -rotate-1 bg-green-500 px-6 py-2 text-4xl font-black uppercase text-gray-900 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
          Manajemen Cerita
        </h2>
        <p class="mt-4 text-lg font-bold text-gray-600">Kelola blog dan cerita nostalgia angkatan dengan tampilan yang tetap satu vibe.</p>
      </div>
      <NuxtLink
        to="/admin/posts/create"
        class="border-4 border-gray-900 bg-gray-900 px-6 py-3 font-black uppercase tracking-wider text-white shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 hover:bg-green-500 hover:text-gray-900"
      >
        + Tulis Cerita
      </NuxtLink>
    </div>

    <div class="grid gap-4 border-4 border-gray-900 bg-white p-6 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)] md:grid-cols-3">
      <input
        v-model="searchQuery"
        type="search"
        placeholder="Cari judul cerita..."
        class="border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500 md:col-span-2"
      >
      <select v-model="filterStatus" class="border-2 border-gray-900 bg-white px-4 py-3 font-bold">
        <option value="">Semua Status</option>
        <option value="published">Published</option>
        <option value="draft">Draft</option>
      </select>
    </div>

    <div class="grid gap-6 lg:grid-cols-2 2xl:grid-cols-3">
      <article
        v-for="post in filteredPosts"
        :key="post.id"
        class="overflow-hidden border-4 border-gray-900 bg-white shadow-[6px_6px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-2"
      >
        <div class="relative aspect-[16/10] overflow-hidden border-b-4 border-gray-900 bg-gray-900">
          <img :src="post.coverImage" :alt="post.title" class="h-full w-full object-cover">
          <div class="absolute left-3 top-3 flex flex-wrap gap-2">
            <span class="inline-block -rotate-2 border-2 border-gray-900 px-3 py-1 text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]" :class="post.is_published ? 'bg-green-500 text-gray-900' : 'bg-yellow-300 text-gray-900'">
              {{ post.is_published ? 'Published' : 'Draft' }}
            </span>
            <span class="inline-block border-2 border-white bg-gray-900 px-3 py-1 text-xs font-black uppercase tracking-wider text-white">
              {{ formatPublishedAt(post.published_at) }}
            </span>
          </div>
        </div>

        <div class="space-y-4 p-6">
          <div>
            <h3 class="text-xl font-black uppercase text-gray-900">{{ post.title }}</h3>
            <p class="mt-3 line-clamp-3 border-l-4 border-green-500 pl-3 text-sm font-bold text-gray-600">
              {{ post.excerpt }}
            </p>
          </div>

          <div class="grid gap-3 text-xs font-black uppercase tracking-[0.15em] text-gray-500">
            <div class="border-2 border-gray-200 bg-gray-50 px-3 py-2">
              Slug: {{ post.slug }}
            </div>
          </div>

          <div class="flex gap-2 border-t-2 border-gray-200 pt-4">
            <NuxtLink
              :to="`/cerita/${post.slug}`"
              target="_blank"
              class="flex-1 border-2 border-gray-900 bg-white px-4 py-2 text-center text-xs font-black uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all hover:-translate-y-1 hover:bg-green-500"
            >
              Lihat
            </NuxtLink>
            <NuxtLink
              :to="`/admin/posts/${post.id}`"
              class="flex-1 border-2 border-gray-900 bg-gray-900 px-4 py-2 text-center text-xs font-black uppercase text-white shadow-[2px_2px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 hover:bg-green-500 hover:text-gray-900"
            >
              Edit
            </NuxtLink>
            <button
              class="flex-1 border-2 border-gray-900 bg-white px-4 py-2 text-xs font-black uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all hover:-translate-y-1 hover:bg-red-500 hover:text-white"
              @click="deletePost(post)"
            >
              Hapus
            </button>
          </div>
        </div>
      </article>
    </div>

    <div v-if="loading || filteredPosts.length === 0" class="border-4 border-gray-900 bg-white p-10 text-center font-black uppercase text-gray-500 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
      {{ loading ? 'Memuat cerita...' : 'Cerita tidak ditemukan.' }}
    </div>

    <div v-if="errorMessage" class="fixed bottom-6 right-6 z-[80] max-w-md border-4 border-gray-900 bg-red-500 p-5 font-bold whitespace-pre-line text-white shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
      {{ errorMessage }}
      <button class="ml-3 font-black" @click="errorMessage = ''">×</button>
    </div>
  </div>
</template>

<script setup lang="ts">
type PostApi = {
  id: number
  title: string
  slug: string
  excerpt: string
  content: string
  cover_image: string | null
  is_published: boolean
  published_at: string | null
}

type PostCard = PostApi & {
  coverImage: string
}

definePageMeta({
  layout: 'admin',
  middleware: 'admin-auth',
})

const auth = useAdminAuth()
const config = useRuntimeConfig()
const { mediaUrl } = useApiMedia()

const posts = ref<PostCard[]>([])
const searchQuery = ref('')
const filterStatus = ref('')
const loading = ref(true)
const errorMessage = ref('')

const requestHeaders = computed(() => ({
  Authorization: `Bearer ${auth.token.value}`,
}))

const normalizePost = (post: PostApi): PostCard => ({
  ...post,
  coverImage: mediaUrl(post.cover_image),
})

const filteredPosts = computed(() => {
  const query = searchQuery.value.trim().toLowerCase()

  return posts.value.filter((post) => {
    const matchesQuery = !query
      || post.title.toLowerCase().includes(query)
      || post.excerpt.toLowerCase().includes(query)
      || post.slug.toLowerCase().includes(query)

    const matchesStatus = !filterStatus.value
      || (filterStatus.value === 'published' && post.is_published)
      || (filterStatus.value === 'draft' && !post.is_published)

    return matchesQuery && matchesStatus
  })
})

const apiError = (error: any) => {
  if (error?.status === 413 || error?.response?.status === 413) {
    return 'Ukuran file terlalu besar. Tolong upload gambar cover yang lebih kecil dari 5 MB ya.'
  }

  const validationErrors = error?.data?.errors
  if (validationErrors) {
    return Object.values(validationErrors).flat().join('\n')
  }

  return error?.data?.message || error?.message || 'Terjadi kesalahan saat memproses cerita.'
}

const formatPublishedAt = (value: string | null) => {
  if (!value) return 'Belum dijadwalkan'

  return new Intl.DateTimeFormat('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  }).format(new Date(value))
}

const loadPosts = async () => {
  loading.value = true

  try {
    const data = await $fetch<PostApi[]>(`${config.public.apiBase}/admin/posts`, {
      headers: requestHeaders.value,
    })

    posts.value = data.map(normalizePost)
  } catch (error) {
    errorMessage.value = apiError(error)
  } finally {
    loading.value = false
  }
}

const deletePost = async (post: PostCard) => {
  if (!window.confirm(`Hapus cerita "${post.title}"?`)) {
    return
  }

  errorMessage.value = ''

  try {
    await $fetch(`${config.public.apiBase}/posts/${post.id}`, {
      method: 'DELETE',
      headers: requestHeaders.value,
    })

    posts.value = posts.value.filter(item => item.id !== post.id)
  } catch (error) {
    errorMessage.value = apiError(error)
  }
}

onMounted(async () => {
  auth.restore()

  if (!auth.token.value) {
    return
  }

  await loadPosts()
})
</script>
