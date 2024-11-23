<template>
    <div class="bg-[#212B3C] p-5 rounded-xl w-full sm:w-[48%] lg:w-[30%] relative">
      <!-- Trash Can Icon -->
      <button
        class="absolute top-2 right-2 text-gray-400 hover:text-red-500"
        @click="handleDelete"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="2"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v2m-4 0h14"
          />
        </svg>
      </button>

      <!-- Monitor Info -->
      <h3 class="text-lg font-semibold mb-2">
        {{ monitor.city }} ({{ monitor.country }})
      </h3>
      <p class="text-gray-400 text-sm mb-4">
        Interval: {{ monitor.interval_minutes }} minutes
      </p>
      <p class="text-gray-400 text-sm">
        Next Run: {{ formattedNextRun }}
      </p>
    </div>
  </template>

  <script>
  export default {
    props: {
      monitor: {
        type: Object,
        required: true,
      },
    },
    computed: {
      formattedNextRun() {
        return new Date(this.monitor.next_run_at).toLocaleString();
      },
    },
    methods: {
      handleDelete() {
        this.$emit("delete", this.monitor.id);
      },
    },
  };
  </script>
