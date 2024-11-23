<template>
    <div class="bg-[#212B3C] p-4 rounded-xl">
      <h3 class="font-medium text-gray-500 uppercase pl-3 py-3">Today's forecast</h3>
      <div class="flex gap-4 overflow-x-auto px-3">
        <ForecastCard
          v-for="(forecast, index) in forecasts"
          :key="index"
          :time="forecast.time"
          :icon="forecast.icon"
          :temp="forecast.temp"
        />
      </div>
    </div>
  </template>

  <script>
  import ForecastCard from './ForecastCard.vue';

  export default {
    components: { ForecastCard },
    data() {
      return {
        forecasts: [
          { time: '6AM', icon: '☀️', temp: '25°C' },
          { time: '9AM', icon: '☀️', temp: '28°C' },
          { time: '12PM', icon: '🌤️', temp: '30°C' },
          { time: '6AM', icon: '☀️', temp: '25°C' },
          { time: '9AM', icon: '☀️', temp: '28°C' },
          { time: '9AM', icon: '☀️', temp: '28°C' },
          { time: '12PM', icon: '🌤️', temp: '30°C' },
          // Add more forecasts
        ],
      };
    },
  };
  </script>
