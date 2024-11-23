<template>
    <div class="flex flex-col items-center gap-2 border-r border-gray-700 p-4 min-w-[100px]">
      <span>{{ time }}</span>
      <span class="text-5xl">{{ icon }}</span>
      <span>{{ temp }}</span>
    </div>
  </template>

  <script>
  export default {
    props: {
      time: String,
      icon: String,
      temp: String,
    },
  };
  </script>
