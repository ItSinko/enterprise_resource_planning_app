<template>
    <div class="autocomplete">
      <input
        type="text"
        class="form-control"
        v-model="searchTerm"
        @input="handleInput"
        @keydown.enter="selectOption"
        @blur="hideOptions"
      />
      <ul v-if="showOptions" class="dropdown-menu" :class="{ 'scrollable': filteredOptions.length > 10 }">
        <li
          v-for="(option, index) in filteredOptions"
          :key="index"
          class="list-group-item"
          @click="selectOption(option)"
        >
          {{ option }}
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    props: ['options', 'value'],
    data() {
      return {
        searchTerm: this.value,
        showOptions: false,
      };
    },
    watch: {
      value(newValue) {
        this.searchTerm = newValue;
      },
      searchTerm(newValue) {
        this.$emit('input', newValue);
      },
    },
    computed: {
      filteredOptions() {
        return this.options.filter(option =>
          option.toLowerCase().includes(this.searchTerm.toLowerCase())
        );
      },
    },
    methods: {
      handleInput() {
        this.showOptions = true;
      },
      selectOption(option) {
        this.searchTerm = option;
        this.showOptions = false;
        this.$emit('input', option);
      },
      hideOptions() {
        setTimeout(() => {
          this.showOptions = false;
        }, 200);
      },
    },
  };
  </script>
  
  <style scoped>
  .autocomplete {
    position: relative;
    display: inline-block;
  }
  
  .dropdown-menu {
    display: block;
    position: absolute;
    z-index: 1000;
    top: 100%;
    left: 0;
    margin: 2px 0 0;
    min-width: 160px;
    max-height: 200px; /* Set a maximum height for the dropdown menu */
    overflow-y: auto; /* Enable vertical scrolling */
    padding: 5px 0;
    list-style: none;
    background-color: #fff;
    border: 1px solid #ccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
  }
  
  .scrollable {
    overflow-y: auto;
  }
  
  .list-group-item {
    border: 0px;
  }
  
  :hover.list-group-item {
    background-color: #007bff;
    color: #fff;
  }
  </style>
  