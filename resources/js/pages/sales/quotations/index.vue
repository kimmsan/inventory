<template>
  <div class="mb-50">
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card custom-card w-100">
          <div class="card-header setings-header">
            <div class="col-xl-4 col-4">
              <h3 class="card-title">
                {{ $t("sales.quotations.index.page_title") }}
              </h3>
            </div>
            <div class="col-xl-8 col-8 float-right text-right">
              <div class="btn-group c-w-100">
                <a
                  @click="refreshTable()"
                  href="#"
                  v-tooltip="'Refresh'"
                  class="btn btn-success"
                >
                  <i class="fas fa-sync"></i>
                </a>
                <a
                  href="quotations/pdf"
                  v-tooltip="$t('common.export_table')"
                  class="btn btn-secondary"
                >
                  <i class="fas fa-file-export"></i>
                </a>
                <a
                  @click="print"
                  v-tooltip="$t('common.print_table')"
                  class="btn btn-info"
                >
                  <i class="fas fa-print"></i>
                </a>
                <router-link
                  v-if="$can('quotation-create')"
                  :to="{ name: 'quotations.create' }"
                  class="btn btn-primary"
                >
                  {{ $t("common.create") }}
                  <i class="fas fa-plus-circle d-none d-sm-inline-block" />
                </router-link>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body position-relative">
            <div class="row">
              <div class="col-6 col-xl-4 mb-2">
                <search
                  v-model="query"
                  @reset-pagination="resetPagination()"
                  @reload="reload"
                />
              </div>
              <div class="col-6 col-xl-8 mb-2 text-right">
                <date-range-picker
                  ref="picker"
                  opens="left"
                  :locale-data="locale"
                  :minDate="minDate"
                  :maxDate="maxDate"
                  :singleDatePicker="false"
                  :showWeekNumbers="false"
                  :showDropdowns="true"
                  :autoApply="true"
                  v-model="dateRange"
                  @update="updateValues"
                  :linkedCalendars="true"
                  class="c-w-100"
                >
                  <template v-slot:input="picker" style="min-width: 350px">
                    {{ picker.startDate | startDate }} -
                    {{ picker.endDate | endDate }}
                  </template>
                </date-range-picker>
              </div>
            </div>
            <table-loading v-show="loading" />
            <div class="table-responsive table-custom mt-3" id="printMe">
              <table class="table">
                <thead>
                  <tr>
                    <th>{{ $t("common.s_no") }}</th>
                    <th>{{ $t("sales.common.quotation_no") }}</th>
                    <th>{{ $t("sales.common.quotation_date") }}</th>
                    <th>{{ $t("common.client") }}</th>
                    <th>{{ $t("common.subtotal") }}</th>
                    <th>{{ $t("common.transport") }}</th>
                    <th>{{ $t("common.discount") }}</th>
                    <th>{{ $t("common.tax") }}</th>
                    <th>{{ $t("common.net_total") }}</th>
                    <th>{{ $t("common.status") }}</th>
                    <th
                      v-if="
                        $can('quotation-to-invoice') ||
                        $can('quotation-view') ||
                        $can('quotation-edit') ||
                        $can('quotation-delete')
                      "
                      class="text-right no-print"
                    >
                      {{ $t("common.action") }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-show="items.length" v-for="(data, i) in items" :key="i">
                    <td>
                      <span v-if="pagination && pagination.current_page > 1">
                        {{
                          pagination.per_page * (pagination.current_page - 1) +
                          (i + 1)
                        }}
                      </span>
                      <span v-else>{{ i + 1 }}</span>
                    </td>
                    <td>
                      <router-link
                        v-if="$can('quotation-view')"
                        :to="{
                          name: 'quotations.show',
                          params: { slug: data.slug },
                        }"
                        >{{ data.quotationNo | withPrefix(quotationPrefix) }}
                      </router-link>
                      <span v-else>{{
                        data.quotationNo | withPrefix(quotationPrefix)
                      }}</span>
                    </td>
                    <td>
                      <span v-if="data.date">{{
                        data.date | moment("Do MMM, YYYY")
                      }}</span>
                    </td>
                    <td>{{ data.clientName }}</td>
                    <td>{{ data.subTotal | withCurrency }}</td>
                    <td>{{ data.transport | withCurrency }}</td>
                    <td>{{ data.discount | withCurrency }}</td>
                    <td>{{ data.totalTax | withCurrency }}</td>
                    <td>{{ data.total | withCurrency }}</td>
                    <td>
                      <span v-if="data.status === 1" class="badge bg-success">{{
                        $t("common.active")
                      }}</span>
                      <span v-else class="badge bg-danger">{{
                        $t("common.in_active")
                      }}</span>
                    </td>
                    <td
                      v-if="
                        $can('quotation-to-invoice') ||
                        $can('quotation-view') ||
                        $can('quotation-edit') ||
                        $can('quotation-delete')
                      "
                      class="text-right no-print"
                    >
                      <div class="btn-group">
                        <router-link
                          v-if="$can('quotation-to-invoice')"
                          v-tooltip="
                            $t('sales.quotations.index.create_invoice')
                          "
                          :to="{
                            name: 'quotations.invoice',
                            params: { slug: data.slug },
                          }"
                          class="btn btn-success btn-sm"
                        >
                          <i class="fas fa-file-invoice" />
                        </router-link>
                        <router-link
                          v-if="$can('quotation-view')"
                          v-tooltip="$t('common.view')"
                          :to="{
                            name: 'quotations.show',
                            params: { slug: data.slug },
                          }"
                          class="btn btn-primary btn-sm"
                        >
                          <i class="fas fa-eye" />
                        </router-link>
                        <router-link
                          v-if="$can('quotation-edit')"
                          v-tooltip="$t('common.edit')"
                          :to="{
                            name: 'quotations.edit',
                            params: { slug: data.slug },
                          }"
                          class="btn btn-info btn-sm"
                        >
                          <i class="fas fa-edit" />
                        </router-link>
                        <a
                          v-if="$can('quotation-delete')"
                          v-tooltip="$t('common.delete')"
                          href="#"
                          class="btn btn-danger btn-sm"
                          @click="deleteData(data.slug)"
                        >
                          <i class="fas fa-trash" />
                        </a>
                      </div>
                    </td>
                  </tr>
                  <tr v-show="!loading && !items.length">
                    <td colspan="11">
                      <EmptyTable />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer">
            <div class="dtable-footer">
              <div class="form-group row display-per-page">
                <label>{{ $t("per_page") }} </label>
                <div>
                  <select
                    @change="updatePerPager"
                    v-model="perPage"
                    class="form-control form-control-sm ml-1"
                  >
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                  </select>
                </div>
              </div>
              <!-- pagination-start -->
              <pagination
                v-if="pagination && pagination.last_page > 1"
                :pagination="pagination"
                :offset="5"
                class="justify-flex-end"
                @paginate="paginate"
              />
              <!-- pagination-end -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import i18n from "~/plugins/i18n";
