<template>
    <div class="flex flex-col w-full h-full p-5 bg-[#0C121E] text-white">
      <!-- Include Toast component -->
      <Toast ref="toast" />

      <!-- Header -->
      <Header
        :city="monitor.city"
        :country="monitor.country"
        @go-back="goBack"
      />

      <!-- Main Content -->
      <div class="flex flex-col flex-grow overflow-hidden mt-4">
        <!-- Map Section -->
        <Map :latitude="monitor.latitude" :longitude="monitor.longitude" />

        <!-- Filter Section -->
        <Filters
          v-model:startDateTime="startDateTime"
          v-model:endDateTime="endDateTime"
          @filter="filterReadings"
        />

        <!-- Monitored Data Section -->
        <div
          class="flex-grow overflow-y-auto pt-4 h-[500px]"
          @scroll="handleScroll"
          ref="scrollContainer"
        >
          <div v-if="weatherReadings.length > 0" class="flex flex-wrap gap-5 p-2">
            <WeatherReadingCard
              v-for="(reading, index) in weatherReadings"
              :key="index"
              :reading="reading"
            />
          </div>

          <!-- Loading Indicator -->
          <div v-if="isLoading" class="text-center text-gray-400 mt-4">
            <p>Loading...</p>
          </div>

          <!-- No Data Message -->
          <div v-if="!isLoading && weatherReadings.length === 0" class="text-center text-gray-400">
            <p>No weather readings available for this monitor.</p>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script>
  import Toast from "../components/shared/ToastNotification.vue";
  import Header from "../components/WeatherMonitorReadings/Header.vue";
  import Map from "../components/WeatherMonitorReadings/Map.vue";
  import Filters from "../components/WeatherMonitorReadings/Filters.vue";
  import WeatherReadingCard from "../components/WeatherMonitorReadings/WeatherReadingCard.vue";
  import {
    fetchWeatherMonitorDetails,
    fetchWeatherMonitorReadings,
  } from "../services/weatherMonitorReadingsService";

  export default {
    components: {
      Toast,
      Header,
      Map,
      Filters,
      WeatherReadingCard,
    },
    props: ["id"],
    data() {
      return {
        monitor: {},
        weatherReadings: [],
        currentPage: 1,
        perPage: 10,
        isLoading: false,
        hasMore: true,
        startDateTime: null,
        endDateTime: null,
      };
    },
    watch: {
      startDateTime() {
        this.filterReadings();
      },
      endDateTime() {
        this.filterReadings();
      },
    },
    methods: {
      async fetchMonitorDetails() {
        try {
          const response = await fetchWeatherMonitorDetails(this.id);
          this.monitor = response.data;
        } catch (error) {
          this.$refs.toast.addToast("Failed to load monitor details.", "error");
        }
      },
      async fetchWeatherReadings(reset = false) {
        if (this.isLoading || (!this.hasMore && !reset)) return;

        if (reset) {
          this.weatherReadings = [];
          this.currentPage = 1;
          this.hasMore = true;
        }

        this.isLoading = true;
        try {
          const response = await fetchWeatherMonitorReadings(
            this.id,
            this.currentPage,
            this.perPage,
            this.startDateTime,
            this.endDateTime
          );
          this.weatherReadings = [...this.weatherReadings, ...response.data];
          this.hasMore = response.data.length >= this.perPage;
          this.currentPage += 1;

          if (reset) {
          } else if (response.data.length > 0) {
            this.$refs.toast.addToast("Fetched weather readings.");
          }
        } catch (error) {
          console.error("Failed to fetch weather readings:", error);
          this.$refs.toast.addToast("Failed to load weather readings.", "error");
          this.hasMore = false;
        } finally {
          this.isLoading = false;
        }
      },
      filterReadings() {
        this.fetchWeatherReadings(true);
      },
      handleScroll() {
        const container = this.$refs.scrollContainer;
        if (container.scrollTop + container.clientHeight >= container.scrollHeight - 10) {
          this.fetchWeatherReadings();
        }
      },
      goBack() {
        this.$router.push("/weather-monitors");
      },
    },
    async mounted() {
      await this.fetchMonitorDetails();
      await this.fetchWeatherReadings();
    },
  };
  </script>
