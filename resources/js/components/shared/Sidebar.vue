<template>
    <aside class="bg-[#212B3C] text-white rounded-xl p-5 max-w-[15%] w-full lg:w-auto flex-shrink-0">
      <!-- Logo Section -->
      <div class="mb-10 flex justify-center">
        <span>Sandwave weather</span>
      </div>

      <!-- Navigation Links -->
      <ul class="flex flex-col flex-row gap-8 lg:text-center text-sm lg:text-base">
        <li class="font-bold flex flex-col gap-2 items-center">
          <router-link
            to="/"
            class="flex flex-col justify-center items-center gap-1 text-center md:text-start md:gap-4 md:items-start md:flex-row text-white no-underline hover:text-gray-400"
          >
            <span class="text-2xl">🗺️</span>
            Weather overview
          </router-link>
        </li>
        <li class="font-bold flex flex-col gap-2 items-center">
          <router-link
            to="/weather-monitors"
            class="flex flex-col justify-center items-center gap-1 text-center md:text-start md:gap-4 md:items-start md:flex-row text-white no-underline hover:text-gray-400"
          >
            <span class="text-2xl">📡</span>
            Weather monitors

          </router-link>
        </li>
      </ul>
    </aside>
  </template>

  <script>
  export default {};
  </script>
