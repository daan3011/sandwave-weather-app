<template>
    <form class="flex gap-2" @submit.prevent="submitSearch">
      <input
        v-model="searchQuery"
        class="bg-[#212B3C] w-full p-2 rounded-lg text-white placeholder-gray-500"
        type="text"
        placeholder="Search for a city..."
      />
      <button
        type="submit"
        class="bg-gray-800 hover:bg-gray-600 text-white rounded-lg px-4 py-2"
      >
        Search
      </button>
    </form>
  </template>

  <script>
  export default {
    data() {
      return {
        searchQuery: "",
      };
    },
    methods: {
      submitSearch() {
        if (this.searchQuery.trim()) {
          this.$emit("search", this.searchQuery.trim());
        }
      },
    },
  };
  </script>
