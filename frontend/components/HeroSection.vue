<template>
  <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Grid Photos with Colorful Overlay -->
    <div class="absolute inset-0 grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2 p-4 opacity-20">
      <div v-for="i in 48" :key="i" class="aspect-square bg-gradient-to-br from-bright-yellow via-fun-pink to-bright-blue rounded-lg"></div>
    </div>
    
    <!-- Colorful Overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-bright-yellow/30 via-fun-pink/20 to-mint-green/30"></div>
    
    <!-- Hero Content -->
    <div class="relative z-10 text-center px-4 max-w-5xl mx-auto">
      <h1 class="font-display text-6xl md:text-8xl lg:text-9xl font-black mb-6 leading-tight">
        <span class="inline-block bg-clip-text text-transparent bg-gradient-to-r from-fun-pink via-bright-yellow to-bright-blue animate-pulse">
          TI'23
        </span>
        <br>
        <span class="text-gray-900">Insiders</span>
      </h1>
      
      <p class="text-xl md:text-2xl font-semibold text-gray-700 mb-8 max-w-2xl mx-auto">
        How fun we are! 🎉
      </p>
      
      <p class="text-base md:text-lg text-gray-600 mb-12 max-w-xl mx-auto">
        Digital Yearbook Angkatan TI 2023 ITTS - Tempat nongkrong digital yang nyimpen semua memori, dari yang keren sampai aib legendaris!
      </p>
      
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
        <a 
          href="#episodes" 
          class="btn-primary bg-gradient-to-r from-fun-pink to-vibrant-orange shadow-lg hover:shadow-2xl"
        >
          🔥 Stalk Our Momen
        </a>
        
        <a 
          href="#timebox" 
          class="btn-primary bg-gradient-to-r from-bright-blue to-mint-green shadow-lg hover:shadow-2xl"
        >
          ⏰ Perjalanan Kita
        </a>
      </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
      <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
      </svg>
    </div>
  </section>
</template>

<script setup>
</script>
