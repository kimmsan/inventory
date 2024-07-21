<template>
  <li v-if="Object.keys(locales).length > 1" class="nav-item dropdown">
    <a
      class="nav-link dropdown-toggle"
      href="#"
      role="button"
      data-toggle="dropdown"
      aria-haspopup="true"
      aria-expanded="false"
    >
      <lang-flag :iso="locales[locale][0]" /> {{ locales[locale][0] }}
    </a>
    <div class="dropdown-menu dropdown-menu-sm">
      <a
        v-for="(value, key) in locales"
        :key="key"
        class="dropdown-item"
        :title="value[1]"
        href="#"
        @click.prevent="setLocale(key)"
      >
        <div v-if="value[0] != 'AL'">
          <lang-flag :iso="value[0]" /> {{ value[0] }}
        </div>
        <div v-else>
          <span title="Albania" class="fi fis fi-al"
            >&nbsp;&nbsp;&nbsp; AL
          </span>
        </div>
      </a>
    </div>
  </li>
</template>

<script>
import { mapGetters } from "vuex";
import { loadMessages } from "~/plugins/i18n";
import LangFlag from "vue-lang-code-flags";

export default {
  computed: mapGetters({
    locale: "lang/locale",
    locales: "lang/locales",
  }),

  components: {
    LangFlag,
  },

  methods: {
    setLocale(locale) {
      if (this.$i18n.locale !== locale) {
        loadMessages(locale);
        this.$store.dispatch("lang/setLocale", { locale });
      }
    },
  },
};
</script>
