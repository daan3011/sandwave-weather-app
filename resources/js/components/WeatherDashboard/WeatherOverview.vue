<template>
    <div class="px-6 py-8 bg-[#212B3C] rounded-xl">
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
      city: String,
      weatherDescription: String,
      temperature: Number,
    },
  };
  </script>
