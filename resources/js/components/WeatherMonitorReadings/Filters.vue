<template>
    <div class="flex justify-between items-center my-4">
      <h2 class="text-xl font-semibold">Monitored Weather Readings</h2>
      <div class="flex items-center gap-2">
        <input
          type="datetime-local"
          v-model="localStart"
          @change="handleStartChange"
          class="bg-[#212B3C] text-white px-3 py-2 rounded-lg focus:outline-none"
        />
        <input
          type="datetime-local"
          v-model="localEnd"
          @change="handleEndChange"
          class="bg-[#212B3C] text-white px-3 py-2 rounded-lg focus:outline-none"
        />
        <button
          @click="emitFilter"
          class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg"
        >
          Filter
        </button>
      </div>
    </div>
  </template>


<script>
export default {
  props: {
    start: {
      type: String,
      default: null,
    },
    end: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      localStart: this.start,
      localEnd: this.end,
    };
  },
  watch: {
    start(newVal) {
      this.localStart = newVal;
    },
    end(newVal) {
      this.localEnd = newVal;
    },
  },
  methods: {
    handleStartChange() {
      this.$emit("update:startDateTime", this.localStart);
    },
    handleEndChange() {
      this.$emit("update:endDateTime", this.localEnd);
    },
    emitFilter() {
      this.$emit("filter", { start: this.localStart, end: this.localEnd });
    },
  },
};
</script>
