<template>
  <section id="episodes" class="border-y-4 border-white bg-gray-800 py-24 text-white">
    <div class="container mx-auto px-4">
      <div v-reveal="'up'" class="mb-16 flex flex-col items-center text-center">
        <h2 class="mb-6 flex flex-col items-center gap-2 font-display text-5xl font-black md:text-7xl">
          <span class="inline-block rotate-2 bg-green-500 px-6 py-1 text-gray-900 shadow-[6px_6px_0px_0px_rgba(255,255,255,1)]">
            HALL OF
          </span>
          <span class="inline-block -rotate-2 bg-white px-6 py-1 text-gray-900 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
            SHAME
          </span>
        </h2>
        <span class="inline-block rotate-1 border-2 border-green-500 bg-gray-900 px-4 py-2 font-bold text-white shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
          Aib dan inside jokes legendaris angkatan
        </span>
      </div>

      <div class="relative mx-auto max-w-7xl">
        <div class="scrollbar-hide overflow-x-auto pb-12 pt-4">
          <div class="flex gap-6 px-2 sm:gap-8 sm:px-4">
            <div
              v-for="episode in episodes"
              :key="episode.id"
              v-reveal="'up'"
              class="group w-[85vw] max-w-80 flex-shrink-0 cursor-pointer sm:w-80"
              @click="openModal(episode)"
            >
              <div class="border-4 border-white bg-gray-900 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)] transition-all duration-300 group-hover:-translate-y-3 group-hover:shadow-[12px_12px_0px_0px_rgba(255,255,255,1)]">
                <div class="relative aspect-video overflow-hidden border-b-4 border-white bg-gray-800">
                  <img
                    v-if="episode.mediaType === 'image'"
                    :src="episode.image"
                    :alt="episode.title"
                    class="h-full w-full object-cover opacity-60 transition-all duration-500 group-hover:scale-110 group-hover:opacity-100 group-hover:grayscale-0 grayscale"
                  >
                  <video
                    v-else
                    :src="episode.videoUrl || undefined"
                    class="h-full w-full object-cover opacity-75 transition-all duration-500 group-hover:scale-105 group-hover:opacity-100"
                    muted
                    playsinline
                    preload="metadata"
                    autoplay
                    loop
                  />

                  <div class="absolute left-3 top-3 flex flex-wrap gap-2">
                    <span class="inline-block -rotate-3 border-2 border-gray-900 bg-green-500 px-3 py-1 text-xs font-black uppercase tracking-wider text-gray-900 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                      {{ episode.category }}
                    </span>
                    <span v-if="episode.isTopBanyakDicari" class="inline-block -rotate-2 border-2 border-yellow-300 bg-yellow-300 px-3 py-1 text-xs font-black uppercase tracking-wider text-gray-900">
                      #BanyakDicari
                    </span>
                    <span class="inline-block border-2 border-white bg-gray-900 px-3 py-1 text-xs font-black uppercase tracking-wider text-white">
                      {{ episode.mediaType === 'video' ? 'Video' : 'Image' }}
                    </span>
                  </div>

                  <div class="absolute inset-0 flex items-center justify-center bg-gray-900/40 opacity-0 transition-opacity duration-300 backdrop-blur-sm group-hover:opacity-100">
                    <div class="flex h-16 w-16 items-center justify-center border-4 border-gray-900 bg-white text-gray-900 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transform rotate-6">
                      <svg v-if="episode.mediaType === 'video'" class="ml-1 h-8 w-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                      </svg>
                      <svg v-else class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                      </svg>
                    </div>
                  </div>
                </div>

                <div class="bg-gray-900 p-5">
                  <h3 class="mb-3 text-2xl font-black leading-tight text-white transition-colors group-hover:text-green-400">
                    {{ episode.title }}
                  </h3>
                  <p class="border-l-4 border-green-500 pl-3 text-sm font-bold text-gray-400">
                    {{ episode.shortDescription }}
                  </p>
                  <div class="mt-4 flex flex-wrap gap-3 text-xs font-black uppercase tracking-[0.2em] text-gray-300">
                    <span>❤️ {{ episode.likesCount }} Likes</span>
                    <span>💬 {{ episode.commentsCount }} Komentar</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <Teleport to="body">
        <div
          v-if="selectedEpisode"
          class="fixed inset-0 z-[9999] min-h-screen overflow-y-auto bg-gray-900/95 px-4 py-8 backdrop-blur-md"
          @click="closeModal"
        >
          <div class="mx-auto w-full max-w-4xl border-4 border-white bg-gray-900 shadow-[16px_16px_0px_0px_rgba(34,197,94,1)]" @click.stop>
            <div class="relative bg-gray-800 border-b-4 border-white">
              <img
                v-if="selectedEpisode.mediaType === 'image'"
                :src="selectedEpisode.image"
                :alt="selectedEpisode.title"
                class="aspect-video w-full object-cover"
              >
              <video
                v-else
                :src="selectedEpisode.videoUrl || undefined"
                class="aspect-video w-full object-cover"
                controls
                playsinline
                preload="metadata"
              />
              <button
                type="button"
                class="absolute right-4 top-4 flex h-12 w-12 items-center justify-center border-4 border-gray-900 bg-white font-black text-gray-900 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-colors hover:bg-green-500"
                @click="closeModal"
              >
                X
              </button>
            </div>

            <div class="p-6 sm:p-8">
              <div class="mb-6 flex flex-wrap gap-3">
                <span class="inline-block -rotate-2 border-2 border-gray-900 bg-green-500 px-4 py-2 font-black uppercase text-gray-900 shadow-[4px_4px_0px_0px_rgba(255,255,255,1)]">
                  {{ selectedEpisode.category }}
                </span>
                <span class="inline-block border-2 border-white bg-gray-800 px-4 py-2 font-black uppercase text-white">
                  {{ selectedEpisode.mediaType === 'video' ? 'Video Episode' : 'Image Episode' }}
                </span>
                <span v-if="selectedEpisode.isTopBanyakDicari" class="inline-block -rotate-2 border-2 border-yellow-300 bg-yellow-300 px-4 py-2 font-black uppercase text-gray-900">
                  #BanyakDicari
                </span>
              </div>

              <h2 class="mb-6 text-3xl font-black uppercase tracking-tight text-white md:text-5xl">
                {{ selectedEpisode.title }}
              </h2>

              <div class="mb-8 border-2 border-gray-700 bg-gray-800 p-6">
                <p class="whitespace-pre-line text-lg font-bold leading-relaxed text-gray-300">
                  {{ selectedEpisode.fullDescription }}
                </p>
              </div>

              <div class="flex flex-wrap gap-4 text-sm font-black">
                <div class="border-2 border-gray-900 bg-white px-4 py-2 text-gray-900 shadow-[3px_3px_0px_0px_rgba(34,197,94,1)]">
                  📅 {{ selectedEpisode.date }}
                </div>
                <div class="border-2 border-gray-900 bg-white px-4 py-2 text-gray-900 shadow-[3px_3px_0px_0px_rgba(34,197,94,1)]">
                  👥 {{ selectedEpisode.participants }}
                </div>
              </div>

              <div class="mt-8 grid gap-6 lg:grid-cols-[0.8fr_1.2fr]">
                <div class="border-4 border-white bg-white p-5 text-gray-900 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
                  <p class="text-sm font-black uppercase tracking-[0.2em] text-gray-500">Dukung Episode Ini</p>
                  <label class="mt-5 block">
                    <span class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-gray-500">Masukkan NIM Sekali Aja</span>
                    <input v-model="engagementNim" type="text" class="w-full border-2 border-gray-900 px-4 py-3 font-bold outline-none focus:border-green-500" placeholder="1002230xxx">
                  </label>
                  <div class="mt-4 flex gap-3 text-sm font-black uppercase">
                    <span>❤️ {{ selectedEpisode.likesCount }} Likes</span>
                    <span>💬 {{ selectedEpisode.commentsCount }} Komentar</span>
                  </div>
                  <form class="mt-5 space-y-4" @submit.prevent="submitLike">
                    <button type="submit" class="w-full border-4 border-gray-900 bg-gray-900 px-4 py-3 font-black uppercase text-white shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 hover:bg-green-500 hover:text-gray-900">
                      {{ likeLoading ? 'Menyimpan Like...' : 'Like Sekarang' }}
                    </button>
                    <p class="text-xs font-bold text-gray-500">1 NIM cuma bisa like 1 kali per episode.</p>
                  </form>
                </div>

                <div class="border-4 border-white bg-gray-800 p-5 shadow-[6px_6px_0px_0px_rgba(255,255,255,0.15)]">
                  <p class="text-sm font-black uppercase tracking-[0.2em] text-gray-300">Komentar Teman-teman</p>
                  <form class="mt-5 space-y-4 border-b-2 border-gray-700 pb-5" @submit.prevent="submitComment">
                    <label class="block">
                      <span class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-gray-400">Komentar</span>
                      <textarea v-model="commentForm.comment" rows="4" class="w-full resize-none border-2 border-white bg-gray-900 px-4 py-3 font-bold text-white outline-none focus:border-green-500" placeholder="Tulis pesan kamu buat episode ini..."></textarea>
                    </label>
                    <button type="submit" class="w-full border-4 border-white bg-white px-4 py-3 font-black uppercase text-gray-900 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 hover:bg-green-500">
                      {{ commentLoading ? 'Mengirim Komentar...' : 'Kirim Komentar' }}
                    </button>
                    <p class="text-xs font-bold text-gray-400">1 NIM cuma bisa komentar 1 kali per episode.</p>
                  </form>

                  <div class="mt-5 space-y-4">
                    <article v-for="comment in selectedEpisode.comments" :key="comment.id" class="border-2 border-gray-700 bg-gray-900 p-4">
                      <div class="flex flex-wrap items-center justify-between gap-3">
                        <p class="font-black uppercase text-green-400">{{ comment.student_name }} • {{ comment.student_nim }}</p>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-gray-500">{{ formatCommentTime(comment.created_at) }}</p>
                      </div>
                      <p class="mt-3 text-sm font-bold leading-relaxed text-gray-200">{{ comment.comment }}</p>
                    </article>

                    <div v-if="!selectedEpisode.comments.length" class="border-2 border-dashed border-gray-700 bg-gray-900 p-5 text-center font-bold text-gray-400">
                      Belum ada komentar. Kamu bisa jadi yang pertama.
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </Teleport>
    </div>
  </section>
