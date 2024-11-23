<template>
    <div id="map" class="h-1/3 rounded-xl bg-gray-700"></div>
  </template>

  <script>
  import L from "leaflet";
  import "leaflet/dist/leaflet.css";

  export default {
    props: {
      latitude: Number,
      longitude: Number,
      city: String,
    },
    mounted() {
      if (!this.latitude || !this.longitude) {
        console.error("Missing latitude or longitude for monitor");
        return;
      }

      const map = L.map("map").setView([this.latitude, this.longitude], 13);
      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      }).addTo(map);

      L.marker([this.latitude, this.longitude])
        .addTo(map)
        .bindPopup(this.city || "Location")
        .openPopup();
    },
  };
  </script>
