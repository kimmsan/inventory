<template>
  <div v-if="products && products.length > 0" class="card">
    <div class="card-header">
      <h3 class="card-title">{{ $t("dashboard.stock_alert") }}</h3>
    </div>
    <div class="card-body">
      <table-loading v-show="loading" />
      <div v-if="products" class="table-responsive table-custom mb-4">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>{{ $t("common.s_no") }}</th>
              <th>{{ $t("common.code") }}</th>
              <th>{{ $t("common.name") }}</th>
              <th>{{ $t("common.quantity") }}</th>
              <th class="text-right">
                {{ $t("products.list.common.alert_quantity") }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(data, i) in products" :key="i">
              <td>{{ ++i }}</td>
              <td>{{ data.code | withPrefix(prefix) }}</td>
              <td>
                <router-link
                  :to="{
                    name: 'products.show',
                    params: { slug: data.slug },
                  }"
                >
                  {{ data.name }}
                </router-link>
              </td>
              <td>
                <span
                  v-if="data.availableQty < data.alertQty"
                  v-tooltip="$t('common.stock_alert_msg')"
                  class="badge badge-danger p-2"
                >
                  <i class="fas fa-exclamation"></i>
                </span>
                <span v-if="data.itemUnit">
                  {{ data.availableQty }} {{ data.itemUnit.code }}
                </span>
              </td>
              <td v-if="data.itemUnit" class="text-right">
                {{ data.alertQty }} {{ data.itemUnit.code }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";

export default {
  name: "StockAlert",
  data: () => ({
    products: "",
    prefix: "",
    loading: false,
  }),
  // Map Getters
  computed: {
    ...mapGetters("operations", ["appInfo"]),
  },
  created() {
    this.getData();
    this.prefix = this.appInfo.productPrefix;
  },
  methods: {
    // get products with lower stock
    async getData() {
      this.loading = true;
      const { data } = await axios.get(
        window.location.origin + "/api/dashboard/stock-alert"
      );
      this.products = data.data;
      this.loading = false;
    },
  },
};
</script>
