<template>
  <footer class="bg-gray-800 border-t-8 border-white text-white pt-16 pb-8 relative overflow-hidden">
    <div class="container mx-auto px-4 max-w-7xl relative z-10">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
        
        <!-- Brand Info -->
        <div class="space-y-6 flex flex-col items-start">
          <h3 class="font-display text-4xl font-black tracking-tight inline-block bg-gray-900 px-4 py-2 border-2 border-green-500 transform -rotate-1 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)]">
            TI'23 <span class="text-green-500">INSIDERS</span>
          </h3>
          <p class="text-gray-300 font-bold leading-relaxed max-w-sm bg-gray-900 p-4 border border-gray-700 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            Digital Yearbook Angkatan TI 2023 Institut Teknologi Tangerang Selatan. Tempat nongkrong digital yang nyimpen semua memori, dari yang keren sampai aib legendaris.
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="text-lg font-black text-white mb-6 uppercase tracking-widest inline-block border-b-4 border-green-500 pb-1">NAVIGATION</h4>
          <nav class="flex flex-col space-y-4 font-black">
            <NuxtLink to="/#hero" class="text-gray-300 hover:text-green-400 hover:translate-x-2 transition-all duration-300 inline-flex w-fit uppercase">
              > Home
            </NuxtLink>
            <NuxtLink to="/#timebox" class="text-gray-300 hover:text-green-400 hover:translate-x-2 transition-all duration-300 inline-flex w-fit uppercase">
              > Time Box
            </NuxtLink>
            <NuxtLink to="/#episodes" class="text-gray-300 hover:text-green-400 hover:translate-x-2 transition-all duration-300 inline-flex w-fit uppercase">
              > Hall of Shame
            </NuxtLink>
            <NuxtLink to="/#profil" class="text-gray-300 hover:text-green-400 hover:translate-x-2 transition-all duration-300 inline-flex w-fit uppercase">
              > Cast & Crew
            </NuxtLink>
            <NuxtLink to="/cerita" class="text-gray-300 hover:text-green-400 hover:translate-x-2 transition-all duration-300 inline-flex w-fit uppercase">
              > Cerita
            </NuxtLink>
          </nav>
        </div>

        <!-- Connect / Socials -->
        <div>
          <h4 class="text-lg font-black text-white mb-6 uppercase tracking-widest inline-block border-b-4 border-green-500 pb-1">CONNECT</h4>
          <div class="flex gap-4">
            <a href="https://github.com/syahrullrmdhn/ti23itts" target="_blank" rel="noopener noreferrer" aria-label="GitHub TI'23 ITTS" class="w-14 h-14 bg-white border-4 border-gray-900 flex items-center justify-center hover:bg-green-500 text-gray-900 transition-all duration-300 shadow-[4px_4px_0px_0px_rgba(34,197,94,1)] transform hover:-translate-y-1 hover:-rotate-6">
              <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
              </svg>
            </a>
          </div>
        </div>
      </div>

      <!-- Bottom Copyright Bar -->
      <div class="border-t-4 border-gray-700 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <p class="text-sm text-gray-400 font-bold uppercase tracking-wider">
          &copy; 2023-{{ new Date().getFullYear() }} TI'23 Institut Teknologi Tangerang Selatan. All rights reserved.
        </p>
        <div class="flex items-center gap-2 text-sm text-gray-400 font-bold uppercase tracking-wider bg-gray-900 px-4 py-2 border border-gray-700">
          <span>Made with</span>
          <span class="text-green-500 text-lg">⚡</span>
          <span>and lots of coffee.</span>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
</script>

<style scoped>
.font-display {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}
</style>
