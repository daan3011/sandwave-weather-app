<template>
    <div class="bg-[#212B3C] p-5 rounded-xl w-full sm:w-[48%] lg:w-[30%]">
        <h3 class="text-md font-semibold mb-2">
            Recorded At: {{ formattedRecordedAt }}
        </h3>

        <div class="flex items-center">
            <div class="text-6xl mr-8">
                {{ reading.icon }}
            </div>

            <div class="flex-1">
                <p class="text-gray-400 text-sm mb-2">
                    Temperature: {{ reading.temperature }}°C
                </p>
                <p class="text-gray-400 text-sm mb-2">
                    Feels Like: {{ reading.feels_like }}°C
                </p>
                <p class="text-gray-400 text-sm mb-2">
                    Weather: {{ reading.weather_description }}
                </p>
                <p class="text-gray-400 text-sm mb-2">
                    Wind Speed: {{ reading.wind_speed }} m/s
                </p>
                <p class="text-gray-400 text-sm mb-2">
                    Wind Direction: {{ reading.wind_direction }}°
                </p>
                <p v-if="reading.chance_of_rain !== null" class="text-gray-400 text-sm">
                    Chance of Rain: {{ reading.chance_of_rain }}%
                </p>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    name: "WeatherReadingCard",
    props: {
        reading: {
            type: Object,
            required: true,
            validator(value) {
                const requiredKeys = [
                    "recorded_at",
                    "temperature",
                    "feels_like",
                    "weather_description",
                    "wind_speed",
                    "wind_direction",
                ];
                return requiredKeys.every((key) => key in value);
            },
        },
    },
    computed: {
        formattedRecordedAt() {
            return this.formatDate(this.reading.recorded_at);
        },
    },
    methods: {
        formatDate(datetime) {
            if (!datetime) return "Invalid Date";

            const [datePart, timePart] = datetime.split(" ");
            const [day, month, year] = datePart.split("-");
            const isoFormatted = `${year}-${month}-${day}T${timePart}`;
            const dateObject = new Date(isoFormatted);

            return new Intl.DateTimeFormat("nl-NL", {
                weekday: "short",
                year: "numeric",
                month: "short",
                day: "numeric",
                hour: "numeric",
                minute: "numeric",
                hour12: false,
            }).format(dateObject);
        },
    },
};
</script>
