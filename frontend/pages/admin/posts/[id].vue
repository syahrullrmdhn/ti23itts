<template>
  <div class="space-y-8">
    <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
      <div>
        <p class="text-sm font-black uppercase tracking-[0.2em] text-gray-500">Edit Cerita</p>
        <h2 class="mt-2 inline-block -rotate-1 bg-green-500 px-6 py-2 text-4xl font-black uppercase text-gray-900 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
          Rapihin Ceritanya
        </h2>
        <p class="mt-4 text-lg font-bold text-gray-600">Tinggal revisi isi, cover, atau status publish tanpa modal sempit.</p>
      </div>
      <NuxtLink to="/admin/posts" class="border-4 border-gray-900 bg-white px-6 py-3 font-black uppercase tracking-wider text-gray-900 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1">
        ← Kembali
      </NuxtLink>
    </div>

    <section v-if="pending" class="border-4 border-gray-900 bg-white p-10 text-center font-black uppercase text-gray-500 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]">
      Memuat cerita...
    </section>

    <section v-else class="border-4 border-gray-900 bg-white p-6 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)] sm:p-8">
      <PostEditorForm
        v-model="form"
        :saving="saving"
        submit-label="Simpan Perubahan"
        :initial-cover="coverPreview"
        @submit="savePost"
      />
    </section>

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

definePageMeta({
  layout: 'admin',
  middleware: 'admin-auth',
})

const auth = useAdminAuth()
const config = useRuntimeConfig()
const route = useRoute()
const router = useRouter()
const { mediaUrl } = useApiMedia()

const pending = ref(true)
const saving = ref(false)
const errorMessage = ref('')
const coverPreview = ref('')

const form = ref({
  title: '',
  slug: '',
  excerpt: '',
  content: '',
  isPublished: true,
  publishedAt: '',
})

const requestHeaders = computed(() => ({
  Authorization: `Bearer ${auth.token.value}`,
}))

const toLocalDateTimeValue = (value: string | null) => {
  if (!value) return ''

  const date = new Date(value)
  const offset = date.getTimezoneOffset()
  const local = new Date(date.getTime() - offset * 60000)
  return local.toISOString().slice(0, 16)
}

const apiError = (error: any) => {
  if (error?.status === 413 || error?.response?.status === 413) {
    return 'Ukuran file terlalu besar. Tolong upload gambar cover yang lebih kecil dari 5 MB ya.'
  }

  const validationErrors = error?.data?.errors
  if (validationErrors) {
    return Object.values(validationErrors).flat().join('\n')
  }

  return error?.data?.message || error?.message || 'Terjadi kesalahan saat menyimpan cerita.'
}

const loadPost = async () => {
  pending.value = true
  errorMessage.value = ''

  try {
    const post = await $fetch<PostApi>(`${config.public.apiBase}/posts/${route.params.id}`, {
      headers: requestHeaders.value,
    })

    form.value = {
      title: post.title,
      slug: post.slug,
      excerpt: post.excerpt,
      content: post.content,
      isPublished: post.is_published,
      publishedAt: toLocalDateTimeValue(post.published_at),
    }
    coverPreview.value = mediaUrl(post.cover_image)
  } catch (error) {
    errorMessage.value = apiError(error)
  } finally {
    pending.value = false
  }
}

const savePost = async ({ values, coverFile }: { values: typeof form.value, coverFile: File | null }) => {
  saving.value = true
  errorMessage.value = ''

  try {
    const payload = new FormData()
    payload.append('title', values.title)
    payload.append('slug', values.slug)
    payload.append('excerpt', values.excerpt)
    payload.append('content', values.content)
    payload.append('is_published', values.isPublished ? '1' : '0')
    payload.append('_method', 'PUT')

    if (values.publishedAt) {
      payload.append('published_at', new Date(values.publishedAt).toISOString())
    }

    if (coverFile) {
      payload.append('cover_image', coverFile)
    }

    await $fetch(`${config.public.apiBase}/posts/${route.params.id}`, {
      method: 'POST',
      headers: requestHeaders.value,
      body: payload,
    })

    await router.push('/admin/posts')
  } catch (error) {
    errorMessage.value = apiError(error)
  } finally {
    saving.value = false
  }
}

onMounted(async () => {
  auth.restore()
  await loadPost()
})
</script>
