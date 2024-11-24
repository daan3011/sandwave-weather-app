<template>
    <div class="bg-[#212B3C] p-4 rounded-xl">
        <h3 class="font-medium text-gray-500 uppercase pl-3 py-3">Today's forecast</h3>
        <div class="flex gap-4 overflow-x-auto px-3">
            <ForecastCard v-for="(forecast, index) in forecasts" :key="index" :time="forecast.time"
                :icon="forecast.icon" :temp="String(forecast.temperature)" />
        </div>
    </div>
</template>

<script>
import ForecastCard from "./ForecastCard.vue";

export default {
    components: { ForecastCard },
    props: {
        forecasts: Array,
    },
};
</script>
