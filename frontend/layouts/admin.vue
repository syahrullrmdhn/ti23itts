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
      class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-40 lg:hidden" 
      @click="sidebarOpen = false"
    ></div>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 transition-all duration-300 lg:ml-72">
      
      <!-- Mobile Header (Hamburger Menu) - Hanya muncul di HP -->
      <header class="lg:hidden bg-white border-b-4 border-gray-900 p-4 flex justify-between items-center sticky top-0 z-30 shadow-sm">
        <div class="font-black uppercase tracking-widest text-lg text-gray-900">Markas Admin</div>
        <button 
          @click="sidebarOpen = true" 
          class="bg-green-500 border-2 border-gray-900 px-4 py-2 text-sm font-black uppercase shadow-[2px_2px_0px_0px_rgba(17,24,39,1)] active:translate-y-0.5 active:translate-x-0.5 active:shadow-[0px_0px_0px_0px_rgba(17,24,39,1)]"
        >
          MENU
        </button>
      </header>

      <!-- Tempat konten halaman di-render (seperti dashboard, students, dll) -->
      <main class="flex-1 p-4 sm:p-8 overflow-x-hidden">
        <slot />
      </main>
      
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const sidebarOpen = ref(false)

// Pastikan composable ini sudah sesuai dengan logic auth kamu
const auth = useAdminAuth()

const handleLogout = async () => {
  try {
    await auth.logout()
    // Arahkan kembali ke halaman login (index admin)
    navigateTo('/admin')
  } catch (error) {
    console.error('Logout gagal', error)
  }
}
</script>