<template>
    <div class="autocomplete">
    <textarea
      class="form-control"
      @input="onChange()"
      v-model="search"
      @keydown.down="onArrowDown"
      @keydown.up="onArrowUp"
      @keydown.enter="onEnter"
    ></textarea>
    <ul
      id="autocomplete-results"
      v-show="isOpen"
      class="autocomplete-results"
      ref="scrollCointainer"
    >
      <li
        class="loading"
        v-if="isLoading"
      >
        Loading results...
      </li>
      <li
        v-else
        v-for="(result, i) in results"
        :key="i"
        @click="setResult(result.text)"
        class="autocomplete-result"
        :class="{ 'is-active': i === arrowCounter }"
        ref="options"
      >
        {{ result.text }}
      </li>
    </ul>
  </div>
</template>
<script>
  export default {
    name: 'SearchAutocomplete',
    props: {
      items: {
        type: Array,
        required: false,
        default: () => [],
      },
      isAsync: {
        type: Boolean,
        required: false,
        default: false,
      },
    },
    data() {
      return {
        isOpen: false,
        results: [],
        search: '',
        isLoading: false,
        arrowCounter: -1,
      };
    },
    watch: {
      items: function (value, oldValue) {
        if (value.length !== oldValue.length) {
          this.results = value;
          this.isLoading = false;
        }
      },
    },
    created() {
      this.search = this.$attrs.value;
    },  
    mounted() {
      document.addEventListener('click', this.handleClickOutside)
    },
    destroyed() {
      document.removeEventListener('click', this.handleClickOutside)
    },
    methods: {
      setResult(result) {
        this.search = result;
        this.$emit('input', result);
        this.isOpen = false;
      },
      filterResults() {
        this.results = this.items.filter((item) => {
          return item.text.toLowerCase().includes(this.search.toLowerCase());
        });
      },
      onChange() {
        this.$emit('input', this.search);
        if (this.isAsync) {
          this.isLoading = true;
        } else {
          if(this.items.length > 0) {
            this.filterResults();
            this.isOpen = true;
          }else{
            this.isOpen = false;
          }
        }
      },
      handleClickOutside(event) {
        if (!this.$el.contains(event.target)) {
          this.isOpen = false;
          this.arrowCounter = -1;
        }
      },
      onArrowDown(e) {
        e.preventDefault();
        if (this.arrowCounter < this.results.length - 1) {
          this.arrowCounter++;
        }
        this.fixScrolling();
      },
      onArrowUp(e) {
        e.preventDefault();
        if (this.arrowCounter > 0) {
          this.arrowCounter--;
        }
        this.fixScrolling();
      },
      fixScrolling(){
        const container = this.$refs.options[this.arrowCounter].scrollHeight;
        this.$refs.scrollCointainer.scrollTop = container * this.arrowCounter;
      },
      onEnter(event) {
        event.preventDefault();
        this.search = this.results[this.arrowCounter].text;
        this.isOpen = false;
        this.arrowCounter = -1;
      },
    },
  };
</script>
<style>
  .autocomplete {
    position: relative;
  }

  .autocomplete-results {
    padding: 0;
    margin: 0;
    border: 1px solid #eeeeee;
    height: 120px;
    overflow: auto;
  }

  .autocomplete-result {
    list-style: none;
    text-align: left;
    padding: 4px 2px;
    cursor: pointer;
  }

  .autocomplete-result.is-active,
  .autocomplete-result:hover {
    background-color: var(--primary);
    color: white;
  }
</style>