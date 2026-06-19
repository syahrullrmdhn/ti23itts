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
        to="/#profil"
        class="inline-flex items-center gap-2 border-4 border-white bg-white px-5 py-3 font-black uppercase text-gray-900 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1"
      >
        ← Kembali ke Cast & Crew
      </NuxtLink>

      <div v-if="pending" class="mt-10 border-4 border-white bg-gray-800 p-10 text-center font-black uppercase text-gray-300 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]">
        Memuat profil crew...
      </div>

      <div v-else-if="student" class="mt-10 space-y-8">
        <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
          <section v-reveal="'left'" class="overflow-hidden border-4 border-white bg-gray-800 shadow-[10px_10px_0px_0px_rgba(34,197,94,1)]">
            <div class="relative aspect-square overflow-hidden border-b-4 border-white bg-gray-900">
              <img :src="activePhoto" :alt="student.name" class="h-full w-full object-cover">
              <span class="absolute right-4 top-4 inline-block rotate-3 border-2 border-gray-900 bg-green-500 px-3 py-1 text-xs font-black uppercase tracking-wider text-gray-900 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
                {{ student.status }}
              </span>
            </div>
            <div class="grid grid-cols-2 border-t-4 border-white">
              <button type="button" class="border-r-2 border-white px-4 py-4 text-sm font-black uppercase tracking-[0.2em] transition-colors" :class="showAltPhoto ? 'bg-gray-700 text-gray-300' : 'bg-white text-gray-900'" @click="showAltPhoto = false">
                Foto Utama
              </button>
              <button type="button" class="px-4 py-4 text-sm font-black uppercase tracking-[0.2em] transition-colors" :class="showAltPhoto ? 'bg-green-500 text-gray-900' : 'bg-gray-900 text-green-400'" @click="showAltPhoto = true">
                Mode Aib
              </button>
            </div>
          </section>

          <section class="space-y-6">
            <div v-reveal="'up'" class="border-4 border-white bg-white p-6 text-gray-900 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)] sm:p-8">
              <p class="text-sm font-black uppercase tracking-[0.25em] text-gray-500">Crew Profile</p>
              <h1 class="mt-4 text-4xl font-black uppercase tracking-tight sm:text-5xl">{{ student.name }}</h1>
              <div class="mt-4 inline-block -rotate-1 border-2 border-gray-900 bg-green-500 px-4 py-2 text-sm font-black uppercase tracking-wider">
                {{ student.role }}
              </div>
              <p class="mt-6 text-lg font-bold leading-relaxed text-gray-700">
                {{ student.fun_fact || 'Setiap orang punya cerita seru sendiri, dan crew ini jelas bagian penting dari perjalanan TI23.' }}
              </p>
            </div>

            <div v-reveal="'up'" class="border-4 border-white bg-gray-800 p-6 shadow-[8px_8px_0px_0px_rgba(255,255,255,0.15)] sm:p-8">
              <p class="inline-block -rotate-1 border-2 border-green-500 bg-gray-900 px-3 py-1 text-xs font-black uppercase tracking-[0.25em] text-green-400">
                Pesan ke Teman-teman
              </p>
              <blockquote class="mt-5 border-l-4 border-green-500 bg-gray-900/70 p-5 text-lg font-bold leading-relaxed text-gray-200">
                “{{ student.message || 'Terima kasih sudah tumbuh bareng. Semoga setelah semua drama, tugas, dan aib yang lewat, kita tetap saling dukung sampai kapan pun.' }}”
              </blockquote>
            </div>

            <div v-reveal="'right'" class="grid gap-4 sm:grid-cols-2">
              <div class="border-4 border-white bg-gray-800 p-5 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
                <p class="text-xs font-black uppercase tracking-[0.25em] text-gray-400">Status</p>
                <p class="mt-3 text-2xl font-black uppercase">{{ student.status }}</p>
              </div>
              <div class="border-4 border-white bg-gray-800 p-5 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
                <p class="text-xs font-black uppercase tracking-[0.25em] text-gray-400">Angkatan</p>
                <p class="mt-3 text-2xl font-black uppercase">TI 2023</p>
              </div>
            </div>
          </section>
        </div>

        <section class="grid gap-8 xl:grid-cols-[0.9fr_1.1fr]">
          <div v-reveal="'up'" class="border-4 border-white bg-white p-6 text-gray-900 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)] sm:p-8">
            <p class="text-sm font-black uppercase tracking-[0.25em] text-gray-500">Identitas & Voting Badge</p>
            <div class="mt-5 border-2 border-gray-900 bg-gray-50 p-4">
              <template v-if="savedNim">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-gray-500">NIM Aktif di Browser Ini</p>
                <p class="mt-2 text-lg font-black text-gray-900">{{ savedNim }}</p>
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

            <div class="mt-6">
              <p class="text-sm font-black uppercase tracking-[0.2em] text-gray-600">Badge Lucu Buat {{ student.name }}</p>
              <div class="mt-4 grid gap-3 sm:grid-cols-2">
                <button
                  v-for="badge in voteSummary"
                  :key="badge.key"
                  type="button"
                  :disabled="voteLoading || !savedNim"
                  class="group border-4 border-gray-900 bg-gray-900 p-4 text-left text-white shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 hover:bg-green-500 hover:text-gray-900 disabled:cursor-not-allowed disabled:opacity-50"
                  @click="submitVote(badge.key)"
                >
                  <p class="text-lg font-black uppercase">{{ badge.label }}</p>
                  <p class="mt-2 text-xs font-black uppercase tracking-[0.2em] opacity-80">{{ badge.count }} vote</p>
                </button>
              </div>
              <p class="mt-4 text-xs font-bold text-gray-500">1 NIM bisa vote 1 kali per badge untuk mahasiswa ini.</p>
            </div>

            <div class="mt-6 border-2 border-gray-900 bg-yellow-300 p-4 shadow-[4px_4px_0px_0px_rgba(17,24,39,1)]">
              <p class="text-xs font-black uppercase tracking-[0.2em] text-gray-900">Badge Paling Nempel</p>
              <div class="mt-3 flex flex-wrap gap-2">
                <span v-for="badge in highlightedBadges" :key="badge.key" class="inline-block -rotate-1 border-2 border-gray-900 bg-white px-3 py-2 text-xs font-black uppercase tracking-[0.15em] text-gray-900">
                  {{ badge.label }} · {{ badge.count }}
                </span>
                <span v-if="!highlightedBadges.length" class="text-sm font-bold text-gray-800">Belum ada badge kepilih. Kamu bisa jadi yang pertama vote.</span>
              </div>
            </div>
          </div>

          <div v-reveal="'up'" class="border-4 border-white bg-gray-800 p-6 shadow-[8px_8px_0px_0px_rgba(255,255,255,0.15)] sm:p-8">
            <p class="text-sm font-black uppercase tracking-[0.2em] text-gray-300">Wall of Messages</p>
            <form class="mt-5 space-y-4 border-b-2 border-gray-700 pb-5" @submit.prevent="submitWallMessage">
              <label class="block">
                <span class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-gray-400">{{ editingMessageId ? 'Edit Pesan' : 'Tulis Pesan Buat Dia' }}</span>
                <textarea v-model="wallForm.message" rows="4" class="w-full resize-none border-2 border-white bg-gray-900 px-4 py-3 font-bold text-white outline-none focus:border-green-500" :placeholder="editingMessageId ? 'Perbarui pesan kamu...' : 'Tulis kalimat motivasi, pesan lucu, atau kenangan singkat...'" />
              </label>
              <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" :disabled="!savedNim || wallLoading" class="w-full border-4 border-white bg-white px-4 py-3 font-black uppercase text-gray-900 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 hover:bg-green-500 disabled:cursor-not-allowed disabled:opacity-50">
                  {{ wallLoading ? (editingMessageId ? 'Menyimpan Edit...' : 'Mengirim Pesan...') : (editingMessageId ? 'Simpan Perubahan' : 'Kirim Pesan') }}
                </button>
                <button v-if="editingMessageId" type="button" class="w-full border-4 border-gray-700 bg-gray-900 px-4 py-3 font-black uppercase text-white transition-all hover:-translate-y-1 hover:border-white" @click="cancelWallEdit">
                  Batal Edit
                </button>
              </div>
              <p class="text-xs font-bold text-gray-400">1 NIM cuma bisa ninggalin 1 pesan per mahasiswa, tapi masih bisa diedit atau dihapus.</p>
            </form>

            <div class="mt-5 space-y-4">
              <article v-for="message in wallMessages" :key="message.id" class="border-2 border-gray-700 bg-gray-900 p-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                  <div>
                    <p class="font-black uppercase text-green-400">{{ message.author_name }}</p>
                    <p v-if="message.updated_at && message.updated_at !== message.created_at" class="mt-1 text-[10px] font-black uppercase tracking-[0.2em] text-yellow-300">
                      Sudah diedit
                    </p>
                  </div>
                  <p class="text-xs font-black uppercase tracking-[0.2em] text-gray-500">{{ formatDateTime(message.updated_at || message.created_at) }}</p>
                </div>
                <p class="mt-3 text-sm font-bold leading-relaxed text-gray-200">{{ message.message }}</p>
                <div v-if="isOwnMessage(message)" class="mt-4 flex flex-wrap gap-3">
                  <button type="button" class="border-2 border-white bg-white px-3 py-2 text-xs font-black uppercase text-gray-900 shadow-[2px_2px_0px_0px_rgba(34,197,94,1)] transition-all hover:-translate-y-1 hover:bg-green-500" @click="startWallEdit(message)">
                    Edit
                  </button>
                  <button type="button" :disabled="wallLoading" class="border-2 border-red-400 bg-transparent px-3 py-2 text-xs font-black uppercase text-red-300 transition-all hover:-translate-y-1 hover:bg-red-500 hover:text-white disabled:cursor-not-allowed disabled:opacity-50" @click="deleteWallMessage(message)">
                    Hapus
                  </button>
                </div>
              </article>

              <div v-if="!wallMessages.length" class="border-2 border-dashed border-gray-700 bg-gray-900 p-5 text-center font-bold text-gray-400">
                Belum ada pesan. Kamu bisa jadi yang pertama ninggalin jejak.
              </div>
            </div>
          </div>
        </section>
      </div>

      <div v-else class="mt-10 border-4 border-red-500 bg-white p-10 text-center text-gray-900 shadow-[8px_8px_0px_0px_rgba(34,197,94,1)]">
        <p class="text-3xl font-black uppercase">Crew tidak ditemukan</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
