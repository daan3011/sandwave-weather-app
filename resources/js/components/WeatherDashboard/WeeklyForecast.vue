<template>
    <div class="bg-[#212B3C] w-full lg:w-[35%] p-8 rounded-xl">
      <h3 class="font-medium text-gray-500 uppercase">5-Day Forecast</h3>
      <div class="flex flex-col">
        <ForecastRow
          v-for="(forecast, index) in forecasts"
          :key="index"
          :day="forecast.day"
          :icon="forecast.icon"
          :condition="forecast.weather"
          :tempRange="forecast.high_low"
        />
      </div>
    </div>
  </template>

  <script>
  import ForecastRow from "./ForecastRow.vue";

  export default {
    components: { ForecastRow },
    props: {
      forecasts: Array,
    },
  };
  </script>
