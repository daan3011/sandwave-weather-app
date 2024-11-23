<template>
    <div class="flex flex-col gap-5 w-full h-full">
      <!-- Header -->
      <div class="flex justify-between items-center bg-[#212B3C] p-4 rounded-xl">
        <h1 class="text-2xl font-bold">Weather Monitors</h1>
        <button
          class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg"
          @click="openCreateMonitorModal"
        >
          Create Monitor
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="flex justify-center items-center h-full">
        <p class="text-white text-lg">Loading monitors...</p>
      </div>

      <!-- Monitors List -->
      <div v-if="!isLoading && weatherMonitors.length > 0" class="flex flex-wrap gap-5">
        <WeatherMonitorCard
          v-for="monitor in weatherMonitors"
          :key="monitor.id"
          :monitor="monitor"
          @delete="openDeleteModal"
        />
      </div>

      <!-- No Monitors Overlay -->
      <div
        v-else-if="!isLoading && weatherMonitors.length === 0"
        class="flex flex-col items-center justify-center bg-[#212B3C] p-10 rounded-xl h-60"
      >
        <p class="text-xl font-medium mb-4">There are no weather monitors created yet.</p>
        <button
          class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-lg"
          @click="openCreateMonitorModal"
        >
          Create Monitor
        </button>
      </div>

      <!-- Create Monitor Modal -->
      <div
        v-if="isCreateModalOpen"
        class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
      >
        <div class="bg-[#212B3C] p-6 rounded-lg shadow-lg text-white w-80">
          <h3 class="text-lg font-bold mb-4">Create Weather Monitor</h3>
          <form @submit.prevent="handleCreateMonitor">
            <div class="mb-4">
              <label for="city" class="block text-sm font-medium mb-1">City Name</label>
              <input
                id="city"
                v-model="newMonitor.city"
                type="text"
                class="w-full p-2 rounded-lg bg-gray-700 text-white"
                required
              />
            </div>
            <div class="mb-4">
              <label for="interval" class="block text-sm font-medium mb-1">Interval (Minutes)</label>
              <input
                id="interval"
                v-model.number="newMonitor.interval_minutes"
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
                @click="closeCreateMonitorModal"
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
    </div>
  </template>

  <script>
  import {
    fetchWeatherMonitors,
    createWeatherMonitor,
    deleteWeatherMonitor,
  } from "../services/weatherMonitorsService.js";
  import WeatherMonitorCard from "../components/WeatherMonitors/WeatherMonitorCard.vue";

  export default {
    components: { WeatherMonitorCard },
    data() {
      return {
        weatherMonitors: [],
        isLoading: true,
        isCreateModalOpen: false,
        isDeleteModalOpen: false,
        monitorToDelete: null,
        newMonitor: {
          city: "",
          interval_minutes: "",
        },
      };
    },
    methods: {
      async loadWeatherMonitors() {
        this.isLoading = true;
        try {
          const response = await fetchWeatherMonitors();
          this.weatherMonitors = response.data || [];
        } catch (error) {
          console.error("Error loading weather monitors:", error);
          this.weatherMonitors = [];
        } finally {
          this.isLoading = false;
        }
      },
      openCreateMonitorModal() {
        this.isCreateModalOpen = true;
      },
      closeCreateMonitorModal() {
        this.isCreateModalOpen = false;
        this.newMonitor = { city: "", interval_minutes: "" }; // Reset form
      },
      async handleCreateMonitor() {
        try {
          await createWeatherMonitor(this.newMonitor);
          this.closeCreateMonitorModal();
          this.loadWeatherMonitors();
        } catch (error) {
          console.error("Error creating weather monitor:", error);
        }
      },
      openDeleteModal(monitorId) {
        this.isDeleteModalOpen = true;
        this.monitorToDelete = monitorId;
      },
      closeDeleteModal() {
        this.isDeleteModalOpen = false;
        this.monitorToDelete = null;
      },
      async confirmDelete() {
        try {
          await deleteWeatherMonitor(this.monitorToDelete);
          this.weatherMonitors = this.weatherMonitors.filter(
            (monitor) => monitor.id !== this.monitorToDelete
          );
          this.closeDeleteModal();
        } catch (error) {
          console.error("Error deleting weather monitor:", error);
        }
      },
    },
    mounted() {
      this.loadWeatherMonitors();
    },
  };
  </script>
