<template>
    <div class="bg-[#212B3C] p-4 rounded-xl flex-grow">
      <h3 class="font-medium text-gray-500 uppercase pl-3 py-3">Air Conditions</h3>
      <div class="flex flex-wrap gap-4">
        <ConditionCard
          v-for="(condition, index) in conditions"
          :key="index"
          :label="condition.label"
          :value="condition.value"
        />
      </div>
    </div>
  </template>

  <script>
  import ConditionCard from "./ConditionCard.vue";

  export default {
    components: { ConditionCard },
    props: {
      conditions: Array,
    },
  };
  </script>
