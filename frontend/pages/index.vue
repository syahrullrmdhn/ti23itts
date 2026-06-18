<template>
  <div class="min-h-screen bg-white">
    <HeroSection />
    <TimeBox />
    <Episodes />
    <ProfilAngkatan />
    <Footer />
  </div>
</template>

<script setup>
useHead({
  link: [
    {
      rel: 'stylesheet',
      href: 'https://fonts.googleapis.com/css2?family=Poppins:wght@700;800;900&family=Inter:wght@400;500;600;700&display=swap'
    }
  ]
})
</script>