</template>

<script setup>
import { onMounted, onUnmounted, ref, watch } from 'vue'

const config = useRuntimeConfig()
const { mediaUrl } = useApiMedia()
const episodes = ref([])
const selectedEpisode = ref(null)
const engagementNim = ref('')
const commentForm = ref({ comment: '' })
const likeLoading = ref(false)
const commentLoading = ref(false)
const feedbackMessage = ref('')

const normalizeEpisode = (episode) => ({
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

const openModal = (episode) => {
  selectedEpisode.value = episode
  engagementNim.value = ''
  commentForm.value = { comment: '' }
  feedbackMessage.value = ''
}

const closeModal = () => {
  selectedEpisode.value = null
}

const formatCommentTime = (date) => new Intl.DateTimeFormat('id-ID', {
  day: '2-digit',
  month: 'short',
  year: 'numeric',
  hour: '2-digit',
  minute: '2-digit',
}).format(new Date(date))

const applyEpisodeUpdate = async () => {
  const data = await $fetch(`${config.public.apiBase}/episodes`)
  episodes.value = data.map(normalizeEpisode)

  if (selectedEpisode.value) {
    selectedEpisode.value = episodes.value.find(item => item.id === selectedEpisode.value.id) || null
  }
}

const showFeedback = (message) => {
  feedbackMessage.value = message
  if (import.meta.client) {
    window.alert(message)
  }
}

const submitLike = async () => {
  if (!selectedEpisode.value) return

  likeLoading.value = true
  try {
    const response = await $fetch(`${config.public.apiBase}/episodes/${selectedEpisode.value.id}/like`, {
      method: 'POST',
      body: { nim: engagementNim.value },
    })
    await applyEpisodeUpdate()
    showFeedback(response.message || 'Like berhasil disimpan.')
  } catch (error) {
    showFeedback(error?.data?.errors ? Object.values(error.data.errors).flat().join('\n') : (error?.data?.message || error?.message || 'Gagal menyimpan like.'))
  } finally {
    likeLoading.value = false
  }
}

const submitComment = async () => {
  if (!selectedEpisode.value) return

  commentLoading.value = true
  try {
    const response = await $fetch(`${config.public.apiBase}/episodes/${selectedEpisode.value.id}/comment`, {
      method: 'POST',
      body: {
        nim: engagementNim.value,
        comment: commentForm.value.comment,
      },
    })
    await applyEpisodeUpdate()
    commentForm.value = { comment: '' }
    showFeedback(response.message || 'Komentar berhasil dikirim.')
  } catch (error) {
    showFeedback(error?.data?.errors ? Object.values(error.data.errors).flat().join('\n') : (error?.data?.message || error?.message || 'Gagal mengirim komentar.'))
  } finally {
    commentLoading.value = false
  }
}

watch(selectedEpisode, (episode) => {
  if (!import.meta.client) return
  document.body.style.overflow = episode ? 'hidden' : ''
})

onMounted(async () => {
  try {
    await applyEpisodeUpdate()
  } catch (error) {
    console.error('Gagal memuat episode dari database', error)
  }
})

onUnmounted(() => {
  if (import.meta.client) {
    document.body.style.overflow = ''
  }
})
</script>

<style scoped>
.font-display {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
