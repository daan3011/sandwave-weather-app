<template>
    <aside class="bg-[#212B3C] text-white rounded-xl p-5 lg:max-w-[15%] w-full lg:w-auto flex-shrink-0">
      <!-- Logo Section -->
      <div class="mb-10 flex justify-center">
        <span>Sandwave weather</span>
      </div>

      <!-- Navigation Links -->
      <ul class="flex lg:flex-col flex-row gap-8 lg:text-center text-sm lg:text-base">
        <li class="font-bold flex flex-col gap-2 items-center">
          <router-link
            to="/"
            class="text-white no-underline hover:text-gray-400"
          >
            <span class="text-2xl pr-4">🗺️</span>
            Weather overview
          </router-link>
        </li>
        <li class="font-bold flex flex-col gap-2 items-center">
          <router-link
            to="/weather-monitors"
            class="text-white no-underline hover:text-gray-400"
          >
            <span class="text-2xl pr-4">📡</span>
            Weather monitors
          </router-link>
        </li>
      </ul>
    </aside>
  </template>

  <script>
  export default {};
  </script>
