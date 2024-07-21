<template>
  <div class="wrapper">
    <navbar />
    <child />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Navbar from '~/components/Navbar'
export default {
  name: 'MainLayout',
  data: () => ({
    year: new Date().getFullYear(),
  }),
  components: {
    Navbar
  },
  // Map Getters
  computed: {
    ...mapGetters('operations', ['appInfo']),
  },
  methods: {
    addBodyClass(className) {
      document.body.classList.toggle(className)
    },
  },
}
</script>
