<template>
  <div class="min-h-screen bg-gray-900 px-4 py-12 text-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
      <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-y-3 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-3 opacity-0"
      >
        <div
          v-if="feedbackMessage"
          class="fixed bottom-6 right-6 z-[10000] w-[calc(100vw-2rem)] max-w-md border-4 border-gray-900 bg-white p-5 text-gray-900 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]"
        >
          <div class="flex items-start justify-between gap-4">
            <div>
              <p class="inline-block -rotate-1 border-2 border-gray-900 bg-green-500 px-3 py-1 text-xs font-black uppercase tracking-[0.2em]">
                Info
              </p>
              <p class="mt-4 whitespace-pre-line text-sm font-black leading-relaxed sm:text-base">{{ feedbackMessage }}</p>
            </div>
            <button type="button" class="flex h-10 w-10 shrink-0 items-center justify-center border-2 border-gray-900 bg-gray-900 font-black text-white transition-colors hover:bg-red-500" @click="clearFeedback">
              ×
            </button>
          </div>
        </div>
      </Transition>

      <NuxtLink
        to="/#episodes"
        class="inline-flex items-center gap-2 border-4 border-white bg-white px-5 py-3 font-black uppercase text-gray-900 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1"
      >
        ← Kembali ke Hall of Shame
      </NuxtLink>

      <div v-if="pending" class="mt-10 border-4 border-white bg-gray-800 p-10 text-center font-black uppercase text-gray-300 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]">
        Memuat episode...
      </div>

      <div v-else-if="episode" class="mt-10 space-y-8">
        <section class="overflow-hidden border-4 border-white bg-gray-900 shadow-[12px_12px_0px_0px_rgba(34,197,94,1)]">
          <div class="relative bg-gray-800 border-b-4 border-white">
            <img
              v-if="episode.mediaType === 'image'"
              :src="episode.image"
              :alt="episode.title"
              fetchpriority="high"
              decoding="async"
              class="aspect-video w-full object-cover"
            >
            <video
              v-else
              :src="episode.videoUrl || undefined"
              class="aspect-video w-full object-cover"
              controls
              playsinline
              preload="metadata"
            />
          </div>

          <div class="p-6 sm:p-8">
            <div class="mb-6 flex flex-wrap gap-3">
              <span class="inline-block -rotate-2 border-2 border-gray-900 bg-green-500 px-4 py-2 font-black uppercase text-gray-900 shadow-[4px_4px_0px_0px_rgba(255,255,255,1)]">
                {{ episode.category }}
              </span>
              <span class="inline-block border-2 border-white bg-gray-800 px-4 py-2 font-black uppercase text-white">
                {{ episode.mediaType === 'video' ? 'Video Episode' : 'Image Episode' }}
              </span>
              <span v-if="episode.isTopBanyakDicari" class="inline-block -rotate-2 border-2 border-yellow-300 bg-yellow-300 px-4 py-2 font-black uppercase text-gray-900">
                #BanyakDicari
              </span>
            </div>

            <h1 class="mb-6 text-3xl font-black uppercase tracking-tight text-white md:text-5xl">
              {{ episode.title }}
            </h1>

            <div class="mb-8 border-2 border-gray-700 bg-gray-800 p-6">
              <p class="border-l-4 border-green-500 pl-3 text-sm font-bold text-gray-400">
                {{ episode.shortDescription }}
              </p>
              <p class="mt-5 whitespace-pre-line text-lg font-bold leading-relaxed text-gray-300">
                {{ episode.fullDescription }}
              </p>
            </div>

            <div class="flex flex-wrap gap-4 text-sm font-black">
              <div class="border-2 border-gray-900 bg-white px-4 py-2 text-gray-900 shadow-[3px_3px_0px_0px_rgba(34,197,94,1)]">
                📅 {{ episode.date }}
              </div>
              <div class="border-2 border-gray-900 bg-white px-4 py-2 text-gray-900 shadow-[3px_3px_0px_0px_rgba(34,197,94,1)]">
                👥 {{ episode.participants }}
              </div>
              <div class="border-2 border-gray-900 bg-white px-4 py-2 text-gray-900 shadow-[3px_3px_0px_0px_rgba(34,197,94,1)]">
                ❤️ {{ episode.likesCount }} Likes · 💬 {{ episode.commentsCount }} Komentar
              </div>
              <button
                type="button"
                class="border-2 border-gray-900 bg-green-500 px-4 py-2 text-gray-900 shadow-[3px_3px_0px_0px_rgba(255,255,255,1)] transition-all hover:-translate-y-1"
                @click="shareEpisode"
              >
                Share Episode
              </button>
              <button
                type="button"
                class="border-2 border-white bg-gray-900 px-4 py-2 text-white shadow-[3px_3px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1"
                @click="copyEpisodeLink"
              >
                Copy Link
              </button>
            </div>
          </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[0.8fr_1.2fr]">
          <div class="border-4 border-white bg-white p-5 text-gray-900 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
            <p class="text-sm font-black uppercase tracking-[0.2em] text-gray-500">Dukung Episode Ini</p>
            <div class="mt-5 border-2 border-gray-900 bg-gray-50 p-4">
              <template v-if="savedNim">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-gray-500">Identitas Aktif</p>
                <p class="mt-2 text-lg font-black text-gray-900">{{ savedNim }}</p>
                <p class="mt-1 text-sm font-bold text-gray-600">NIM ini bakal dipakai buat like dan komentar sampai kamu ganti.</p>
                <button type="button" class="mt-4 border-2 border-gray-900 bg-white px-4 py-2 text-xs font-black uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all hover:-translate-y-1 hover:bg-green-500" @click="resetSavedNim">
                  Ganti NIM
                </button>
              </template>

              <form v-else class="space-y-3" @submit.prevent="saveNimIdentity">
                <label class="block">
                  <span class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-gray-500">Isi NIM Kamu Dulu</span>
                  <input v-model="nimDraft" type="text" class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500" placeholder="1002230xxx">
                </label>
                <button type="submit" class="w-full border-4 border-gray-900 bg-gray-900 px-4 py-3 font-black uppercase text-white shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 hover:bg-green-500 hover:text-gray-900">
                  Simpan NIM
                </button>
              </form>
            </div>

            <form class="mt-5 space-y-4" @submit.prevent="submitLike">
              <button type="submit" :disabled="!savedNim || likeLoading" class="w-full border-4 border-gray-900 bg-gray-900 px-4 py-3 font-black uppercase text-white shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 hover:bg-green-500 hover:text-gray-900 disabled:cursor-not-allowed disabled:opacity-50">
                {{ likeLoading ? 'Menyimpan Like...' : 'Like Sekarang' }}
              </button>
              <p class="text-xs font-bold text-gray-500">1 NIM cuma bisa like 1 kali per episode.</p>
            </form>
          </div>

          <div class="border-4 border-white bg-gray-800 p-5 shadow-[6px_6px_0px_0px_rgba(255,255,255,0.15)]">
            <p class="text-sm font-black uppercase tracking-[0.2em] text-gray-300">Komentar Teman-teman</p>
            <form class="mt-5 space-y-4 border-b-2 border-gray-700 pb-5" @submit.prevent="submitComment">
              <label class="block">
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-gray-400">
                  {{ editingCommentId ? 'Edit Komentar' : 'Komentar' }}
                </span>
                <textarea v-model="commentForm.comment" rows="4" class="w-full resize-none border-2 border-white bg-gray-900 px-4 py-3 font-bold text-white outline-none focus:border-green-500" :placeholder="editingCommentId ? 'Perbarui komentar kamu...' : 'Tulis pesan kamu buat episode ini...'" />
              </label>
              <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" :disabled="!savedNim || commentLoading" class="w-full border-4 border-white bg-white px-4 py-3 font-black uppercase text-gray-900 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 hover:bg-green-500 disabled:cursor-not-allowed disabled:opacity-50">
                  {{ commentLoading ? (editingCommentId ? 'Menyimpan Edit...' : 'Mengirim Komentar...') : (editingCommentId ? 'Simpan Perubahan' : 'Kirim Komentar') }}
                </button>
                <button v-if="editingCommentId" type="button" class="w-full border-4 border-gray-700 bg-gray-900 px-4 py-3 font-black uppercase text-white transition-all hover:-translate-y-1 hover:border-white" @click="cancelCommentEdit">
                  Batal Edit
                </button>
              </div>
            </form>

            <div class="mt-5 space-y-4">
              <article v-for="comment in episode.comments" :key="comment.id" class="border-2 border-gray-700 bg-gray-900 p-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                  <div>
                    <p class="font-black uppercase text-green-400">{{ comment.student_name }}</p>
                    <p v-if="comment.updated_at && comment.updated_at !== comment.created_at" class="mt-1 text-[10px] font-black uppercase tracking-[0.2em] text-yellow-300">
                      Sudah diedit
                    </p>
                  </div>
                  <p class="text-xs font-black uppercase tracking-[0.2em] text-gray-500">{{ formatCommentTime(comment.updated_at || comment.created_at) }}</p>
                </div>
                <p class="mt-3 text-sm font-bold leading-relaxed text-gray-200">{{ comment.comment }}</p>
                <div v-if="isOwnComment(comment)" class="mt-4 flex flex-wrap gap-3">
                  <button type="button" class="border-2 border-white bg-white px-3 py-2 text-xs font-black uppercase text-gray-900 shadow-[2px_2px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 hover:bg-green-500" @click="startCommentEdit(comment)">
                    Edit
                  </button>
                  <button type="button" :disabled="commentLoading" class="border-2 border-red-400 bg-transparent px-3 py-2 text-xs font-black uppercase text-red-300 transition-all hover:-translate-y-1 hover:bg-red-500 hover:text-white disabled:cursor-not-allowed disabled:opacity-50" @click="deleteComment(comment)">
                    Hapus
                  </button>
                </div>
              </article>

              <div v-if="!episode.comments.length" class="border-2 border-dashed border-gray-700 bg-gray-900 p-5 text-center font-bold text-gray-400">
                Belum ada komentar. Kamu bisa jadi yang pertama.
              </div>
            </div>
          </div>
        </section>
      </div>

      <div v-else class="mt-10 border-4 border-red-500 bg-white p-10 text-center text-gray-900 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]">
        <p class="text-3xl font-black uppercase">Episode tidak ditemukan</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
