<template>
  <div class="min-h-screen bg-gray-900 text-white">
    <PublicNavbar />

    <section class="px-4 pb-20 pt-36 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-7xl">
        <div class="max-w-4xl">
          <p class="inline-block -rotate-1 border-2 border-gray-900 bg-green-500 px-4 py-2 text-xs font-black uppercase tracking-[0.2em] text-gray-900 shadow-[4px_4px_0px_0px_rgba(255,255,255,1)]">
            Blog / Cerita
          </p>
          <h1 class="mt-6 text-4xl font-black uppercase tracking-tight text-white sm:text-6xl">
            Cerita-cerita kecil yang bikin TI'23 tetap hidup.
          </h1>
          <p class="mt-5 max-w-3xl border-l-4 border-green-500 pl-4 text-lg font-bold leading-relaxed text-gray-300">
            Tempat buat nostalgia, catatan lucu, pesan hangat, sampai cerita yang layak dikenang lebih lama dari deadline tugas.
          </p>
        </div>

        <div class="mt-10 border-4 border-white bg-white p-4 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
          <input
            v-model="searchQuery"
            type="search"
            placeholder="Cari judul atau isi cerita..."
            class="w-full border-2 border-gray-900 px-4 py-4 font-black text-gray-900 outline-none focus:border-green-500"
          >
        </div>

        <div v-if="pending" class="mt-10 border-4 border-white bg-gray-800 p-10 text-center font-black uppercase text-gray-300 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]">
          Memuat cerita...
        </div>

        <div v-else class="mt-10 grid gap-8 lg:grid-cols-2 xl:grid-cols-3">
          <NuxtLink
            v-for="post in filteredPosts"
            :key="post.id"
            :to="`/cerita/${post.slug}`"
            class="group overflow-hidden border-4 border-white bg-white text-gray-900 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-2"
          >
            <div class="aspect-[16/10] overflow-hidden border-b-4 border-gray-900 bg-gray-900">
              <img :src="post.coverImage" :alt="post.title" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
            </div>

            <div class="space-y-4 p-6">
              <div class="flex flex-wrap gap-3">
                <span class="inline-block -rotate-1 border-2 border-gray-900 bg-green-500 px-3 py-1 text-xs font-black uppercase tracking-[0.2em] shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                  Cerita TI'23
                </span>
                <span class="inline-block border-2 border-gray-900 bg-white px-3 py-1 text-xs font-black uppercase tracking-[0.2em]">
                  {{ formatPublishedAt(post.published_at) }}
                </span>
              </div>

              <h2 class="text-2xl font-black uppercase leading-tight">{{ post.title }}</h2>
              <p class="line-clamp-4 text-sm font-bold leading-relaxed text-gray-600">{{ post.excerpt }}</p>

              <div class="border-t-2 border-gray-200 pt-4 text-sm font-black uppercase tracking-[0.2em] text-gray-900">
                Baca Cerita →
              </div>
            </div>
          </NuxtLink>
        </div>

        <div v-if="!pending && filteredPosts.length === 0" class="mt-10 border-4 border-white bg-white p-10 text-center text-gray-900 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]">
          <p class="text-3xl font-black uppercase">Cerita belum ketemu</p>
          <p class="mt-3 font-bold text-gray-600">Coba ganti kata kunci, atau tunggu admin nulis nostalgia baru.</p>
        </div>
      </div>
    </section>

    <Footer />
  </div>
</template>

<script setup lang="ts">
type PublicPost = {
  id: number
  title: string
  slug: string
  excerpt: string
  content: string
  cover_image: string | null
  published_at: string | null
  coverImage: string
}

const config = useRuntimeConfig()
const { mediaUrl } = useApiMedia()
const searchQuery = ref('')

const { data: posts, pending } = await useFetch<PublicPost[]>(`${config.public.apiBase}/posts`, {
  transform: (items) => items.map((item) => ({
    ...item,
    coverImage: mediaUrl(item.cover_image),
  })),
})

const filteredPosts = computed(() => {
  const query = searchQuery.value.trim().toLowerCase()
  const items = posts.value || []

  if (!query) return items

  return items.filter((post) =>
    post.title.toLowerCase().includes(query)
    || post.excerpt.toLowerCase().includes(query)
    || post.content.toLowerCase().includes(query)
  )
})

const formatPublishedAt = (value: string | null) => {
  if (!value) return 'Cerita Baru'

  return new Intl.DateTimeFormat('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
  }).format(new Date(value))
}

useSeoMeta({
  title: "Cerita TI'23 ITTS",
  description: 'Baca kumpulan cerita, nostalgia, dan catatan seru dari perjalanan TI’23 ITTS.',
  ogTitle: "Cerita TI'23 ITTS",
  ogDescription: 'Baca kumpulan cerita, nostalgia, dan catatan seru dari perjalanan TI’23 ITTS.',
  ogUrl: `${config.public.siteUrl}/cerita`,
  ogImage: `${config.public.siteUrl}/social-preview.svg`,
  twitterTitle: "Cerita TI'23 ITTS",
  twitterDescription: 'Baca kumpulan cerita, nostalgia, dan catatan seru dari perjalanan TI’23 ITTS.',
  twitterImage: `${config.public.siteUrl}/social-preview.svg`,
})
</script>
