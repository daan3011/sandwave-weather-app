<template>
    <div class="bg-[#212B3C] p-5 rounded-xl w-full sm:w-[48%] lg:w-[30%]">
      <h3 class="text-lg font-semibold mb-2">Recorded At: {{ formattedRecordedAt }}</h3>
      <p class="text-gray-400 text-sm mb-2">Temperature: {{ reading.temperature }}°C</p>
      <p class="text-gray-400 text-sm mb-2">Feels Like: {{ reading.feels_like }}°C</p>
      <p class="text-gray-400 text-sm mb-2">Weather: {{ reading.weather_description }}</p>
      <p class="text-gray-400 text-sm mb-2">Wind Speed: {{ reading.wind_speed }} m/s</p>
      <p class="text-gray-400 text-sm mb-2">Wind Direction: {{ reading.wind_direction }}°</p>
      <p v-if="reading.chance_of_rain !== null" class="text-gray-400 text-sm">
        Chance of Rain: {{ reading.chance_of_rain }}%
      </p>
    </div>
  </template>

  <script>
  export default {
    props: {
      reading: Object,
    },
    computed: {
      formattedRecordedAt() {
        return new Date(this.reading.recorded_at).toLocaleString();
      },
    },
  };
  </script>
