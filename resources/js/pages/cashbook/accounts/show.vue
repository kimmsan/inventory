<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row no-print mb-2">
      <div class="w-100 text-right float-right">
        <div class="btn-group" v-if="allData">
          <a :href="'/account-transactions/pdf/' + allData.slug" v-tooltip="$t('common.export_table')"
            class="btn btn-primary">
            <i class="fas fa-download"></i> {{ $t("download") }}
          </a>
          <a href="#" @click="printWindow" class="btn btn-secondary"><i class="fas fa-print"></i> {{ $t("common.print")
          }}</a>
          <router-link v-if="$can('account-edit')" :to="{
            name: 'accounts.edit',
            params: { slug: allData.slug },
          }" class="btn btn-info">
            <i class="fas fa-edit" /> {{ $t("common.edit") }}
          </router-link>
          <router-link :to="{ name: 'accounts.index' }" class="btn btn-dark float-right">
            <i class="fas fa-long-arrow-alt-left" />
            {{ $t("common.back") }}
          </router-link>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="invoice p-3 mb-3 w-100">
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            <CompanyInfo />
          </div>
          <!-- /.col -->
          <div v-if="allData" class="col-sm-6 offset-sm-2 invoice-col float-right text-md-right">
            <h5 v-if="allData.date">
              {{ $t("cashbook.common.account_details") }}
            </h5>
            <strong>{{ $t("cashbook.common.bank_name") }}:</strong>
            {{ allData.bankName }}<br />
            <span v-if="allData.branchName"><strong>{{ $t("cashbook.common.branch_name") }}:</strong>
              {{ allData.branchName }}<br /></span>
            <span v-if="allData.accountNumber"><strong>{{ $t("cashbook.common.account_number") }}:</strong>
              {{ allData.accountNumber }}<br /></span>
            <span v-if="allData.accountNumber"><strong>{{ $t("common.created_at") }}:</strong>
              {{ allData.date | moment("Do MMM, YYYY") }}<br /></span>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
          <div v-if="pagination" class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h4>
                  <span>{{ totalCount }}</span>
                </h4>
                <p>{{ $t("cashbook.common.total_transactions") }}</p>
              </div>
              <div class="icon">
                <i class="fas fa-coins"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h4>{{ allData.totalCredits | withCurrency }}</h4>
                <p>{{ $t("cashbook.common.credit_amount") }}</p>
              </div>
              <div class="icon">
                <i class="fas fa-sign-in-alt"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
              <div class="inner">
                <h4>{{ allData.totalDebits | withCurrency }}</h4>
                <p>{{ $t("cashbook.common.debit_amount") }}</p>
              </div>
              <div class="icon">
                <i class="fas fa-sign-out-alt"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
              <div class="inner">
                <h4>{{ allData.availableBalance | withCurrency }}</h4>
                <p>{{ $t("common.available_balance") }}</p>
              </div>
              <div class="icon">
                <i class="fas fa-piggy-bank"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <table-loading v-show="loading" />
          <div class="table-responsive table-custom">
            <table class="table">
              <thead>
                <tr>
                  <th>{{ $t("common.s_no") }}</th>
                  <th>{{ $t("common.particular") }}</th>
                  <th>{{ $t("common.date") }}</th>
                  <th>{{ $t("common.credit") }}</th>
                  <th>{{ $t("common.debit") }}</th>
                  <th>{{ $t("common.balance") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-show="transactions.length" v-for="( data, i ) in  transactions " :key="i">
                  <td>{{ ++i }}</td>
                  <td>{{ data.reason }}</td>
                  <td>
                    <span v-if="data.transactionDate">{{
                      data.transactionDate | moment("Do MMM, YYYY")
                    }}</span>
                  </td>
                  <td>
                    <span v-if="data.type === 1">{{
                      data.amount | withCurrency
                    }}</span>
                    <span v-else>{{ 0 | withCurrency }}</span>
                  </td>

                  <td>
                    <span v-if="data.type === 1">{{ 0 | withCurrency }}</span>
                    <span v-else>{{ data.amount | withCurrency }}</span>
                  </td>
                  <td>{{ data.balance | withCurrency }}</td>
                </tr>
                <tr v-show="!loading && !transactions.length">
                  <td colspan="8">
                    <EmptyTable />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-show="allData.length" class="no-print callout callout-danger mt-4 w-100">
          <h5>{{ $t("cashbook.accounts.view.empty_transaction") }}</h5>
          <p>{{ $t("cashbook.accounts.view.empty_transaction_msg") }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("cashbook.accounts.view.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "cashbook.accounts.view.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "cashbook.accounts.view.breadcrumbs_first",
        url: "home",
      },
      {
        name: "cashbook.accounts.view.breadcrumbs_second",
        url: "accounts.index",
      },
      {
        name: "cashbook.accounts.view.breadcrumbs_active",
        url: "",
      },
    ],
    query: "",
    allData: "",
    transactions: [],
    perPage: 10,
    totalCount: 0,
  }),

  // Map Getters
  computed: {
    ...mapGetters("operations", ["items", "loading", "pagination"]),
  },
  watch: {
    // watch search data
    query: function (newQ) {
      if (newQ === "") {
        this.getTransactions();
      } else {
        this.searchTransactions();
      }
    },
  },

  created() {
    this.getAccount();
    this.getTransactions();
  },
  methods: {
    // update per page count
    updatePerPager() {
      this.pagination.current_page = 1;
      this.query === "" ? this.getTransactions() : this.searchTransactions();
    },

    // get the account
    async getAccount() {
      const { data } = await axios.get(
        window.location.origin + "/api/accounts/" + this.$route.params.slug
      );
      this.allData = data.data;
    },

    // get the supplier lc
    async getTransactions() {
      this.$store.state.operations.loading = true;
      const { data } = await axios.get(
        window.location.origin +
        "/api/accounts/transactions/" +
        this.$route.params.slug
      );
      let totalBalance = 0;
      this.transactions = data.data.map((transaction) => {
        totalBalance =
          transaction.type == 0
            ? totalBalance - transaction.amount
            : totalBalance + transaction.amount; // Debit subtracts, Credit adds
        return { ...transaction, balance: totalBalance };
      });
      this.totalCount = this.transactions.length;
      this.$store.state.operations.loading = false;
    },

    // search lc
    async searchTransactions() {
      this.$store.state.operations.loading = true;
      await this.$store.dispatch("operations/searchData", {
        term: this.query,
        path:
          "/api/accounts/transactions/" + this.$route.params.slug + "/search",
        currentPage: this.pagination.current_page + "&perPage=" + this.perPage,
      });
    },

    // pagination
    async paginate() {
      this.query === "" ? this.getTransactions() : this.searchTransactions();
    },

    // reset purchase pagination
    async resetPagination() {
      this.pagination.current_page = 1;
    },

    // reload purchases after search
    async reload() {
      this.query = "";
    },

    // print
    printWindow() {
      window.print();
    },
  },
};
</script>

