<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="col-lg-12">
      <div class="row set-up-page">
        <div
          v-if="$can('general-settings')"
          class="col-xl-4 col-sm-12 col-md-6"
        >
          <div class="card card-large-icons">
            <div class="card-icon text-white bg-success">
              <i class="fas fa-cog" />
            </div>
            <div class="card-body p-0">
              <h4>{{ $t("setup.index.menu.item_one.title") }}</h4>
              <p>
                {{ $t("setup.index.menu.item_one.description") }}
              </p>
              <router-link
                :to="{ name: 'setup.general' }"
                href="#"
                class="card-cta"
              >
                {{ $t("setup.index.menu.item_one.link_text") }}
                <i class="fas fa-long-arrow-alt-right" />
              </router-link>
            </div>
          </div>
        </div>
        <div
          v-if="$can('mail-configuration')"
          class="col-xl-4 col-sm-12 col-md-6"
        >
          <div class="card card-large-icons">
            <div class="card-icon text-white bg-success">
              <i class="fas fa-envelope" />
            </div>
            <div class="card-body">
              <h4>{{ $t("setup.index.menu.item_nine.title") }}</h4>
              <p>
                {{ $t("setup.index.menu.item_nine.description") }}
              </p>
              <router-link
                :to="{ name: 'setup.mailConfiguration' }"
                href="#"
                class="card-cta"
              >
                {{ $t("setup.index.menu.item_nine.link_text") }}
                <i class="fas fa-long-arrow-alt-right" />
              </router-link>
            </div>
          </div>
        </div>

        <div
          v-if="$can('mail-configuration')"
          class="col-xl-4 col-sm-12 col-md-6"
        >
          <div class="card card-large-icons">
            <div class="card-icon text-white bg-success">
              <i class="fas fa-sms" />
            </div>
            <div class="card-body">
              <h4>{{ $t("setup.index.menu.item_ten.title") }}</h4>
              <p>
                {{ $t("setup.index.menu.item_ten.description") }}
              </p>
              <router-link
                :to="{ name: 'setup.smsConfiguration' }"
                href="#"
                class="card-cta"
              >
                {{ $t("setup.index.menu.item_nine.link_text") }}
                <i class="fas fa-long-arrow-alt-right" />
              </router-link>
            </div>
          </div>
        </div>

        <div v-if="developer" class="col-xl-4 col-sm-12 col-md-6">
          <div class="card card-large-icons">
            <div class="card-icon text-white bg-success">
              <i class="fas fa-braille" />
            </div>
            <div class="card-body">
              <h4>{{ $t("setup.index.menu.item_eight.title") }}</h4>
              <p>
                {{ $t("setup.index.menu.item_eight.description") }}
              </p>
              <router-link :to="{ name: 'permissions.index' }" class="card-cta">
                {{ $t("setup.index.menu.item_eight.link_text") }}
                <i class="fas fa-long-arrow-alt-right" />
              </router-link>
            </div>
          </div>
        </div>
        <div v-if="$can('user-role')" class="col-xl-4 col-sm-12 col-md-6">
          <div class="card card-large-icons">
            <div class="card-icon text-white bg-success">
              <i class="fas fa-user-lock" />
            </div>
            <div class="card-body">
              <h4>{{ $t("setup.index.menu.item_four.title") }}</h4>
              <p>
                {{ $t("setup.index.menu.item_four.description") }}
              </p>
              <router-link :to="{ name: 'roles.index' }" class="card-cta">
                {{ $t("setup.index.menu.item_four.link_text") }}
                <i class="fas fa-long-arrow-alt-right" />
              </router-link>
            </div>
          </div>
        </div>
        <div
          v-if="$can('currencies-management')"
          class="col-xl-4 col-sm-12 col-md-6"
        >
          <div class="card card-large-icons">
            <div class="card-icon text-white bg-success">
              <i class="fas fa-money-check-alt" />
            </div>
            <div class="card-body">
              <h4>{{ $t("setup.index.menu.item_two.title") }}</h4>
              <p>
                {{ $t("setup.index.menu.item_two.description") }}
              </p>
              <router-link :to="{ name: 'currencies.index' }" class="card-cta">
                {{ $t("setup.index.menu.item_two.link_text") }}
                <i class="fas fa-long-arrow-alt-right" />
              </router-link>
            </div>
          </div>
        </div>
        <div
          v-if="$can('units-management')"
          class="col-xl-4 col-sm-12 col-md-6"
        >
          <div class="card card-large-icons">
            <div class="card-icon text-white bg-success">
              <i class="fas fa-balance-scale" />
            </div>
            <div class="card-body">
              <h4>{{ $t("setup.index.menu.item_three.title") }}</h4>
              <p>
                {{ $t("setup.index.menu.item_three.description") }}
              </p>
              <router-link :to="{ name: 'units.index' }" class="card-cta">
                {{ $t("setup.index.menu.item_three.link_text") }}
                <i class="fas fa-long-arrow-alt-right" />
              </router-link>
            </div>
          </div>
        </div>
        <div
          v-if="$can('vat-rate-management')"
          class="col-xl-4 col-sm-12 col-md-6"
        >
          <div class="card card-large-icons">
            <div class="card-icon text-white bg-success">
              <i class="fas fa-percentage" />
            </div>
            <div class="card-body">
              <h4>{{ $t("setup.index.menu.item_five.title") }}</h4>
              <p>
                {{ $t("setup.index.menu.item_five.description") }}
              </p>
              <router-link :to="{ name: 'vatRates.index' }" class="card-cta">
                {{ $t("setup.index.menu.item_five.link_text") }}
                <i class="fas fa-long-arrow-alt-right" />
              </router-link>
            </div>
          </div>
        </div>
        <div
          v-if="$can('brands-management')"
          class="col-xl-4 col-sm-12 col-md-6"
        >
          <div class="card card-large-icons">
            <div class="card-icon text-white bg-success">
              <i class="fas fa-bold" />
            </div>
            <div class="card-body">
              <h4>{{ $t("setup.index.menu.item_six.title") }}</h4>
              <p>{{ $t("setup.index.menu.item_six.description") }}</p>
              <router-link :to="{ name: 'brands.index' }" class="card-cta">
                {{ $t("setup.index.menu.item_six.link_text") }}
                <i class="fas fa-long-arrow-alt-right" />
              </router-link>
            </div>
          </div>
        </div>
        <div
          v-if="$can('payment-method-management')"
          class="col-xl-4 col-sm-12 col-md-6"
        >
          <div class="card card-large-icons">
            <div class="card-icon text-white bg-success">
              <i class="fas fa-wallet" />
            </div>
            <div class="card-body">
              <h4>{{ $t("setup.index.menu.item_seven.title") }}</h4>
              <p>
                {{ $t("setup.index.menu.item_seven.description") }}
              </p>
              <router-link
                :to="{ name: 'paymentMethods.index' }"
                class="card-cta"
              >
                {{ $t("setup.index.menu.item_seven.link_text") }}
                <i class="fas fa-long-arrow-alt-right" />
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("setup.index.page_title") };
  },

  data: () => ({
    breadcrumbsCurrent: "setup.index.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "setup.index.breadcrumbs_first",
        url: "home",
      },
      {
        name: "setup.index.breadcrumbs_active",
        url: "",
      },
    ],
    developer: "",
  }),
  // Map Getters
  computed: {
    ...mapGetters("auth", ["user"]),
  },
  created() {
    this.developer = this.user.roles.includes("developer");
  },
};
</script>