type EpisodeComment = {
  id: number
  student_name: string
  student_nim: string
  comment: string
  created_at: string
  updated_at: string
}

type EpisodeDetail = {
  id: number
  category: string
  title: string
  image: string | null
  media_type: 'image' | 'video'
  media_source: 'url' | 'upload'
  video_url: string | null
  short_description: string
  full_description: string
  date: string
  participants: string
  likes_count: number
  comments_count: number
  comments: EpisodeComment[]
  is_top_banyak_dicari: boolean
  mediaType: 'image' | 'video'
  videoUrl: string | null
  likesCount: number
  commentsCount: number
  shortDescription: string
  fullDescription: string
  isTopBanyakDicari: boolean
}

const route = useRoute()
const config = useRuntimeConfig()
const { mediaUrl } = useApiMedia()
const NIM_STORAGE_KEY = 'ti23itts-episode-nim'
const savedNim = ref('')
const nimDraft = ref('')
const commentForm = ref({ comment: '' })
const editingCommentId = ref<number | null>(null)
const likeLoading = ref(false)
const commentLoading = ref(false)
const feedbackMessage = ref('')
let feedbackTimeout: number | null = null

const normalizeEpisode = (episode: any): EpisodeDetail => ({
  ...episode,
  mediaType: episode.media_type || 'image',
  image: mediaUrl(episode.image || null),
  videoUrl: episode.video_url ? mediaUrl(episode.video_url) : null,
  likesCount: episode.likes_count || 0,
  commentsCount: episode.comments_count || 0,
  comments: episode.comments || [],
  isTopBanyakDicari: Boolean(episode.is_top_banyak_dicari),
  shortDescription: episode.short_description,
  fullDescription: episode.full_description,
})

