<template>
  <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gray-900 text-white">
    <div class="absolute inset-0 z-0 grid grid-cols-2 md:grid-cols-4 opacity-10">
      <div v-for="i in 16" :key="i" class="aspect-video bg-gray-700 m-1 rounded-sm"></div>
    </div>
    
    <div class="relative z-10 text-center px-4 max-w-6xl mx-auto">
      <div class="mb-8">
        <span class="inline-block px-6 py-2 bg-green-500/10 text-green-400 font-bold rounded-full text-sm uppercase tracking-wider mb-6">
          Angkatan 2023
        </span>
      </div>
      
      <h1 class="font-display text-6xl md:text-8xl lg:text-9xl font-black mb-6 leading-tight">
        TI'23
        <br>
        <span class="text-green-400">Insiders</span>
      </h1>
      
      <p class="text-2xl md:text-3xl font-bold mb-6">
        How fun we are!
      </p>
      
      <p class="text-lg md:text-xl text-gray-300 mb-12 max-w-2xl mx-auto font-medium">
        Digital Yearbook Angkatan TI 2023 ITTS - Tempat nongkrong digital yang nyimpen semua memori, dari yang keren sampai aib legendaris!
      </p>
      
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
        <a 
          href="#episodes" 
          class="btn-primary"
        >
          Stalk Our Momen
        </a>
        
        <a 
          href="#timebox" 
          class="btn-secondary"
        >
          Perjalanan Kita
        </a>
      </div>
    </div>
  </section>
</template>

<script setup>
</script>

<style scoped>
/* Simplified basic styles to mimic the look without heavy configuration */
.btn-primary {
  @apply px-8 py-3 bg-green-500 text-gray-900 font-bold rounded-md hover:bg-green-400 transition duration-300 text-lg;
}

.btn-secondary {
  @apply px-8 py-3 bg-white text-gray-900 font-bold rounded-md hover:bg-gray-200 transition duration-300 text-lg;
}

h1 {
  line-height: 1.1;
}
</style>