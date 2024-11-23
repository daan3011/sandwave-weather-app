<template>
    <div class="flex gap-4 w-full sm:w-[48%] p-3">
      <span class="text-5xl">{{ icon }}</span>
      <div class="flex flex-col gap-1">
        <span class="text-sm text-gray-500">{{ label }}</span>
        <span>{{ value }}</span>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      icon: String,
      label: String,
      value: String,
    },
  };
  </script>