const { data: episode, pending, refresh } = await useFetch<EpisodeDetail>(`${config.public.apiBase}/episodes/${route.params.id}/public`, {
  transform: normalizeEpisode,
})

const episodeTitle = computed(() => episode.value ? `${episode.value.title} - Episode TI'23 ITTS` : "Episode TI'23 ITTS")
const episodeDescription = computed(() => {
  if (!episode.value) return 'Lihat episode seru TI’23 ITTS lengkap dengan like, komentar, dan inside joke angkatan.'
  return episode.value.shortDescription || episode.value.fullDescription || `Baca cerita ${episode.value.title} di TI'23 ITTS.`
})
const episodeImage = computed(() => {
  if (!episode.value) return `${config.public.siteUrl}/social-preview.svg`
  return episode.value.mediaType === 'image' && episode.value.image ? episode.value.image : `${config.public.siteUrl}/social-preview.svg`
})
const episodeUrl = computed(() => `${config.public.siteUrl}/episodes/${route.params.id}`)

useSeoMeta({
  title: episodeTitle,
  description: episodeDescription,
  ogTitle: episodeTitle,
  ogDescription: episodeDescription,
  ogType: 'article',
  ogUrl: episodeUrl,
  ogImage: episodeImage,
  ogImageAlt: episodeTitle,
  twitterCard: 'summary_large_image',
  twitterTitle: episodeTitle,
  twitterDescription: episodeDescription,
  twitterImage: episodeImage,
})