type VoteBadge = {
  key: string
  label: string
  count: number
}

type WallMessage = {
  id: number
  author_name: string
  message: string
  created_at: string
  updated_at: string
}

type CrewProfile = {
  id: number
  name: string
  role: string
  status: string
  photo: string | null
  aib_photo: string | null
  fun_fact: string | null
  message: string | null
  vote_summary: VoteBadge[]
  highlighted_badges: VoteBadge[]
  wall_messages: WallMessage[]
}

const route = useRoute()
const config = useRuntimeConfig()
const { mediaUrl } = useApiMedia()
const showAltPhoto = ref(false)
const NIM_STORAGE_KEY = 'ti23itts-episode-nim'
const WALL_OWNERSHIP_KEY = 'ti23itts-student-wall-ownership'
const savedNim = ref('')
const nimDraft = ref('')
const voteLoading = ref(false)
const wallLoading = ref(false)
const feedbackMessage = ref('')
const wallForm = ref({ message: '' })
const editingMessageId = ref<number | null>(null)
let feedbackTimeout: number | null = null

const { data: student, pending, refresh } = await useFetch<CrewProfile>(`${config.public.apiBase}/students/${route.params.id}/public`, {
  transform: (value) => ({
    ...value,
    photo: mediaUrl(value.photo),
    aib_photo: mediaUrl(value.aib_photo || value.photo),
  }),
})

