<template>
  <div class="min-h-screen bg-gray-50 flex font-sans selection:bg-green-500 selection:text-gray-900">
    
    <!-- Sidebar Component (Dari folder components/admin/Sidebar.vue) -->
    <AdminSidebar 
      :open="sidebarOpen" 
      @close="sidebarOpen = false" 
      @logout="handleLogout" 
    />

    <!-- Overlay untuk nutup sidebar di layar HP -->
    <div 
      v-if="sidebarOpen" 
      class="fixed inset-0 z-40 bg-gray-900/60 backdrop-blur-sm lg:hidden"
      @click="sidebarOpen = false"
    ></div>

    <!-- Main Content Wrapper -->
    <div class="flex min-w-0 flex-1 flex-col transition-all duration-300 lg:ml-72">
      
      <!-- Mobile Header (Hamburger Menu) - Hanya muncul di HP -->
      <header class="sticky top-0 z-30 flex items-center justify-between border-b-4 border-gray-900 bg-white p-4 shadow-sm lg:hidden">
        <div class="min-w-0">
          <div class="font-black uppercase tracking-widest text-lg text-gray-900">Markas Admin</div>
          <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-gray-500">TI'23 ITTS</p>
        </div>
        <button 
          @click="sidebarOpen = true" 
          class="flex items-center gap-2 bg-green-500 border-2 border-gray-900 px-4 py-2 text-sm font-black uppercase shadow-[2px_2px_0px_0px_rgba(17,24,39,1)] active:translate-y-0.5 active:translate-x-0.5 active:shadow-[0px_0px_0px_0px_rgba(17,24,39,1)]"
          aria-label="Buka menu admin"
        >
          <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
          </svg>
          Menu
        </button>
      </header>

      <!-- Tempat konten halaman di-render (seperti dashboard, students, dll) -->
      <main class="flex-1 overflow-x-hidden p-3 sm:p-6 lg:p-8">
        <slot />
      </main>
      
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const route = useRoute()

const sidebarOpen = ref(false)

// Pastikan composable ini sudah sesuai dengan logic auth kamu
const auth = useAdminAuth()

const handleLogout = async () => {
  try {
    await auth.logout()
    navigateTo('/admin')
  } catch (error) {
    console.error('Logout gagal', error)
  }
}

watch(() => route.fullPath, () => {
  sidebarOpen.value = false
})
</script>
