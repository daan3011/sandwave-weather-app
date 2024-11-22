<template>
    <div class="flex justify-between items-center py-4 border-b border-gray-700">
      <div class="text-gray-500">
        <h4>{{ day }}</h4>
      </div>
      <div class="flex items-center gap-4">
        <span class="text-5xl">{{ icon }}</span>
        <span>{{ condition }}</span>
      </div>
      <div>
        <span>{{ tempRange }}</span>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      day: String,
      icon: String,
      condition: String,
      tempRange: String,
    },
  };
  </script>
