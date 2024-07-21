import Vue from 'vue'
import '~/plugins'
import '~/components'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import can from '~/helpers/can'
import App from '~/components/App'

// vue datepicker
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css';

// vue page transition
import VuePageTransition from 'vue-page-transition'
Vue.use(VuePageTransition)

// vue clipboard
import Clipboard from 'v-clipboard'
window.Vue = require('vue').default;
Vue.use(Clipboard)

// vue-masonry-css for role permissions
import VueMasonry from 'vue-masonry-css'
Vue.use(VueMasonry)

// vue print tables
import VueHtmlToPaper from 'vue-html-to-paper'
const options = {
  name: '_blank',
  styles: [
    window.location.origin +'/css/app.css'
  ],
  timeout: 1000, // default timeout before the print window appears
}
Vue.use(VueHtmlToPaper, options)

// vue date range picker
import DateRangePicker from 'vue-mj-daterangepicker'
Vue.use(DateRangePicker)

// vue v-select
import vSelect from 'vue-select'
Vue.component('VSelect', vSelect)

// vue moment js
Vue.use(require('vue-moment'));

// vue tooltip
import VTooltip from 'v-tooltip'
Vue.use(VTooltip)

import { vfmPlugin } from 'vue-final-modal'
Vue.use(vfmPlugin)

VTooltip.options.defaultTemplate = '<div class="tooltip-vue" role="tooltip"><div class="tooltip-vue-arrow"></div><div class="tooltip-vue-inner"></div></div>'
VTooltip.options.defaultArrowSelector = '.tooltip-vue-arrow, .tooltip-vue__arrow'
VTooltip.options.defaultInnerSelector = '.tooltip-vue-inner, .tooltip-vue__inner'

Vue.config.productionTip = false
Vue.prototype.$can = can

// status is a boolean value
Vue.prototype.$toggleModal = (status) => {
  // if (status) {
  //   document.body.style.overflow = "hidden";
  // } else {
  //   document.body.style.overflow = "auto";
  // }
}

/* eslint-disable no-new */
new Vue({
  i18n,
  store,
  router,
  ...App
})
