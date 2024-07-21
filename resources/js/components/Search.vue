<template>
  <div class="search-area">
    <div
      class="search-btn"
      :class="[query !== '' ? 'd-none' : 'd-inline-block']"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-6 w-6"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
        />
      </svg>
    </div>
    <input
      ref="autoFocusInput"
      type="text"
      :value="query"
      class="search-input form-control"
      :placeholder="$t('common.search')"
      @input="$emit('reset-pagination', $event.target.value)"
    />
    <label
      class="search-btn"
      :class="[query !== '' ? 'd-inline-block' : 'd-none']"
      @click="$emit('reload')"
    >
      <i class="fas fa-times" />
    </label>
  </div>
</template>

<script>
export default {
  name: "Search",
  model: {
    prop: "query",
    event: "reset-pagination",
  },
  mounted() {
    if (this.isPosSearch) {
      this.$nextTick(() => this.$refs.autoFocusInput.focus());
    }
  },
  props: {
    query: {},
    isPosSearch: {
      type: Boolean,
      default: false,
    },
  },
};
</script>

<style>
.search-area input {
  padding: 5px 11px 5px 35px;
  border-radius: 5px;
}

.search-btn {
  position: absolute;
  left: 18px;
  top: 6px;
}
</style>
