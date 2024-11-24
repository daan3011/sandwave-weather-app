<template>
    <div class="flex-none bg-[#212B3C] p-4 rounded-xl">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">{{ city }} ({{ country }})</h1>
        <button class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg" @click="$emit('go-back')">
          Back
        </button>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      city: String,
      country: String,
    },
  };
  </script>
