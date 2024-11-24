import apiClient from "./http";

export const fetchWeatherData = async (city) => {
  try {
    const response = await apiClient.get(`/weather?city=${city}`);
    return response.data.data;
  } catch (error) {
    console.error("Failed to fetch weather data:", error);
    throw error;
  }
};
