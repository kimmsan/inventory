<template>
  <vue-final-modal
    :lock-scroll="true"
    v-slot="{ params, close }"
    v-bind="$attrs"
    classes="z-50 custom-modal-dialog"
    content-class="modal-content"
    v-on="$listeners"
  >
    <span class="modal-header">
      <slot name="title"></slot>
      <button
        @click="close"
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </span>
    <div class="modal-body">
      <slot v-bind:params="params"></slot>
    </div>
    <!-- <div class="modal-footer">
      <v-button class="vfm-btn" @click="$emit('confirm', close)">confirm</v-button>
      <v-button class="vfm-btn" @click="$emit('cancel', close)">cancel</v-button>
    </div> -->
    <div class="modal-footer">
      <slot name="modal-footer"></slot>
    </div>
  </vue-final-modal>
</template>

<script>
export default {
  name: "VModal",
  inheritAttrs: false,
};
</script>

<style lang="scss">
.modal-content {
  max-width: 500px;
  margin: 1.75rem auto;
}

.custom-modal-dialog {
  overflow: auto;
}

.modal-body {
  max-height: 75vh;
  overflow: auto;
}
</style>
