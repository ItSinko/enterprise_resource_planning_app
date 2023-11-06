<!-- TimeInput.vue -->
<template>
  <div>
    <label for="timeInput">Time (24-hour format):</label>
    <input
      type="text"
      id="timeInput"
      v-model="time"
      @input="validateTime"
    />
  </div>
</template>

<script>
export default {
  data() {
    return {
      time: '',
    };
  },
  methods: {
    validateTime() {
      // You can add validation logic here to ensure the time is in the correct 24-hour format
      // For example, you can use regular expressions to validate the input.
      // Here, we'll assume a basic format validation.
      const validTime = /^([01]\d|2[0-3]):([0-5]\d)$/.test(this.time);
      if (!validTime) {
        this.time = ''; // Clear the input if it's not valid
      }
    },
  },
};
</script>
