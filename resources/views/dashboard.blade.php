<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
    </style>
</head>
<body class="bg-[#0C121E] text-white">
    <div class="flex gap-5 w-full h-full p-5 overflow-auto">
        <!-- Sidebar -->
        <aside class="bg-[#212B3C] text-white rounded-xl p-5 max-w-[10%] flex-shrink-0">
            <div class="mb-10 flex justify-center">
                <span>LOGO</span>
            </div>
            <ul class="flex flex-col gap-4 text-center">
                <li class="font-bold flex flex-col gap-2">
                    <span>ICON</span>
                    Weather
                </li>
                <li class="font-bold flex flex-col gap-2">
                    <span>ICON</span>
                    Maps
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col gap-5 w-full">
            <!-- Search Form -->
            <form action="" class="flex gap-2">
                <input class="bg-[#212B3C] w-full p-2 rounded-lg text-white placeholder-gray-500" type="text" placeholder="Search for a city...">
                <button class="bg-gray-800 hover:bg-gray-600 text-white rounded-lg px-4 py-2">Search</button>
            </form>

            <!-- Weather Overview -->
            <div class="pl-12 py-8">
                <div class="flex flex-col gap-10">
                    <div>
                        <h1 class="text-3xl">Madrid</h1>
                        <span class="text-gray-400 text-sm">Chance of rain: 20%</span>
                    </div>
                    <span class="text-3xl">31°C</span>
                </div>
            </div>

            <!-- Today's Forecast -->
            <div class="bg-[#212B3C] p-4 rounded-xl">
                <h3 class="font-medium text-gray-500 uppercase pl-3 py-3">Today's forecast</h3>
                <div class="flex gap-6 overflow-x-auto px-6">
                    <!-- Repeatable Forecast Cards -->
                    <div class="flex flex-col items-center gap-2 border-r border-gray-700 p-6">
                        <span>6AM</span>
                        <span class="text-5xl">☀️</span>
                        <span>25°C</span>
                    </div>

                    <div class="flex flex-col items-center gap-2 border-r border-gray-700 p-6">
                        <span>6AM</span>
                        <span class="text-5xl">☀️</span>
                        <span>25°C</span>
                    </div>

                    <div class="flex flex-col items-center gap-2 border-r border-gray-700 p-6">
                        <span>6AM</span>
                        <span class="text-5xl">☀️</span>
                        <span>25°C</span>
                    </div>

                    <div class="flex flex-col items-center gap-2 border-r border-gray-700 p-6">
                        <span>6AM</span>
                        <span class="text-5xl">☀️</span>
                        <span>25°C</span>
                    </div>

                    <div class="flex flex-col items-center gap-2 border-r border-gray-700 p-6">
                        <span>6AM</span>
                        <span class="text-5xl">☀️</span>
                        <span>25°C</span>
                    </div>

                    <div class="flex flex-col items-center gap-2 border-r border-gray-700 p-6">
                        <span>6AM</span>
                        <span class="text-5xl">☀️</span>
                        <span>25°C</span>
                    </div>
                </div>
            </div>

            <!-- Air Conditions -->
            <div class="bg-[#212B3C] p-4 rounded-xl flex-grow">
                <h3 class="font-medium text-gray-500 uppercase pl-3 py-3">Air Conditions</h3>
                <div class="flex flex-wrap py-6">
                    <div class="flex gap-4 w-1/2 p-3">
                        <span class="text-5xl">☀️</span>
                        <div class="flex flex-col gap-1">
                            <span class="text-sm text-gray-500">Real Feel</span>
                            <span>30°C</span>
                        </div>
                    </div>

                    <div class="flex gap-4 w-1/2 p-3">
                        <span class="text-5xl">☀️</span>
                        <div class="flex flex-col gap-1">
                            <span class="text-sm text-gray-500">Real Feel</span>
                            <span>30°C</span>
                        </div>
                    </div>

                    <div class="flex gap-4 w-1/2 p-3">
                        <span class="text-5xl">☀️</span>
                        <div class="flex flex-col gap-1">
                            <span class="text-sm text-gray-500">Real Feel</span>
                            <span>30°C</span>
                        </div>
                    </div>

                    <div class="flex gap-4 w-1/2 p-3">
                        <span class="text-5xl">☀️</span>
                        <div class="flex flex-col gap-1">
                            <span class="text-sm text-gray-500">Real Feel</span>
                            <span>30°C</span>
                        </div>
                    </div>
                    <!-- Add more air condition items as needed -->
                </div>
            </div>
        </div>

        <!-- 7-Day Forecast -->
        <div class="bg-[#212B3C] w-full p-8 rounded-xl max-w-[40%] flex-shrink-0">
            <h3 class="font-medium text-gray-500 uppercase">7-Day Forecast</h3>
            <div class="flex flex-col">
                <!-- Repeatable Forecast Rows -->
                <div class="flex justify-between items-center py-6 border-b border-gray-700">
                    <div class="text-gray-500">
                        <h4>Today</h4>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-5xl">☀️</span>
                        <span>Sunny</span>
                    </div>
                    <div>
                        <span>33/44</span>
                    </div>
                </div>


                <div class="flex justify-between items-center py-6 border-b border-gray-700">
                    <div class="text-gray-500">
                        <h4>Today</h4>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-5xl">☀️</span>
                        <span>Sunny</span>
                    </div>
                    <div>
                        <span>33/44</span>
                    </div>
                </div>

                <div class="flex justify-between items-center py-6 border-b border-gray-700">
                    <div class="text-gray-500">
                        <h4>Today</h4>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-5xl">☀️</span>
                        <span>Sunny</span>
                    </div>
                    <div>
                        <span>33/44</span>
                    </div>
                </div>

                <div class="flex justify-between items-center py-6 border-b border-gray-700">
                    <div class="text-gray-500">
                        <h4>Today</h4>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-5xl">☀️</span>
                        <span>Sunny</span>
                    </div>
                    <div>
                        <span>33/44</span>
                    </div>
                </div>

                <div class="flex justify-between items-center py-6 border-b border-gray-700">
                    <div class="text-gray-500">
                        <h4>Today</h4>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-5xl">☀️</span>
                        <span>Sunny</span>
                    </div>
                    <div>
                        <span>33/44</span>
                    </div>
                </div>

                <div class="flex justify-between items-center py-6 border-b border-gray-700">
                    <div class="text-gray-500">
                        <h4>Today</h4>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-5xl">☀️</span>
                        <span>Sunny</span>
                    </div>
                    <div>
                        <span>33/44</span>
                    </div>
                </div>

                <div class="flex justify-between items-center py-6 border-b border-gray-700">
                    <div class="text-gray-500">
                        <h4>Today</h4>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-5xl">☀️</span>
                        <span>Sunny</span>
                    </div>
                    <div>
                        <span>33/44</span>
                    </div>
                </div>
                <!-- Add more rows as needed -->
            </div>
        </div>
    </div>
</body>
</html>
