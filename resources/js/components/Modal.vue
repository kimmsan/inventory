<template>
  <div id="modal-template">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header">
              <slot name="header">{{ $t("common.modal_header") }}</slot>
            </div>
            <div class="modal-body">
              <slot name="body">
                {{ $t("common.modal_body") }}
              </slot>
            </div>
            <div class="modal-footer asdfasd">
              <slot name="modal-footer">
                <button
                  class="modal-default-button btn btn-secondary"
                  @click="$emit('close')"
                >
                  <i class="fas fa-times"></i>
                  {{ $t("common.close") }}
                </button>
              </slot>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  props: {
    form: Object,
    allData: Object,
  },
  name: "Modal",
  data() {
    return {};
  },
  methods: {},
};
</script>


<style lang="scss" scoped>
.modal-mask {
  position: fixed;
  z-index: 50;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 600px;
  margin: 0px auto;
  padding: 20px 10px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
}

.modal-header {
  padding: 0;
}

.modal-header h4 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-footer {
  padding: 0;
}

.modal-default-button {
  display: block;
  margin-top: 1rem;
}

/**
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 **/
.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}

@media only screen and (max-width: 767px) {
  .modal-container {
    width: auto !important;
    padding: 0px;
    height: 100%;
    overflow: auto;
  }

  .modal-body {
    margin: 0px 0;
    max-height: inherit !important;
    overflow-y: auto;
  }
}
</style>