import DateRangePicker from "vue2-daterange-picker";
import moment from "moment";
import { mapGetters } from "vuex";
export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("sales.quotations.index.page_title") };
  },
  components: {
    DateRangePicker,
  },
  data: () => ({
    breadcrumbsCurrent: "sales.quotations.index.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "sales.quotations.index.breadcrumbs_first",
        url: "home",
      },
      {
        name: "sales.quotations.index.breadcrumbs_second",
        url: "",
      },
    ],
    query: "",
    perPage: 10,
    quotationPrefix: "",
    clientPrefix: "",
    minDate: moment(new Date("01-01-2021")).format("YYYY-MM-DD"),
    maxDate: moment().add(1, "days").format("YYYY-MM-DD"),
    dateRange: {
      startDate: "",
      endDate: "",
    },
    locale: {
      direction: "ltr",
      format: "YYYY-MM-DD",
      separator: " - ",
      applyLabel: "Apply",
      cancelLabel: "Cancel",
      weekLabel: "W",
      customRangeLabel: "Custom Range",
      daysOfWeek: moment.weekdaysMin(),
      monthNames: moment.monthsShort(),
      firstDay: 1,
    },
  }),
  filters: {
    startDate(val) {
      return val ? moment(val).format("YYYY-MM-DD") : i18n.t("common.from");
    },
    endDate(val) {
      return val ? moment(val).format("YYYY-MM-DD") : i18n.t("common.to");
    },
  },
  // Map Getters
  computed: {
    ...mapGetters("operations", ["items", "loading", "pagination", "appInfo"]),
  },
  watch: {
    // watch search data
    query: function (newQ, oldQ) {
      if (newQ === "") {
        if (this.dateRange.startDate && this.dateRange.endDate) {
          this.searchData();
        } else {
          this.getData();
        }
      } else {
        this.searchData();
      }
    },
  },
  created() {
    this.getData();
    this.quotationPrefix = this.appInfo.quotationPrefix;
    this.clientPrefix = this.appInfo.clientPrefix;
  },
  methods: {
    // filter data for selected date range
    async updateValues() {
      this.dateRange.startDate = moment(this.dateRange.startDate).format(
        "YYYY-MM-DD"
      );
      this.dateRange.endDate = moment(this.dateRange.endDate).format(
        "YYYY-MM-DD"
      );
      this.searchData();
    },
    // refresh table
    refreshTable() {
      this.query = "";
      this.dateRange.startDate = null;
      this.dateRange.endDate = null;

      this.query === "" ? this.getData() : this.searchData();

      setTimeout(
        function () {
          this.dateRange.startDate = "";
          this.dateRange.endDate = "";
        }.bind(this),
        500
      );
    },
    // update per page count
    updatePerPager() {
      this.pagination.current_page = 1;
      this.query === "" ? this.getData() : this.searchData();
    },
    // get data
    async getData() {
      this.$store.state.operations.loading = true;
      let currentPage = this.pagination ? this.pagination.current_page : 1;
      await this.$store.dispatch("operations/fetchData", {
        path: "/api/quotations?page=",
        currentPage: currentPage + "&perPage=" + this.perPage,
      });
    },

    // Pagination
    async paginate() {
      this.query === "" ? this.getData() : this.searchData();
    },

    // Reset pagination
    async resetPagination() {
      this.pagination.current_page = 1;
    },

    // search data
    async searchData() {
      this.$store.state.operations.loading = true;
      let currentPage = this.pagination ? this.pagination.current_page : 1;
      await this.$store.dispatch("operations/searchData", {
        path: "/api/quotations/search",
        term: this.query,
        currentPage: currentPage + "&perPage=" + this.perPage,
        startDate: this.dateRange.startDate,
        endDate: this.dateRange.endDate,
      });
    },

    // Reload after search
    async reload() {
      this.query = "";
      await this.searchData();
    },

    // print table
    async print() {
      await this.$htmlToPaper("printMe");
    },

    // delete data
    async deleteData(slug) {
      Swal.fire({
        title: this.$t("common.delete_title"),
        text: this.$t("sales.quotations.index.delete_warning"),
        type: "warning",
        showCancelButton: true,
        confirmButtonText: this.$t("common.delete_confirm_text"),
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.$store
            .dispatch("operations/deleteData", {
              path: "/api/quotations/",
              slug: slug,
            })
            .then((response) => {
              if (response === true) {
                Swal.fire(
                  this.$t("common.deleted"),
                  this.$t("common.delete_success"),
                  "success"
                );
              } else {
                Swal.fire(
                  this.$t("common.failed"),
                  this.$t("sales.quotations.index.delete_failed"),
                  "warning"
                );
              }
            });
        }
      });
    },
  },
};
</script>