const activePhoto = computed(() => {
  if (!student.value) return mediaUrl(null)
  return showAltPhoto.value ? (student.value.aib_photo || student.value.photo || mediaUrl(null)) : (student.value.photo || mediaUrl(null))
})

const voteSummary = computed(() => student.value?.vote_summary || [])
const highlightedBadges = computed(() => student.value?.highlighted_badges || [])
const wallMessages = computed(() => student.value?.wall_messages || [])

const clearFeedback = () => {
  feedbackMessage.value = ''
  if (feedbackTimeout) {
    clearTimeout(feedbackTimeout)
    feedbackTimeout = null
  }
}

const showFeedback = (message: string) => {
  feedbackMessage.value = message

  if (feedbackTimeout) {
    clearTimeout(feedbackTimeout)
  }

  if (import.meta.client) {
    feedbackTimeout = window.setTimeout(() => {
      feedbackMessage.value = ''
      feedbackTimeout = null
    }, 3200)
  }
}

const hydrateSavedNim = () => {
  if (!import.meta.client) return
  savedNim.value = localStorage.getItem(NIM_STORAGE_KEY) || ''
  nimDraft.value = savedNim.value
}

const getOwnedMessageMap = (): Record<string, number> => {
  if (!import.meta.client) return {}

  try {
    return JSON.parse(localStorage.getItem(WALL_OWNERSHIP_KEY) || '{}')
  } catch {
    return {}
  }
}

