<template>
    <div class="flex flex-col lg:flex-row gap-5 w-full">
      <!-- Main Weather Section -->
      <div class="flex flex-col gap-5 w-full lg:w-[60%]">
        <SearchForm />
        <WeatherOverview />
        <TodayForecast />
        <AirCondition />
      </div>

      <!-- Sidebar Section for 7-Day Forecast -->
      <WeeklyForecast />
    </div>
  </template>

  <script>
  import SearchForm from '../components/WeatherDashboard/SearchForm.vue';
  import WeatherOverview from '../components/WeatherDashboard/WeatherOverview.vue';
  import TodayForecast from '../components/WeatherDashboard/TodayForecast.vue';
  import AirCondition from '../components/WeatherDashboard/AirCondition.vue';
  import WeeklyForecast from '../components/WeatherDashboard/WeeklyForecast.vue';

  export default {
    components: {
      SearchForm,
      WeatherOverview,
      TodayForecast,
      AirCondition,
      WeeklyForecast,
    },
  };
  </script>

  <style>
  /* Ensure page content spans the full height when needed */
  body {
    margin: 0;
    height: 100%;
    background-color: #0C121E;
    overflow: hidden;
  }
  </style>
