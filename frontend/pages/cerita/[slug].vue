<template>
  <div class="min-h-screen bg-gray-900 px-4 py-12 text-white sm:px-6 lg:px-8">
    <PublicNavbar />

    <div class="mx-auto mt-24 max-w-5xl">
      <NuxtLink
        to="/cerita"
        class="inline-flex items-center gap-2 border-4 border-white bg-white px-5 py-3 font-black uppercase text-gray-900 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1"
      >
        ← Kembali ke Cerita
      </NuxtLink>

      <div v-if="pending" class="mt-10 border-4 border-white bg-gray-800 p-10 text-center font-black uppercase text-gray-300 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]">
        Memuat cerita...
      </div>

      <article v-else-if="post" class="mt-10 overflow-hidden border-4 border-white bg-white text-gray-900 shadow-[10px_10px_0px_0px_rgba(34,197,94,1)]">
        <div class="aspect-[16/8] overflow-hidden border-b-4 border-gray-900 bg-gray-900">
          <img :src="post.coverImage" :alt="post.title" class="h-full w-full object-cover">
        </div>

        <div class="p-6 sm:p-10">
          <div class="flex flex-wrap gap-3">
            <span class="inline-block -rotate-1 border-2 border-gray-900 bg-green-500 px-4 py-2 text-xs font-black uppercase tracking-[0.2em] shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
              Cerita TI'23
            </span>
            <span class="inline-block border-2 border-gray-900 bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.2em]">
              {{ formatPublishedAt(post.published_at) }}
            </span>
          </div>

          <h1 class="mt-6 text-4xl font-black uppercase tracking-tight sm:text-5xl">{{ post.title }}</h1>

          <p class="mt-6 border-l-4 border-green-500 pl-4 text-lg font-bold leading-relaxed text-gray-700">
            {{ post.excerpt }}
          </p>

          <div class="story-content mt-8 text-base font-bold leading-8 text-gray-800" v-html="post.content">
          </div>
        </div>
      </article>

      <div v-else class="mt-10 border-4 border-red-500 bg-white p-10 text-center text-gray-900 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]">
        <p class="text-3xl font-black uppercase">Cerita tidak ditemukan</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
type PostDetail = {
  id: number
  title: string
  slug: string
  excerpt: string
  content: string
  cover_image: string | null
  published_at: string | null
  coverImage: string
}

const route = useRoute()
const config = useRuntimeConfig()
const { mediaUrl } = useApiMedia()

const { data: post, pending } = await useFetch<PostDetail>(`${config.public.apiBase}/posts/${route.params.slug}`, {
  transform: (item) => ({
    ...item,
    coverImage: mediaUrl(item.cover_image),
  }),
})

const formatPublishedAt = (value: string | null) => {
  if (!value) return 'Cerita TI23'

  return new Intl.DateTimeFormat('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
  }).format(new Date(value))
}

useSeoMeta({
  title: () => post.value ? `${post.value.title} - Cerita TI'23 ITTS` : "Cerita TI'23 ITTS",
  description: () => post.value?.excerpt || 'Baca cerita nostalgia dan catatan seru dari TI’23 ITTS.',
  ogTitle: () => post.value ? `${post.value.title} - Cerita TI'23 ITTS` : "Cerita TI'23 ITTS",
  ogDescription: () => post.value?.excerpt || 'Baca cerita nostalgia dan catatan seru dari TI’23 ITTS.',
  ogUrl: `${config.public.siteUrl}/cerita/${route.params.slug}`,
  ogImage: () => post.value?.coverImage || `${config.public.siteUrl}/social-preview.svg`,
  twitterTitle: () => post.value ? `${post.value.title} - Cerita TI'23 ITTS` : "Cerita TI'23 ITTS",
  twitterDescription: () => post.value?.excerpt || 'Baca cerita nostalgia dan catatan seru dari TI’23 ITTS.',
  twitterImage: () => post.value?.coverImage || `${config.public.siteUrl}/social-preview.svg`,
})
</script>

<style scoped>
.story-content :deep(p) {
  margin-bottom: 1rem;
}

.story-content :deep(ul),
.story-content :deep(ol) {
  margin: 1rem 0;
  padding-left: 1.5rem;
}

.story-content :deep(a) {
  color: #16a34a;
  text-decoration: underline;
}

.story-content :deep(img),
.story-content :deep(video) {
  margin: 1.5rem 0;
  width: 100%;
  border: 4px solid #111827;
}

.story-content :deep(blockquote) {
  margin: 1.5rem 0;
  border-left: 4px solid #22c55e;
  background: #f3f4f6;
  padding: 1rem;
}
</style>