useHead({
  link: [
    { rel: 'canonical', href: episodeUrl },
  ],
  script: [
    {
      type: 'application/ld+json',
      innerHTML: computed(() => JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'Article',
        headline: episode.value?.title || "Episode TI'23 ITTS",
        description: episodeDescription.value,
        image: episodeImage.value,
        datePublished: episode.value?.date || undefined,
        mainEntityOfPage: episodeUrl.value,
        publisher: {
          '@type': 'Organization',
          name: "TI'23 ITTS",
          url: config.public.siteUrl,
        },
        inLanguage: 'id-ID',
      })),
    },
  ],
})

const showFeedback = (message: string) => {
  feedbackMessage.value = message
  if (feedbackTimeout) clearTimeout(feedbackTimeout)
  if (import.meta.client) {
    feedbackTimeout = window.setTimeout(() => {
      feedbackMessage.value = ''
      feedbackTimeout = null
    }, 3200)
  }
}

const clearFeedback = () => {
  feedbackMessage.value = ''
  if (feedbackTimeout) {
    clearTimeout(feedbackTimeout)
    feedbackTimeout = null
  }
}

const hydrateSavedNim = () => {
  if (!import.meta.client) return
  savedNim.value = localStorage.getItem(NIM_STORAGE_KEY) || ''
  nimDraft.value = savedNim.value
}

const saveNimIdentity = () => {
  const normalizedNim = nimDraft.value.trim()
  if (!normalizedNim) {
    showFeedback('Isi NIM kamu dulu ya.')
    return
  }

  savedNim.value = normalizedNim
  if (import.meta.client) {
    localStorage.setItem(NIM_STORAGE_KEY, normalizedNim)
  }
  showFeedback('NIM berhasil disimpan di browser ini.')
}

const copyEpisodeLink = async () => {
  try {
    if (import.meta.client && navigator.clipboard) {
      await navigator.clipboard.writeText(episodeUrl.value)
      showFeedback('Link episode berhasil disalin.')
      return
    }

    showFeedback('Browser ini belum mendukung copy otomatis.')
  } catch (error) {
    showFeedback('Gagal menyalin link episode.')
  }
}

const shareEpisode = async () => {
  try {
    if (import.meta.client && navigator.share) {
      await navigator.share({
        title: episodeTitle.value,
        text: episodeDescription.value,
        url: episodeUrl.value,
      })
      showFeedback('Episode siap dishare.')
      return
    }

    await copyEpisodeLink()
  } catch (error: any) {
    if (error?.name === 'AbortError') {
      return
    }

    await copyEpisodeLink()
  }
}

