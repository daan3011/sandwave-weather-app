<template>
    <div
      class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
      v-if="isVisible"
    >
      <div class="bg-[#212B3C] p-6 rounded-lg shadow-lg text-white w-80">
        <h3 class="text-lg font-bold mb-4">Create Weather Monitor</h3>
        <form @submit.prevent="submitForm">
          <div class="mb-4">
            <label for="city" class="block text-sm font-medium mb-1">City Name</label>
            <input
              id="city"
              v-model="monitor.city"
              type="text"
              class="w-full p-2 rounded-lg bg-gray-700 text-white"
              required
            />
          </div>
          <div class="mb-4">
            <label for="interval" class="block text-sm font-medium mb-1">Interval (Minutes)</label>
            <input
              id="interval"
              v-model.number="monitor.interval_minutes"
              type="number"
              class="w-full p-2 rounded-lg bg-gray-700 text-white"
              required
              min="1"
            />
          </div>
          <div class="flex justify-end gap-4">
            <button
              type="button"
              class="bg-gray-500 hover:bg-gray-400 px-4 py-2 rounded"
              @click="$emit('close')"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded"
            >
              Create
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      isVisible: Boolean,
    },
    data() {
      return {
        monitor: {
          city: "",
          interval_minutes: "",
        },
      };
    },
    methods: {
      submitForm() {
        this.$emit("create", { ...this.monitor });
        this.monitor = { city: "", interval_minutes: "" };
      },
    },
  };
  </script>
