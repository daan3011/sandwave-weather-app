import apiClient from "./http";

export const fetchWeatherMonitors = async () => {
  try {
    const response = await apiClient.get("/weather-monitors");
    return response.data;
  } catch (error) {
    console.error("Failed to fetch weather monitors:", error);
    throw error;
  }
};

export const createWeatherMonitor = async (data) => {
    try {
      const response = await apiClient.post("/weather-monitors", data);
      return response;
    } catch (error) {
      console.error("Failed to create a weather monitor:", error);
      throw error;
    }
  };

export const deleteWeatherMonitor = async (monitorId) => {
    try {
      await apiClient.delete(`/weather-monitors/${monitorId}`);
    } catch (error) {
      console.error("Failed to delete weather monitor:", error);
      throw error;
    }
  };
