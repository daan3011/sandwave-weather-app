<template>
    <div
      class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
      v-if="isVisible"
    >
      <div class="bg-[#212B3C] p-6 rounded-lg shadow-lg text-white w-80">
        <h3 class="text-lg font-bold mb-4">Confirm Deletion</h3>
        <p class="mb-6">Are you sure you want to delete this weather monitor?</p>
        <div class="flex justify-end gap-4">
          <button
            class="bg-gray-500 hover:bg-gray-400 px-4 py-2 rounded"
            @click="$emit('close')"
          >
            Cancel
          </button>
          <button
            class="bg-red-600 hover:bg-red-500 px-4 py-2 rounded"
            @click="$emit('confirm')"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      isVisible: Boolean,
    },
  };
  </script>
