<template>
    <div class="px-6 py-8 bg-[#212B3C] rounded-xl flex items-center gap-6">
      <!-- Icon Section -->
      <div v-if="icon" class="text-6xl">
        {{ icon }}
      </div>

      <!-- Weather Info Section -->
      <div class="flex flex-col gap-6">
        <div>
          <h1 class="text-3xl">{{ city }}</h1>
          <span class="text-gray-400 text-sm">{{ weatherDescription }}</span>
        </div>
        <span class="text-3xl">{{ temperature }}°C</span>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      city: {
        type: String,
        required: true,
      },
      weatherDescription: {
        type: String,
        required: true,
      },
      temperature: {
        type: Number,
        required: true,
      },
      icon: {
        type: String,
        required: true,
      },
    },
  };
  </script>
