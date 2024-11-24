<template>
    <div class="flex flex-col gap-5 w-full h-full">
      <!-- Include the Toast component -->
      <Toast ref="toast" />

      <!-- Header -->
      <div class="flex justify-between items-center bg-[#212B3C] p-4 rounded-xl">
        <h1 class="text-2xl font-bold">Weather Monitors</h1>
        <button
          class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg"
          @click="openCreateMonitorModal"
        >
          Create weather monitor
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="flex justify-center items-center h-full">
        <p class="text-white text-lg">Loading weather monitors...</p>
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
        <p class="text-xl font-medium mb-4">There are no weather monitors created yet</p>
        <button
          class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-lg"
          @click="openCreateMonitorModal"
        >
          Create weather monitor
        </button>
      </div>

      <!-- Create Monitor Modal -->
      <CreateMonitorModal
        v-if="isCreateModalOpen"
        :is-visible="isCreateModalOpen"
        @close="closeCreateMonitorModal"
        @create="handleCreateMonitor"
      />

      <!-- Delete Monitor Modal -->
      <DeleteMonitorModal
        v-if="isDeleteModalOpen"
        :is-visible="isDeleteModalOpen"
        @close="closeDeleteModal"
        @confirm="confirmDelete"
      />
    </div>
  </template>

  <script>
  import Toast from "../components/shared/ToastNotification.vue";
  import WeatherMonitorCard from "../components/WeatherMonitors/WeatherMonitorCard.vue";
  import CreateMonitorModal from "../components/WeatherMonitors/CreateMonitorModal.vue";
  import DeleteMonitorModal from "../components/WeatherMonitors/DeleteMonitorModal.vue";
  import {
    fetchWeatherMonitors,
    createWeatherMonitor,
    deleteWeatherMonitor,
  } from "../services/weatherMonitorsService";

  export default {
    components: {
      Toast,
      WeatherMonitorCard,
      CreateMonitorModal,
      DeleteMonitorModal,
    },
    data() {
      return {
        weatherMonitors: [],
        isLoading: true,
        isCreateModalOpen: false,
        isDeleteModalOpen: false,
        monitorToDelete: null,
      };
    },
    methods: {
      async loadWeatherMonitors() {
        this.isLoading = true;
        try {
          const response = await fetchWeatherMonitors();
          this.weatherMonitors = response.data || [];
        } catch (error) {
          this.$refs.toast.addToast("Error loading weather monitors", "error");
          console.error("Error loading weather monitors:", error);
        } finally {
          this.isLoading = false;
        }
      },
      openCreateMonitorModal() {
        this.isCreateModalOpen = true;
      },
      closeCreateMonitorModal() {
        this.isCreateModalOpen = false;
      },
      async handleCreateMonitor(monitor) {
        try {
          await createWeatherMonitor(monitor);
          this.loadWeatherMonitors();
          this.closeCreateMonitorModal();
          this.$refs.toast.addToast("Weather monitor created successfully");
        } catch (error) {
          this.$refs.toast.addToast("Error creating weather monitor", "error");
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
          this.$refs.toast.addToast("Weather monitor deleted successfully");
        } catch (error) {
          this.$refs.toast.addToast("Error deleting weather monitor", "error");
          console.error("Error deleting weather monitor:", error);
        } finally {
          this.closeDeleteModal();
        }
      },
    },
    mounted() {
      this.loadWeatherMonitors();
    },
  };
  </script>
