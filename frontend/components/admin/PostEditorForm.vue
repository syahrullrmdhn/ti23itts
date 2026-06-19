<template>
  <form class="grid gap-5 md:grid-cols-2" @submit.prevent="submit">
    <label class="block md:col-span-2">
      <span class="mb-2 block text-sm font-black uppercase">Judul Cerita</span>
      <input v-model="localForm.title" required class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500">
    </label>

    <label class="block">
      <span class="mb-2 block text-sm font-black uppercase">Slug</span>
      <input v-model="localForm.slug" placeholder="opsional, auto dari judul" class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500">
    </label>

    <label class="block">
      <span class="mb-2 block text-sm font-black uppercase">Tanggal Publish</span>
      <input v-model="localForm.publishedAt" type="datetime-local" class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500">
    </label>

    <label class="block md:col-span-2">
      <span class="mb-2 block text-sm font-black uppercase">Ringkasan</span>
      <textarea v-model="localForm.excerpt" rows="3" required class="w-full resize-none border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500" />
    </label>

    <div class="grid gap-5 border-4 border-gray-900 bg-gray-50 p-5 md:col-span-2 md:grid-cols-[1.1fr_0.9fr]">
      <div class="space-y-4">
        <label class="block">
          <span class="mb-2 block text-sm font-black uppercase">Upload Cover</span>
          <input
            type="file"
            accept="image/jpeg,image/png,image/webp"
            class="w-full border-2 border-gray-900 bg-white p-3 text-sm font-bold file:mr-3 file:border-0 file:bg-gray-900 file:px-3 file:py-2 file:font-black file:uppercase file:text-white"
            @change="selectCoverImage"
          >
          <p class="mt-2 text-xs font-bold text-gray-500">JPG, PNG, atau WebP maksimal 5 MB.</p>
        </label>

        <label class="flex items-center gap-3 border-2 border-gray-900 bg-white px-4 py-3">
          <input v-model="localForm.isPublished" type="checkbox" class="h-5 w-5 accent-green-500">
          <span class="text-sm font-black uppercase text-gray-900">Langsung publish cerita ini</span>
        </label>
      </div>

      <div class="overflow-hidden border-2 border-gray-900 bg-gray-900">
        <img v-if="coverPreview" :src="coverPreview" alt="Preview cover cerita" class="h-full min-h-56 w-full object-cover">
        <div v-else class="flex min-h-56 items-center justify-center px-6 text-center text-sm font-black uppercase text-gray-400">
          Preview cover bakal muncul di sini.
        </div>
      </div>
    </div>

    <div class="block md:col-span-2">
      <span class="mb-2 block text-sm font-black uppercase">Isi Cerita</span>
      <ClientOnly>
        <Editor
          v-model="localForm.content"
          :api-key="tinyMceApiKey"
          :init="editorInit"
        />
        <template #fallback>
          <textarea v-model="localForm.content" rows="12" required class="w-full resize-none border-2 border-gray-900 px-4 py-3 font-bold leading-relaxed outline-none focus:border-green-500" />
        </template>
      </ClientOnly>
    </div>

    <div class="flex flex-col gap-4 pt-3 sm:flex-row md:col-span-2">
      <button :disabled="saving" class="flex-1 border-4 border-gray-900 bg-gray-900 px-6 py-3 font-black uppercase text-white shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] hover:bg-green-500 hover:text-gray-900 disabled:opacity-50">
        {{ saving ? 'Menyimpan...' : submitLabel }}
      </button>
      <NuxtLink :to="cancelTo" class="flex-1 border-4 border-gray-900 bg-white px-6 py-3 text-center font-black uppercase">
        Batal
      </NuxtLink>
    </div>
  </form>
</template>

<script setup lang="ts">
import Editor from '@tinymce/tinymce-vue'

type PostForm = {
  title: string
  slug: string
  excerpt: string
  content: string
  isPublished: boolean
  publishedAt: string
}

const props = withDefaults(defineProps<{
  modelValue: PostForm
  saving?: boolean
  submitLabel?: string
  cancelTo?: string
  initialCover?: string
}>(), {
  saving: false,
  submitLabel: 'Simpan',
  cancelTo: '/admin/posts',
  initialCover: '',
})

const emit = defineEmits<{
  'update:modelValue': [value: PostForm]
  submit: [payload: { values: PostForm, coverFile: File | null }]
}>()

const config = useRuntimeConfig()
const tinyMceApiKey = config.public.tinyMceApiKey || '2vhezy15g5mg5uguxlq25qmi22buyvmefn6ub42p6lkh253u'

const localForm = ref<PostForm>({ ...props.modelValue })
const coverFile = ref<File | null>(null)
const coverPreview = ref(props.initialCover || '')

const editorInit = {
  height: 420,
  menubar: false,
  branding: false,
  plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code',
  toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media | blockquote table | removeformat code',
  skin: 'oxide',
  content_css: 'default',
  placeholder: 'Tulis cerita terbaik angkatan di sini...',
}

watch(() => props.modelValue, (value) => {
  localForm.value = { ...value }
}, { deep: true })

watch(() => props.initialCover, (value) => {
  if (!coverFile.value) {
    coverPreview.value = value || ''
  }
})

watch(localForm, (value) => {
  emit('update:modelValue', { ...value })
}, { deep: true })

const selectCoverImage = (event: Event) => {
  const input = event.target as HTMLInputElement
  const file = input.files?.[0] || null
  coverFile.value = file

  if (!file) {
    coverPreview.value = props.initialCover || ''
    return
  }

  coverPreview.value = URL.createObjectURL(file)
}

const submit = () => {
  emit('submit', {
    values: { ...localForm.value },
    coverFile: coverFile.value,
  })
}
</script>

<style scoped>
:deep(.tox-tinymce) {
  border: 2px solid #111827 !important;
  box-shadow: none !important;
}

:deep(.tox .tox-toolbar),
:deep(.tox .tox-toolbar__primary),
:deep(.tox .tox-edit-area__iframe) {
  background: #ffffff !important;
}
</style>
