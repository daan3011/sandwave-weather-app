<template>
    <div class="max-h-[300px]">
      <div id="map" class="h-[300px] rounded-xl bg-gray-700 flex-none"></div>
    </div>
  </template>

  <script>
  import L from "leaflet";
  import "leaflet/dist/leaflet.css";

  export default {
    props: {
      latitude: {
        type: Number,
        required: true,
      },
      longitude: {
        type: Number,
        required: true,
      },
    },
    data() {
      return {
        map: null, // Store the Leaflet map instance
        marker: null, // Store the Leaflet marker instance
      };
    },
    watch: {
      // Watch for changes in latitude or longitude
      latitude: "updateMap",
      longitude: "updateMap",
    },
    mounted() {
      this.initializeMap();
    },
    methods: {
      initializeMap() {
        if (!this.latitude || !this.longitude) {
          console.error("Missing latitude or longitude for map initialization.");
          return;
        }

        // Initialize map
        this.map = L.map("map").setView([this.latitude, this.longitude], 13);
        L.tileLayer(
          "https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Dark_Gray_Base/MapServer/tile/{z}/{y}/{x}"
        ).addTo(this.map);

        // Add marker
        this.marker = L.marker([this.latitude, this.longitude])
          .addTo(this.map)
          .bindPopup("Monitor Location")
          .openPopup();
      },
      updateMap() {
        if (this.map && this.marker) {
          // Update marker position
          this.marker.setLatLng([this.latitude, this.longitude]);

          this.map.setView([this.latitude, this.longitude], 13);
        } else {
          this.initializeMap();
        }
      },
    },
  };
  </script>
