<template>
  <div class="min-h-screen bg-white">
    <PublicNavbar />
    <HeroSection />
    <TimeBox />
    <Episodes />
    <ProfilAngkatan />
    <Footer />
  </div>
</template>

<script setup>
const config = useRuntimeConfig()

useHead({
  link: [
    {
      rel: 'stylesheet',
      href: 'https://fonts.googleapis.com/css2?family=Poppins:wght@700;800;900&family=Inter:wght@400;500;600;700&display=swap'
    },
    {
      rel: 'canonical',
      href: config.public.siteUrl
    }
  ],
  script: [
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'CollectionPage',
        name: "TI'23 ITTS Digital Yearbook",
        url: config.public.siteUrl,
        description: 'Digital yearbook Angkatan Teknik Informatika 2023 Institut Teknologi Tangerang Selatan.',
        inLanguage: 'id-ID',
      }),
    },
  ],
})

useSeoMeta({
  title: "TI'23 ITTS Digital Yearbook",
  description: 'Kenalan sama anak-anak TI’23 ITTS, lihat timeline perjalanan angkatan, hall of shame, dan cerita seru yang masih hidup sampai sekarang.',
  ogTitle: "TI'23 ITTS Digital Yearbook",
  ogDescription: 'Kenalan sama anak-anak TI’23 ITTS, lihat timeline perjalanan angkatan, hall of shame, dan cerita seru yang masih hidup sampai sekarang.',
  ogUrl: config.public.siteUrl,
  ogImage: `${config.public.siteUrl}/social-preview.svg`,
  twitterTitle: "TI'23 ITTS Digital Yearbook",
  twitterDescription: 'Kenalan sama anak-anak TI’23 ITTS, lihat timeline perjalanan angkatan, hall of shame, dan cerita seru yang masih hidup sampai sekarang.',
  twitterImage: `${config.public.siteUrl}/social-preview.svg`,
})
</script>
