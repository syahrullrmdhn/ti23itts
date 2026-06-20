<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>

<script setup>
const config = useRuntimeConfig()
const defaultTitle = "TI'23 Institut Teknologi Tangerang Selatan - Digital Yearbook"
const defaultDescription = 'Digital Yearbook Angkatan TI 2023 Institut Teknologi Tangerang Selatan. Tempat buat lihat profile mahasiswa, hall of shame, timeline, dan memori seru satu angkatan.'
const defaultImage = `${config.public.siteUrl}/social-preview.svg`

useHead({
  htmlAttrs: {
    lang: 'id',
  },
  link: [
    {
      rel: 'stylesheet',
      href: 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap'
    }
  ],
  script: [
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'WebSite',
        name: "TI'23 ITTS Digital Yearbook",
        alternateName: "TI'23 ITTS Insiders",
        url: config.public.siteUrl,
        description: defaultDescription,
        inLanguage: 'id-ID',
      }),
    },
  ],
})

useSeoMeta({
  title: defaultTitle,
  description: defaultDescription,
  ogTitle: defaultTitle,
  ogDescription: defaultDescription,
  ogType: 'website',
  ogSiteName: "TI'23 ITTS Yearbook",
  ogLocale: 'id_ID',
  ogUrl: config.public.siteUrl,
  ogImage: defaultImage,
  ogImageAlt: "TI'23 ITTS Digital Yearbook",
  twitterCard: 'summary_large_image',
  twitterTitle: defaultTitle,
  twitterDescription: defaultDescription,
  twitterImage: defaultImage,
})
</script>
