<template>
    <div
      v-if="toasts.length"
      class="fixed bottom-5 right-5 space-y-3 z-50"
    >
      <div
        v-for="(toast, index) in toasts"
        :key="index"
        :class="[
          'px-4 py-2 rounded-lg shadow-md text-white',
          toast.type === 'success' ? 'bg-green-500' : 'bg-red-500',
        ]"
      >
        {{ toast.message }}
      </div>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        toasts: [],
      };
    },
    methods: {
      addToast(message, type = "success") {
        this.toasts.push({ message, type });

        setTimeout(() => {
          this.toasts.shift();
        }, 3000);
      },
    },
  };
  </script>
