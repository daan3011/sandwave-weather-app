<template>
    <div class="min-h-screen w-full flex bg-[#0C121E] text-white">
      <!-- Sidebar -->
      <Sidebar />

      <!-- Main Content -->
      <main class="flex-1 flex flex-col gap-5 p-5 overflow-auto">
        <slot />
      </main>
    </div>
  </template>

  <script>
  import Sidebar from '../components/Shared/Sidebar.vue';

  export default {
    components: { Sidebar },
  };
  </script>
