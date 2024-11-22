<template>
    <div>
      <Navbar />
      <main>
        <slot />
      </main>
    </div>
  </template>

  <script>
  import Navbar from '../components/Shared/Navbar.vue';

  export default {
    components: { Navbar },
  };
  </script>