const setOwnedMessage = (messageId: number | null) => {
  if (!import.meta.client || !student.value) return

  const current = getOwnedMessageMap()
  const key = String(student.value.id)

  if (messageId) {
    current[key] = messageId
  } else {
    delete current[key]
  }

  localStorage.setItem(WALL_OWNERSHIP_KEY, JSON.stringify(current))
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

const resetSavedNim = () => {
  savedNim.value = ''
  nimDraft.value = ''
  editingMessageId.value = null
  wallForm.value = { message: '' }

  if (import.meta.client) {
    localStorage.removeItem(NIM_STORAGE_KEY)
  }
}

const isOwnMessage = (message: WallMessage) => {
  if (!student.value || !savedNim.value) return false
  return getOwnedMessageMap()[String(student.value.id)] === message.id
}

const startWallEdit = (message: WallMessage) => {
  editingMessageId.value = message.id
  wallForm.value = { message: message.message }
}

const cancelWallEdit = () => {
  editingMessageId.value = null
  wallForm.value = { message: '' }
}

const formatDateTime = (date: string) => new Intl.DateTimeFormat('id-ID', {
  day: '2-digit',
  month: 'short',
  year: 'numeric',
  hour: '2-digit',
  minute: '2-digit',
}).format(new Date(date))

const submitVote = async (badge: string) => {
  if (!student.value) return
  if (!savedNim.value) {
    showFeedback('Simpan NIM kamu dulu sebelum vote ya.')
    return
  }

  voteLoading.value = true
  try {
    const response = await $fetch(`${config.public.apiBase}/students/${student.value.id}/vote`, {
      method: 'POST',
      body: {
        nim: savedNim.value,
        badge,
      },
    })
    await refresh()
    showFeedback(response.message || 'Vote badge berhasil disimpan.')
  } catch (error: any) {
    showFeedback(error?.data?.errors ? Object.values(error.data.errors).flat().join('\n') : (error?.data?.message || error?.message || 'Gagal menyimpan vote badge.'))
  } finally {
    voteLoading.value = false
  }
}

const submitWallMessage = async () => {
  if (!student.value) return
  if (!savedNim.value) {
    showFeedback('Simpan NIM kamu dulu sebelum kirim pesan ya.')
    return
  }

  wallLoading.value = true
  try {
    const isEditing = Boolean(editingMessageId.value)
    const response = await $fetch(
      isEditing
        ? `${config.public.apiBase}/students/${student.value.id}/messages/${editingMessageId.value}`
        : `${config.public.apiBase}/students/${student.value.id}/messages`,
      {
        method: isEditing ? 'PUT' : 'POST',
        body: {
          nim: savedNim.value,
          message: wallForm.value.message,
        },
      },
    )
    await refresh()
    if (!isEditing && response.wall_message?.id) {
      setOwnedMessage(response.wall_message.id)
    }
    wallForm.value = { message: '' }
    editingMessageId.value = null
    showFeedback(response.message || 'Pesan berhasil dikirim.')
  } catch (error: any) {
    showFeedback(error?.data?.errors ? Object.values(error.data.errors).flat().join('\n') : (error?.data?.message || error?.message || 'Gagal mengirim pesan.'))
  } finally {
    wallLoading.value = false
  }
}

const deleteWallMessage = async (message: WallMessage) => {
  if (!student.value) return
  if (!savedNim.value) {
    showFeedback('Simpan NIM kamu dulu sebelum hapus pesan ya.')
    return
  }

  wallLoading.value = true
  try {
    const response = await $fetch(`${config.public.apiBase}/students/${student.value.id}/messages/${message.id}`, {
      method: 'DELETE',
      body: { nim: savedNim.value },
    })
    await refresh()
    setOwnedMessage(null)
    if (editingMessageId.value === message.id) {
      cancelWallEdit()
    }
    showFeedback(response.message || 'Pesan berhasil dihapus.')
  } catch (error: any) {
    showFeedback(error?.data?.errors ? Object.values(error.data.errors).flat().join('\n') : (error?.data?.message || error?.message || 'Gagal menghapus pesan.'))
  } finally {
    wallLoading.value = false
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
