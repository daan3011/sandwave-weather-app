import axios from "axios";

const apiClient = axios.create({
  baseURL: "http://localhost:8080/api", // TODO: move to env file
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

export default apiClient;