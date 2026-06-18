<template>
  <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
      <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgb(0 0 0 / 0.15) 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>
    
    <!-- Hero Content -->
    <div class="relative z-10 text-center px-4 max-w-6xl mx-auto">
      <div class="mb-8">
        <span class="inline-block px-6 py-2 bg-primary/10 text-primary font-bold rounded-full text-sm uppercase tracking-wider mb-6">
          Angkatan 2023
        </span>
      </div>
      
      <h1 class="font-display text-6xl md:text-8xl lg:text-9xl font-black mb-6 leading-tight text-dark">
        TI'23
        <br>
        <span class="text-primary">Insiders</span>
      </h1>
      
      <p class="text-2xl md:text-3xl font-bold text-gray-700 mb-6">
        How fun we are! 🎉
      </p>
      
      <p class="text-lg md:text-xl text-gray-600 mb-12 max-w-2xl mx-auto font-medium">
        Digital Yearbook Angkatan TI 2023 ITTS - Tempat nongkrong digital yang nyimpen semua memori, dari yang keren sampai aib legendaris!
      </p>
      
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
        <a 
          href="#episodes" 
          class="btn-primary"
        >
          🔥 Stalk Our Momen
        </a>
        
        <a 
          href="#timebox" 
          class="btn-secondary"
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
