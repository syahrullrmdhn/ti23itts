<template>
  <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-white">
    <!-- Animated Background Memories -->
    <div class="absolute inset-0 z-0">
      <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2 p-4 h-full animate-scroll">
        <div 
          v-for="(img, i) in memoryImages" 
          :key="i" 
          class="aspect-square rounded-lg overflow-hidden opacity-20 hover:opacity-40 transition-opacity"
        >
          <img 
            :src="img" 
            class="w-full h-full object-cover"
            alt="Memory"
          />
        </div>
      </div>
    </div>
    
    <div class="relative z-10 text-center px-4 max-w-6xl mx-auto">
      <div class="mb-8">
        <span class="inline-block px-6 py-2 bg-green-500/10 text-green-600 font-bold rounded-full text-sm uppercase tracking-wider mb-6 border border-green-200">
          Angkatan 2023
        </span>
      </div>
      
      <h1 class="font-display text-6xl md:text-8xl lg:text-9xl font-black mb-6 leading-tight text-gray-900">
        TI'23
        <br>
        <span class="text-green-500">Insiders</span>
      </h1>
      
      <p class="text-2xl md:text-3xl font-bold mb-6 text-gray-800">
        How fun we are!
      </p>
      
      <p class="text-lg md:text-xl text-gray-600 mb-12 max-w-2xl mx-auto font-medium">
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
const memoryImages = [
  'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1528605248644-14dd04022da1?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1543269664-56d93c1b41a6?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1543269865-cbf427effbad?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1531545514256-b1400bc00f31?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1513258496099-48168024aec0?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1515187029135-18ee286d815b?w=400&h=400&fit=crop',
]
</script>

<style scoped>
.btn-primary {
  @apply px-8 py-3 bg-green-500 text-white font-bold rounded-md hover:bg-green-600 transition duration-300 text-lg shadow-lg;
}

.btn-secondary {
  @apply px-8 py-3 bg-gray-900 text-white font-bold rounded-md hover:bg-gray-800 transition duration-300 text-lg shadow-lg;
}

h1 {
  line-height: 1.1;
}

@keyframes scroll {
  0% {
    transform: translateY(0);
  }
  100% {
    transform: translateY(-20%);
  }
}

.animate-scroll {
  animation: scroll 60s linear infinite;
}
</style>
