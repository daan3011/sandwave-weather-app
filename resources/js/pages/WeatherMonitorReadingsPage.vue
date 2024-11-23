<template>
    <div class="flex flex-col w-full h-full p-5 bg-[#0C121E] text-white">
      <!-- Header -->
      <div class="flex-none bg-[#212B3C] p-4 rounded-xl">
        <div class="flex justify-between items-center">
          <h1 class="text-2xl font-bold">{{ monitor.city }} ({{ monitor.country }})</h1>
          <button
            class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg"
            @click="goBack"
          >
            Back
          </button>
        </div>
      </div>

      <!-- Main Content -->
      <div class="flex flex-col flex-grow overflow-hidden mt-4">
        <!-- Map Section -->
         <div class="max-h-[300px]">
            <div id="map" class="h-[300px] rounded-xl bg-gray-700 flex-none"></div>

         </div>

        <!-- Monitored Data Section -->
        <div
          class="flex-grow overflow-y-auto pt-4 h-[500px]"
          @scroll="handleScroll"
          ref="scrollContainer"
        >
          <div v-if="weatherReadings.length > 0" class="flex flex-wrap gap-5 p-2">
            <div
              v-for="(reading, index) in weatherReadings"
              :key="index"
              class="bg-[#212B3C] p-5 rounded-xl w-full sm:w-[48%] lg:w-[30%]"
            >
              <h3 class="text-lg font-semibold mb-2">Recorded At: {{ formatTimestamp(reading.recorded_at) }}</h3>
              <p class="text-gray-400 text-sm mb-2">Temperature: {{ reading.temperature }}°C</p>
              <p class="text-gray-400 text-sm mb-2">Feels Like: {{ reading.feels_like }}°C</p>
              <p class="text-gray-400 text-sm mb-2">Weather: {{ reading.weather_description }}</p>
              <p class="text-gray-400 text-sm mb-2">Wind Speed: {{ reading.wind_speed }} m/s</p>
              <p class="text-gray-400 text-sm mb-2">Wind Direction: {{ reading.wind_direction }}°</p>
              <p v-if="reading.chance_of_rain !== null" class="text-gray-400 text-sm">
                Chance of Rain: {{ reading.chance_of_rain }}%
              </p>
            </div>
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
  import L from "leaflet";
  import "leaflet/dist/leaflet.css";
  import {
    fetchWeatherMonitorDetails,
    fetchWeatherMonitorReadings,
  } from "../services/weatherMonitorReadingsService";

  export default {
    props: ["id"], // Receives monitor ID from route params
    data() {
      return {
        monitor: {}, // Monitor details
        weatherReadings: [], // Monitored data
        currentPage: 1, // Current page
        perPage: 10, // Items per page
        isLoading: false, // Loading state
        hasMore: true, // Whether more data is available
      };
    },
    methods: {
      async fetchMonitorDetails() {
        try {
          const response = await fetchWeatherMonitorDetails(this.id);
          this.monitor = response.data;
          this.initMap();
        } catch (error) {
          console.error("Failed to fetch monitor details:", error);
        }
      },
      async fetchWeatherReadings() {
        if (this.isLoading || !this.hasMore) return;

        this.isLoading = true;
        try {
          const response = await fetchWeatherMonitorReadings(this.id, this.currentPage, this.perPage);
          this.weatherReadings = [...this.weatherReadings, ...response.data];
          this.hasMore = response.data.length >= this.perPage;
          this.currentPage += 1;
        } catch (error) {
          console.error("Failed to fetch weather readings:", error);
          this.hasMore = false;
        } finally {
          this.isLoading = false;
        }
      },
      initMap() {
        if (!this.monitor.latitude || !this.monitor.longitude) {
          console.error("Missing latitude or longitude for monitor");
          return;
        }

        const map = L.map("map").setView([this.monitor.latitude, this.monitor.longitude], 13);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);

        L.marker([this.monitor.latitude, this.monitor.longitude])
          .addTo(map)
          .bindPopup(this.monitor.city || "Location")
          .openPopup();
      },
      handleScroll() {
        const container = this.$refs.scrollContainer;
        if (container.scrollTop + container.clientHeight >= container.scrollHeight - 10) {
          this.fetchWeatherReadings();
        }
      },
      formatTimestamp(timestamp) {
        return new Date(timestamp).toLocaleString();
      },
      goBack() {
        this.$router.push("/");
      },
    },
    async mounted() {
      await this.fetchMonitorDetails();
      await this.fetchWeatherReadings();
    },
  };
  </script>

  <style>
  /* Ensure Leaflet map takes full width and height */
  #map {
    width: 100%;
  }
  </style>