const resetSavedNim = () => {
  savedNim.value = ''
  nimDraft.value = ''
  editingCommentId.value = null
  commentForm.value = { comment: '' }
  if (import.meta.client) {
    localStorage.removeItem(NIM_STORAGE_KEY)
  }
}

const formatCommentTime = (date: string) => new Intl.DateTimeFormat('id-ID', {
  day: '2-digit',
  month: 'short',
  year: 'numeric',
  hour: '2-digit',
  minute: '2-digit',
}).format(new Date(date))

const isOwnComment = (comment: EpisodeComment) => Boolean(savedNim.value) && comment.student_nim === savedNim.value

const startCommentEdit = (comment: EpisodeComment) => {
  editingCommentId.value = comment.id
  commentForm.value = { comment: comment.comment }
}

const cancelCommentEdit = () => {
  editingCommentId.value = null
  commentForm.value = { comment: '' }
}

const submitLike = async () => {
  if (!episode.value) return
  if (!savedNim.value) {
    showFeedback('Simpan NIM kamu dulu sebelum like ya.')
    return
  }

  likeLoading.value = true
  try {
    const response = await $fetch(`${config.public.apiBase}/episodes/${episode.value.id}/like`, {
      method: 'POST',
      body: { nim: savedNim.value },
    })
    await refresh()
    showFeedback(response.message || 'Like berhasil disimpan.')
  } catch (error: any) {
    showFeedback(error?.data?.errors ? Object.values(error.data.errors).flat().join('\n') : (error?.data?.message || error?.message || 'Gagal menyimpan like.'))
  } finally {
    likeLoading.value = false
  }
}

const submitComment = async () => {
  if (!episode.value) return
  if (!savedNim.value) {
    showFeedback('Simpan NIM kamu dulu sebelum komentar ya.')
    return
  }

  commentLoading.value = true
  try {
    const isEditing = Boolean(editingCommentId.value)
    const response = await $fetch(
      isEditing
        ? `${config.public.apiBase}/episodes/${episode.value.id}/comment/${editingCommentId.value}`
        : `${config.public.apiBase}/episodes/${episode.value.id}/comment`,
      {
        method: isEditing ? 'PUT' : 'POST',
        body: {
          nim: savedNim.value,
          comment: commentForm.value.comment,
        },
      },
    )
    await refresh()
    commentForm.value = { comment: '' }
    editingCommentId.value = null
    showFeedback(response.message || 'Komentar berhasil dikirim.')
  } catch (error: any) {
    showFeedback(error?.data?.errors ? Object.values(error.data.errors).flat().join('\n') : (error?.data?.message || error?.message || 'Gagal mengirim komentar.'))
  } finally {
    commentLoading.value = false
  }
}

const deleteComment = async (comment: EpisodeComment) => {
  if (!episode.value) return
  if (!savedNim.value) {
    showFeedback('Simpan NIM kamu dulu sebelum hapus komentar ya.')
    return
  }

  commentLoading.value = true
  try {
    const response = await $fetch(`${config.public.apiBase}/episodes/${episode.value.id}/comment/${comment.id}`, {
      method: 'DELETE',
      body: { nim: savedNim.value },
    })
    await refresh()
    if (editingCommentId.value === comment.id) {
      cancelCommentEdit()
    }
    showFeedback(response.message || 'Komentar berhasil dihapus.')
  } catch (error: any) {
    showFeedback(error?.data?.errors ? Object.values(error.data.errors).flat().join('\n') : (error?.data?.message || error?.message || 'Gagal menghapus komentar.'))
  } finally {
    commentLoading.value = false
  }
}

onMounted(() => {
  hydrateSavedNim()
})

onUnmounted(() => {
  if (feedbackTimeout) {
    clearTimeout(feedbackTimeout)
    feedbackTimeout = null
  }
})
</script>
