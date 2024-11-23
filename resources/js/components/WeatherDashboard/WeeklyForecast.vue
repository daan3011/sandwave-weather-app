<template>
    <div class="bg-[#212B3C] w-full p-8 rounded-xl flex-1">
      <h3 class="font-medium text-gray-500 uppercase">7-Day Forecast</h3>
      <div class="flex flex-col">
        <ForecastRow
          v-for="(forecast, index) in forecasts"
          :key="index"
          :day="forecast.day"
          :icon="forecast.icon"
          :condition="forecast.condition"
          :tempRange="forecast.tempRange"
        />
      </div>
    </div>
  </template>

  <script>
  import ForecastRow from './ForecastRow.vue';

  export default {
    components: { ForecastRow },
    data() {
      return {
        forecasts: [
          { day: 'Today', icon: '☀️', condition: 'Sunny', tempRange: '33/44' },
          { day: 'Monday', icon: '🌦️', condition: 'Cloudy', tempRange: '30/40' },
          { day: 'Tuesday', icon: '☁️', condition: 'Overcast', tempRange: '28/38' },
          { day: 'Wednesday', icon: '🌧️', condition: 'Rainy', tempRange: '25/35' },
          { day: 'Thursday', icon: '🌧️', condition: 'Rainy', tempRange: '25/35' },
          { day: 'Friday', icon: '🌧️', condition: 'Rainy', tempRange: '25/35' },
          { day: 'Saturday', icon: '🌧️', condition: 'Rainy', tempRange: '25/35' },
          { day: 'Sunday', icon: '🌧️', condition: 'Rainy', tempRange: '25/35' },
          // Add more forecasts
        ],
      };
    },
  };
  </script>
