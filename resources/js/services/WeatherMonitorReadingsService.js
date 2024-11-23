import apiClient from "./http";

/**
 * Fetch weather monitor details by ID.
 * @param {number} monitorId - The ID of the weather monitor.
 * @returns {Promise<object>} The monitor details.
 */
export const fetchWeatherMonitorDetails = async (monitorId) => {
  try {
    const response = await apiClient.get(`/weather-monitors/${monitorId}`);
    return response.data;
  } catch (error) {
    console.error(`Failed to fetch details for weather monitor ${monitorId}:`, error);
    throw error;
  }
};

/**
 * Fetch weather readings for a specific monitor with pagination and optional date filters.
 * @param {number} monitorId - ID of the monitor.
 * @param {number} page - Current page number.
 * @param {number} perPage - Number of items per page.
 * @param {string} startDate - (Optional) Start date in YYYY-MM-DD format.
 * @param {string} endDate - (Optional) End date in YYYY-MM-DD format.
 * @returns {Promise<object>} Paginated weather readings.
 */
export const fetchWeatherMonitorReadings = async (monitorId, page = 1, perPage = 10, startDate = null, endDate = null) => {
    try {
      const response = await apiClient.get(`/weather-readings`, {
        params: {
          weather_monitor_id: monitorId,
          page,
          perPage,
          start_date: startDate,
          end_date: endDate,
        },
      });
      return response.data;
    } catch (error) {
      console.error(`Failed to fetch weather readings for monitor ${monitorId}:`, error);
      throw error;
    }
  };
