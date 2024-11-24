<template>
    <div class="flex flex-col lg:flex-row gap-5 w-full">
      <!-- Main Weather Section -->
      <div class="flex flex-col gap-5 w-full lg:w-[60%]">
        <!-- Show a loading skeleton while data is loading -->
        <template v-if="isLoading">
          <div class="bg-[#212B3C] p-4 rounded-xl animate-pulse h-20"></div>
          <div class="bg-[#212B3C] p-4 rounded-xl animate-pulse h-60"></div>
          <div class="bg-[#212B3C] p-4 rounded-xl animate-pulse h-40"></div>
        </template>
        <template v-else>
          <SearchForm @search="handleSearch" />
          <WeatherOverview
            :city="weatherData.city"
            :weatherDescription="weatherData.current_weather.weather_description"
            :temperature="weatherData.current_weather.temperature"
            :icon="weatherData.current_weather.icon"
          />
          <TodayForecast :forecasts="weatherData.todays_forecast" />
          <AirCondition :conditions="weatherData.air_conditions" />
        </template>
      </div>

      <!-- Sidebar Section for 7-Day Forecast -->
        <template v-if="isLoading">
          <div class="bg-[#212B3C] p-8 rounded-xl animate-pulse w-full lg:w-[35%]"></div>
        </template>
        <template v-else>
          <WeeklyForecast :forecasts="weatherData.five_day_forecast" />
        </template>
      </div>
  </template>


<script>
import SearchForm from "../components/WeatherDashboard/SearchForm.vue";
import WeatherOverview from "../components/WeatherDashboard/WeatherOverview.vue";
import TodayForecast from "../components/WeatherDashboard/TodayForecast.vue";
import AirCondition from "../components/WeatherDashboard/AirCondition.vue";
import WeeklyForecast from "../components/WeatherDashboard/WeeklyForecast.vue";
import apiClient from "../services/http";

export default {
    components: {
        SearchForm,
        WeatherOverview,
        TodayForecast,
        AirCondition,
        WeeklyForecast,
    },
    data() {
        return {
            weatherData: {
                city: "",
                weather_description: "",
                current_temperature: null,
                todays_forecast: [],
                air_conditions: [],
                five_day_forecast: [],
                icon : "☁️",
            },
            isLoading: true, // Track loading state
        };
    },
    methods: {
        async fetchWeather(city) {
            this.isLoading = true;
            try {
                const response = await apiClient.get(`/weather`, { params: { city } });
                this.weatherData = response.data;
            } catch (error) {
                console.error("Failed to fetch weather data:", error);
            } finally {
                this.isLoading = false;
            }
        },
        handleSearch(city) {
            this.fetchWeather(city);
        },
    },
    mounted() {
        // Load default weather data on page load
        this.fetchWeather("Amsterdam");
    },
};
</script>
