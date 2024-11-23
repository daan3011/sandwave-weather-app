<template>
    <div class="flex flex-col w-full h-full p-5 bg-[#0C121E] text-white">
      <!-- Header -->
      <div class="flex justify-between items-center bg-[#212B3C] p-4 rounded-xl">
        <h1 class="text-2xl font-bold">{{ monitor.city }} ({{ monitor.country }})</h1>
        <button class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg" @click="goBack">
          Back
        </button>
      </div>

      <!-- Main Content -->
      <div class="flex flex-col h-full">
        <!-- Map Section -->
        <div id="map" class="h-1/3 rounded-xl bg-gray-700"></div>

        <!-- Monitored Data Section -->
        <div class="flex-grow overflow-y-auto pt-4" @scroll="handleScroll" ref="scrollContainer">
          <!-- Filter Section -->
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Monitored Readings</h2>
            <div class="flex items-center gap-2">
              <input
                type="date"
                v-model="startDate"
                class="bg-[#212B3C] text-white px-3 py-2 rounded-lg focus:outline-none"
              />
              <input
                type="date"
                v-model="endDate"
                class="bg-[#212B3C] text-white px-3 py-2 rounded-lg focus:outline-none"
              />
              <button
                @click="filterReadings"
                class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg"
              >
                Filter
              </button>
            </div>
          </div>

          <div v-if="filteredReadings.length > 0" class="flex flex-wrap gap-5 p-2">
            <div
              v-for="(reading, index) in filteredReadings"
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
    props: ["id"],
    data() {
      return {
        monitor: {},
        weatherReadings: [],
        filteredReadings: [],
        startDate: "",
        endDate: "",
        currentPage: 1,
        perPage: 10,
        isLoading: false,
        hasMore: true,
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
          const response = await fetchWeatherMonitorReadings(this.id, this.currentPage, this.perPage, this.startDate, this.endDate);
          this.weatherReadings = [...this.weatherReadings, ...response.data];
          this.filteredReadings = this.weatherReadings;
          this.hasMore = response.data.length >= this.perPage;
          this.currentPage += 1;
        } catch (error) {
          console.error("Failed to fetch weather readings:", error);
          this.hasMore = false;
        } finally {
          this.isLoading = false;
        }
      },
      filterReadings() {
        this.currentPage = 1;
        this.weatherReadings = [];
        this.fetchWeatherReadings();
      },
      initMap() {
        const map = L.map("map").setView([this.monitor.latitude, this.monitor.longitude], 13);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);

        L.marker([this.monitor.latitude, this.monitor.longitude])
          .addTo(map)
          .bindPopup(this.monitor.city || "Location")
          .openPopup();
      },
      formatTimestamp(timestamp) {
        return new Date(timestamp).toLocaleString();
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
