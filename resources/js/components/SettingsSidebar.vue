<template>
  <div class="card settings-card no-print">
    <div class="card-header">{{ $t("common.settings") }}</div>
    <div class="card-body">
      <ul class="nav flex-column nav-pills m-1">
        <li class="nav-item">
          <router-link :to="{ name: 'setup.general' }" class="nav-link thumb">
            <i class="fas fa-cog" />
            {{ $t("setup.index.menu.item_one.title") }}
          </router-link>
        </li>
        <li class="nav-item">
          <router-link
            :to="{ name: 'setup.mailConfiguration' }"
            class="nav-link thumb"
          >
            <i class="fas fa-envelope" />
            {{ $t("setup.index.menu.item_nine.title") }}
          </router-link>
        </li>
        <li class="nav-item">
          <router-link
            :to="{ name: 'setup.smsConfiguration' }"
            class="nav-link thumb"
          >
            <i class="fas fa-sms" />
            {{ $t("setup.index.menu.item_ten.title") }}
          </router-link>
        </li>

        <li class="nav-item" v-if="developer">
          <router-link
            :to="{ name: 'permissions.index' }"
            class="nav-link thumb"
          >
            <i class="fas fa-braille" />
            {{ $t("setup.index.menu.item_eight.title") }}
          </router-link>
        </li>

        <li class="nav-item">
          <router-link :to="{ name: 'roles.index' }" class="nav-link thumb">
            <i class="fas fa-user-lock" />
            {{ $t("setup.index.menu.item_four.title") }}
          </router-link>
        </li>
        <li class="nav-item">
          <router-link
            :to="{ name: 'currencies.index' }"
            class="nav-link thumb"
          >
            <i class="fas fa-money-check-alt" />
            {{ $t("setup.index.menu.item_two.title") }}
          </router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{ name: 'units.index' }" class="nav-link thumb">
            <i class="fas fa-balance-scale" />
            {{ $t("setup.index.menu.item_three.title") }}
          </router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{ name: 'vatRates.index' }" class="nav-link thumb">
            <i class="fas fa-percentage" />
            {{ $t("setup.index.menu.item_five.title") }}
          </router-link>
        </li>
        <li class="nav-item">
          <router-link :to="{ name: 'brands.index' }" class="nav-link thumb">
            <i class="fas fa-bold" />
            {{ $t("setup.index.menu.item_six.title") }}
          </router-link>
        </li>
        <li class="nav-item">
          <router-link
            :to="{ name: 'paymentMethods.index' }"
            class="nav-link thumb"
          >
            <i class="fas fa-wallet" />
            {{ $t("setup.index.menu.item_seven.title") }}
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</template>


<script>
import { mapGetters } from "vuex";

export default {
  name: "SettingsSidebar",
  data: () => ({
    developer: "",
  }),
  // Map Getters
  computed: {
    ...mapGetters("auth", ["user"]),
  },
  created() {
    this.developer = this.user.roles.includes("developer");
  },
  methods: {},
};
</script>
