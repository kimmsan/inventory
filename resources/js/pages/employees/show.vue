<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row">
      <div
        class="col-md-12 m-auto"
        :class="
          !$can('payroll-list') && !$can('increment-list')
            ? 'col-lg-6'
            : 'col-lg-3'
        "
      >
        <!-- Profile Image -->
        <div class="card">
          <div class="card-body box-profile">
            <div class="text-center mb-2">
              <a
                v-if="allData.image"
                href="#"
                id="show-modal"
                @click="previewModal(allData.image)"
              >
                <img
                  :src="allData.image"
                  class="profile-user-img img-fluid img-circle"
                  loading="lazy"
                />
              </a>
              <div v-else class="bg-secondary no-preview-lg">
                <small>{{ $t("common.no_preview") }}</small>
              </div>
            </div>
            <h3 class="profile-username text-center">{{ allData.name }}</h3>
            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <strong>{{ $t("common.emp_id") }}</strong>
                <span class="float-right">{{
                  allData.empID | withPrefix(employeePrefix)
                }}</span>
              </li>
              <li v-if="allData.department" class="list-group-item">
                <strong>{{ $t("common.department") }}</strong>
                <span class="float-right">{{ allData.department.name }}</span>
              </li>
              <li v-if="allData.designation" class="list-group-item">
                <strong>{{ $t("common.designation") }}</strong>
                <span class="float-right">{{ allData.designation }}</span>
              </li>
              <li v-if="allData.mobileNumber" class="list-group-item">
                <strong>{{ $t("common.contact_number") }}</strong>
                <span class="float-right">{{ allData.mobileNumber }}</span>
              </li>
              <li v-if="allData.email" class="list-group-item">
                <strong>{{ $t("common.email") }}</strong>
                <span class="float-right">{{ allData.email }}</span>
              </li>
              <li v-if="allData.salary" class="list-group-item">
                <strong>{{ $t("employees.common.basic_salary") }}</strong>
                <span class="float-right">{{
                  allData.salary | withCurrency
                }}</span>
              </li>
              <li v-if="allData.totalSalary" class="list-group-item">
                <strong>{{ $t("employees.common.current_salary") }}</strong>
                <span class="float-right">{{
                  allData.totalSalary | withCurrency
                }}</span>
              </li>
              <li v-if="allData.commission" class="list-group-item">
                <strong>{{ $t("common.commission") }}</strong>
                <span class="float-right">{{ allData.commission }}%</span>
              </li>

              <li v-if="allData.gender" class="list-group-item">
                <strong>{{ $t("employees.common.gender") }}</strong>
                <span class="float-right">{{ allData.gender }}</span>
              </li>
              <li v-if="allData.bloodGroup" class="list-group-item">
                <strong>{{ $t("employees.common.blood_group") }}</strong>
                <span class="float-right">{{ allData.bloodGroup }}</span>
              </li>
              <li v-if="allData.religion" class="list-group-item">
                <strong>{{ $t("employees.common.religion") }}</strong>
                <span class="float-right">{{ allData.religion }}</span>
              </li>
              <li v-if="allData.birthDate" class="list-group-item">
                <strong>{{ $t("employees.common.birth_date") }}</strong>
                <span class="float-right">{{
                  allData.birthDate | moment("Do MMM, YYYY")
                }}</span>
              </li>
              <li v-if="allData.joiningDate" class="list-group-item">
                <strong>{{ $t("employees.common.join_date") }}</strong>
                <span class="float-right">{{
                  allData.joiningDate | moment("Do MMM, YYYY")
                }}</span>
              </li>
              <li v-if="allData.appointmentDate" class="list-group-item">
                <strong>{{ $t("employees.common.appointment_date") }}</strong>
                <span class="float-right">{{
                  allData.appointmentDate | moment("Do MMM, YYYY")
                }}</span>
              </li>
              <li v-if="allData.address" class="list-group-item">
                <strong>{{ $t("common.address") }}</strong>
                <span class="float-right">{{ allData.address }}</span>
              </li>
              <li class="list-group-item">
                <strong>{{ $t("employees.common.allow_login") }}</strong>
                <span v-if="allData.user" class="float-right">{{
                  $t("common.yes")
                }}</span>
                <span v-else class="float-right">{{ $t("common.no") }}</span>
              </li>
              <li
                v-if="allData.user && allData.user.role"
                class="list-group-item"
              >
                <strong>{{ $t("common.role") }}</strong>
                <span class="float-right">{{ allData.user.role.name }}</span>
              </li>
            </ul>
            <span
              v-if="allData.status === 1"
              class="btn-block btn bg-success"
              >{{ $t("common.active") }}</span
            >
            <span v-else class="badge bg-danger">{{
              $t("common.in_active")
            }}</span>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
      <div
        v-if="$can('payroll-list') || $can('increment-list')"
        class="col-md-12 col-lg-9"
      >
        <div class="card">
          <div class="card-header p-2">
            <div class="row">
              <div class="col-md-8">
                <ul class="nav nav-pills">
                  <li v-if="$can('payroll-list')" class="nav-item">
                    <a
                      class="nav-link active"
                      href="#payroll"
                      data-toggle="tab"
                      >{{ $t("sidebar.payroll") }}</a
                    >
                  </li>
                  <li v-if="$can('increment-list')" class="nav-item">
                    <a
                      @click="getEmployeeSalIncrements"
                      class="nav-link"
                      href="#increment"
                      data-toggle="tab"
                      >{{ $t("employees.common.increment_history") }}</a
                    >
                  </li>
                </ul>
              </div>
              <div class="col-md-4">
                <router-link
                  :to="{ name: 'employees.index' }"
                  class="btn btn-dark float-right"
                >
                  <i class="fas fa-long-arrow-alt-left" />
                  {{ $t("common.back") }}
                </router-link>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content">
              <div
                v-if="$can('payroll-list')"
                class="tab-pane active"
                id="payroll"
              >
                <div>
                  <div class="card-body p-0 position-relative">
                    <div class="col-md-12 large-serach-box">
                      <search
                        v-model="query"
                        @reset-pagination="resetPagination"
                        @reload="reload"
                      />
                    </div>
                    <table-loading v-show="loading" />
                    <div class="table-responsive table-custom mt-3">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>{{ $t("common.s_no") }}</th>
                            <th>{{ $t("payroll.common.salary_month") }}</th>
                            <th>{{ $t("payroll.common.salary_date") }}</th>
                            <th>{{ $t("common.account") }}</th>
                            <th>{{ $t("common.total_paid") }}</th>
                            <th>{{ $t("common.status") }}</th>
                            <th
                              v-if="
                                $can('payroll-edit') ||
                                $can('payroll-view') ||
                                $can('payroll-delete')
                              "
                              class="text-right no-print"
                            >
                              {{ $t("common.action") }}
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-show="items.length"
                            v-for="(data, i) in items"
                            :key="i"
                          >
                            <td>
                              <span v-if="pagination.current_page > 1">
                                {{
                                  pagination.per_page *
                                    (pagination.current_page - 1) +
                                  (i + 1)
                                }}
                              </span>
                              <span v-else>{{ i + 1 }}</span>
                            </td>
                            <td>{{ data.salaryMonth }}</td>
                            <td>
                              <span v-if="data.salaryDate">{{
                                data.salaryDate | moment("Do MMM, YYYY")
                              }}</span>
                            </td>
                            <td>
                              <span
                                v-if="
                                  data.transaction &&
                                  data.transaction.cashbook_account
                                "
                                >{{
                                  data.transaction.cashbook_account
                                    .account_number
                                }}</span
                              >
                            </td>
                            <td>
                              <span v-if="data.transaction">{{
                                data.transaction.amount | withCurrency
                              }}</span>
                            </td>
                            <td>
                              <span
                                v-if="data.status === 1"
                                class="badge bg-success"
                                >{{ $t("common.active") }}</span
                              >
                              <span v-else class="badge bg-danger">{{
                                $t("common.in_active")
                              }}</span>
                            </td>
                            <td
                              v-if="
                                $can('payroll-edit') ||
                                $can('payroll-view') ||
                                $can('payroll-delete')
                              "
                              class="text-right"
                            >
                              <div class="btn-group">
                                <router-link
                                  v-if="$can('payroll-view')"
                                  v-tooltip="$t('common.view')"
                                  :to="{
                                    name: 'payroll.show',
                                    params: { slug: data.slug },
                                  }"
                                  class="btn btn-primary btn-sm"
                                >
                                  <i class="fas fa-eye" />
                                </router-link>
                                <router-link
                                  v-if="$can('payroll-edit')"
                                  v-tooltip="$t('common.edit')"
                                  :to="{
                                    name: 'payroll.edit',
                                    params: { slug: data.slug },
                                  }"
                                  class="btn btn-info btn-sm"
                                >
                                  <i class="fas fa-edit" />
                                </router-link>
                                <a
                                  v-if="$can('payroll-delete')"
                                  v-tooltip="$t('common.delete')"
                                  href="#"
                                  class="btn btn-danger btn-sm"
                                  @click="deletePayroll(data.slug)"
                                >
                                  <i class="fas fa-trash" />
                                </a>
                              </div>
                            </td>
                          </tr>
                          <tr v-show="!loading && !items.length">
                            <td colspan="8">
                              <EmptyTable />
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- NEW PAGINATION -->
                  <div
                    v-if="pagination && pagination.total > 0"
                    class="dtable-footer"
                  >
                    <div class="form-group row display-per-page">
                      <label>{{ $t("per_page") }} </label>
                      <div>
                        <select
                          @change="updatePerPager('payroll')"
                          v-model="perPage"
                          class="form-control form-control-sm ml-1"
                        >
                          <!-- options component -->
                          <option
                            v-for="option in options"
                            :value="option.value"
                          >
                            {{ option.text }}
                          </option>
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
                  <!-- NEW PAGINATION END -->
                </div>
              </div>
              <div
                v-if="$can('increment-list')"
                class="tab-pane"
                id="increment"
              >
                <div>
                  <div class="card-body p-0 position-relative">
                    <div class="col-md-12 large-serach-box">
                      <search
                        v-model="salIncreQuery"
                        @reset-pagination="resetSalIncrePagination"
                        @reload="salIncreReload"
                      />
                    </div>
                    <table-loading v-show="salIncreLoading" />
                    <div class="table-responsive table-custom mt-3">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>{{ $t("common.s_no") }}</th>
                            <th>
                              {{ $t("employees.common.increment_reason") }}
                            </th>
                            <th>{{ $t("employees.common.basic_salary") }}</th>
                            <th>
                              {{ $t("employees.common.increment_amount") }}
                            </th>
                            <th>{{ $t("payroll.common.present_salary") }}</th>
                            <th>{{ $t("employees.common.increment_date") }}</th>
                            <th>{{ $t("common.status") }}</th>
                            <th
                              v-if="
                                $can('increment-edit') ||
                                $can('increment-view') ||
                                $can('increment-delete')
                              "
                              class="text-right no-print"
                            >
                              {{ $t("common.action") }}
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-show="allIncrements.length"
                            v-for="(data, i) in allIncrements"
                            :key="i"
                          >
                            <td>
                              <span
                                v-if="
                                  salIncrePagination &&
                                  salIncrePagination.current_page > 1
                                "
                              >
                                {{
                                  salIncrePagination.per_page *
                                    (salIncrePagination.current_page - 1) +
                                  (i + 1)
                                }}
                              </span>
                              <span v-else>{{ i + 1 }}</span>
                            </td>
                            <td>
                              <router-link
                                :to="{
                                  name: 'increments.show',
                                  params: { slug: data.slug },
                                }"
                              >
                                {{ data.reason }}
                              </router-link>
                            </td>
                            <td>
                              <span v-if="data.employee"
                                >{{ data.employee.salary | withCurrency }}
                              </span>
                            </td>
                            <td>{{ data.incrementAmount | withCurrency }}</td>
                            <td>
                              <span v-if="data.employee">
                                {{
                                  (data.employee.salary + data.incrementAmount)
                                    | withCurrency
                                }}
                              </span>
                            </td>
                            <td>
                              <span v-if="data.incrementDate">{{
                                data.incrementDate | moment("Do MMM, YYYY")
                              }}</span>
                            </td>
                            <td>
                              <span
                                v-if="data.status === 1"
                                class="badge bg-success"
                                >{{ $t("common.active") }}</span
                              >
                              <span v-else class="badge bg-danger">{{
                                $t("common.in_active")
                              }}</span>
                            </td>
                            <td
                              v-if="
                                $can('increment-edit') ||
                                $can('increment-view') ||
                                $can('increment-delete')
                              "
                              class="text-right"
                            >
                              <div class="btn-group">
                                <router-link
                                  v-if="$can('increment-view')"
                                  v-tooltip="$t('common.view')"
                                  :to="{
                                    name: 'increments.show',
                                    params: { slug: data.slug },
                                  }"
                                  class="btn btn-primary btn-sm"
                                >
                                  <i class="fas fa-eye" />
                                </router-link>
                                <router-link
                                  v-if="$can('increment-edit')"
                                  v-tooltip="$t('common.edit')"
                                  :to="{
                                    name: 'increments.edit',
                                    params: { slug: data.slug },
                                  }"
                                  class="btn btn-info btn-sm"
                                >
                                  <i class="fas fa-edit" />
                                </router-link>
                                <a
                                  v-if="$can('increment-delete')"
                                  v-tooltip="$t('common.delete')"
                                  href="#"
                                  class="btn btn-danger btn-sm"
                                  @click="deleteIncrement(data.slug)"
                                >
                                  <i class="fas fa-trash" />
                                </a>
                              </div>
                            </td>
                          </tr>
                          <tr v-show="!loading && !allIncrements.length">
                            <td colspan="8">
                              <EmptyTable />
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!--                  <div
                    v-if="salIncrePagination.last_page > 1"
                    class="mt-3 clearfix"
                  >
                    <pagination
                      :pagination="salIncrePagination"
                      :offset="5"
                      class="justify-flex-end"
                      @paginate="salIncrePaginate"
                    />
                  </div>-->
                  <!-- NEW PAGINATION -->
                  <div
                    v-if="salIncrePagination && salIncrePagination.total > 0"
                    class="dtable-footer"
                  >
                    <div class="form-group row display-per-page">
                      <label>{{ $t("per_page") }} </label>
                      <div>
                        <select
                          @change="updatePerPager('increment-history')"
                          v-model="perPage"
                          class="form-control form-control-sm ml-1"
                        >
                          <!-- options component -->
                          <option
                            v-for="option in options"
                            :value="option.value"
                          >
                            {{ option.text }}
                          </option>
                        </select>
                      </div>
                    </div>
                    <!-- pagination-start -->
                    <pagination
                      v-if="
                        salIncrePagination && salIncrePagination.last_page > 1
                      "
                      :pagination="salIncrePagination"
                      :offset="5"
                      class="justify-flex-end"
                      @paginate="salIncrePaginate"
                    />
                    <!-- pagination-end -->
                  </div>
                  <!-- NEW PAGINATION END -->
                </div>
              </div>
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.nav-tabs-custom -->
      </div>
      <!-- /.col -->
    </div>
    <!-- use the modal component, pass in the prop -->
    <Modal v-if="showModal" @close="previewModal()">
      <h5 slot="header">{{ $t("common.modal_header") }}</h5>
      <div class="w-100" slot="body">
        <img :src="allData.image" class="rounded img-fluid" loading="lazy" />
      </div>
    </Modal>
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("employees.list.view.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "employees.list.view.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "employees.list.view.breadcrumbs_first",
        url: "home",
      },
      {
        name: "employees.list.view.breadcrumbs_second",
        url: "employees.index",
      },
      {
        name: "employees.list.view.breadcrumbs_active",
        url: "",
      },
    ],
    allIncrements: "",
    salIncreLoading: false,
    salIncrePagination: "",
    url: null,
    showModal: false,
    allData: "",
    query: "",
    salIncreQuery: "",
    employeePrefix: "",
    perPage: 10,
    options: [
      { value: "10", text: "10" },
      { value: "25", text: "25" },
      { value: "50", text: "50" },
      { value: "100", text: "100" },
    ],
  }),
  // Map Getters
  computed: {
    ...mapGetters("operations", ["items", "loading", "pagination", "appInfo"]),
  },
  watch: {
    // watch invoice search data
    query: function (newQ, oldQ) {
      if (newQ === "") {
        this.getEmployeePayroll();
      } else {
        this.searchEmployeePayroll();
      }
    },
    // watch salary increment search data
    salIncreQuery: function (newQ, oldQ) {
      if (newQ === "") {
        this.getEmployeeSalIncrements();
      } else {
        this.searchEmployeeSalIncrements();
      }
    },
  },

  created() {
    this.getEmployee();
    this.getEmployeePayroll();
    this.employeePrefix = this.appInfo.employeePrefix;
    Fire.$on("AfterDelete", () => {
      this.getEmployeePayroll();
      this.getEmployeeSalIncrements();
    });
  },
  methods: {
    // get the employee
    async getEmployee() {
      const { data } = await axios.get(
        window.location.origin + "/api/employees/" + this.$route.params.slug
      );
      this.allData = data.data;
    },

    // update per page count
    updatePerPager(tabName) {
      this.pagination.current_page = 1;
      this.salIncrePagination.hasOwnProperty("current_page")
        ? (this.salIncrePagination.current_page = 1)
        : "";

      switch (tabName) {
        case "payroll":
          this.query === ""
            ? this.getEmployeePayroll()
            : this.searchEmployeePayroll();
          break;
        case "increment-history":
          this.query === ""
            ? this.getEmployeeSalIncrements()
            : this.searchEmployeeSalIncrements();
          break;
      }
    },

    // get the employee payroll
    async getEmployeePayroll() {
      this.$store.state.operations.loading = true;
      await this.$store.dispatch("operations/fetchData", {
        path: "/api/employee-payroll/" + this.$route.params.slug + "?page=",
        currentPage: this.pagination.current_page + "&perPage=" + this.perPage,
      });
    },

    // search employee payroll
    async searchEmployeePayroll() {
      this.$store.state.operations.loading = true;
      await this.$store.dispatch("operations/searchData", {
        term: this.query,
        path: "/api/employee-payroll/" + this.$route.params.slug + "/search/",
        currentPage: this.pagination.current_page + "&perPage=" + this.perPage,
      });
    },

    // pagination
    async paginate() {
      this.query === ""
        ? this.getEmployeePayroll()
        : this.searchEmployeePayroll();
    },

    // reset purchase pagination
    async resetPagination() {
      this.pagination.current_page = 1;
    },

    // reload purchases after search
    async reload() {
      this.query = "";
    },

    // get the employee salary increments
    async getEmployeeSalIncrements() {
      this.salIncreLoading = true;
      let currentPage = this.allIncrements
        ? this.salIncrePagination.current_page
        : 1;
      const { data } = await axios.get(
        window.location.origin +
          "/api/employee-increments/" +
          this.$route.params.slug +
          "?page=" +
          currentPage +
          "&perPage=" +
          this.perPage
      );
      this.allIncrements = data.data;
      this.salIncrePagination = data.meta;
      this.salIncreLoading = false;
    },

    // search employee salary increments
    async searchEmployeeSalIncrements() {
      this.salIncreLoading = true;
      let currentPage = this.allIncrements
        ? this.salIncrePagination.current_page
        : 1;
      const { data } = await axios.get(
        window.location.origin +
          "/api/employee-increments/" +
          this.$route.params.slug +
          "/search/" +
          this.salIncreQuery +
          "?page=" +
          currentPage +
          "&perPage=" +
          this.perPage
      );
      this.allIncrements = data.data;
      this.salIncrePagination = data.meta;
      this.salIncreLoading = false;
    },

    // salary increments pagination
    async salIncrePaginate() {
      this.salIncreQuery === ""
        ? this.getEmployeeSalIncrements()
        : this.searchEmployeeSalIncrements();
    },

    // reset increments pagination
    async resetSalIncrePagination() {
      this.salIncrePagination.current_page = 1;
    },

    // Reload increments after search
    async salIncreReload() {
      this.salIncreQuery = "";
    },

    // print
    printWindow() {
      window.print();
    },

    // dispaly modal
    previewModal(image) {
      this.imagePath = image;
      if (this.showModal) {
        return (this.showModal = false);
      }
      return (this.showModal = true);
    },

    // calcualte total paid
    calculateTotalPaid() {
      let totalPaid = 0;
      this.allData.loans.forEach(function (loan) {
        totalPaid += Number(loan.totalPaid);
      });
      return totalPaid;
    },

    // delete payroll
    async deletePayroll(slug) {
      Swal.fire({
        title: this.$t("common.delete_title"),
        text: this.$t("payroll.index.delete_warning"),
        type: "warning",
        showCancelButton: true,
        confirmButtonText: this.$t("common.delete_confirm_text"),
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.$store
            .dispatch("operations/deleteData", {
              path: "/api/payroll/",
              slug: slug,
            })
            .then((response) => {
              if (response === true) {
                Swal.fire(
                  this.$t("common.deleted"),
                  this.$t("common.delete_success"),
                  "success"
                );
                Fire.$emit("AfterDelete");
              } else {
                Swal.fire(
                  this.$t("common.failed"),
                  this.$t("common.delete_failed"),
                  "warning"
                );
              }
            });
        }
      });
    },

    // delete increment
    async deleteIncrement(slug) {
      Swal.fire({
        title: this.$t("common.delete_title"),
        text: this.$t("employees.increments.index.delete_warning"),
        type: "warning",
        showCancelButton: true,
        confirmButtonText: this.$t("common.delete_confirm_text"),
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.$store
            .dispatch("operations/deleteData", {
              path: "/api/increments/",
              slug: slug,
            })
            .then((response) => {
              if (response === true) {
                Swal.fire(
                  this.$t("common.deleted"),
                  this.$t("common.delete_success"),
                  "success"
                );
                Fire.$emit("AfterDelete");
              } else {
                Swal.fire(
                  this.$t("common.failed"),
                  this.$t("employees.increments.index.delete_failed"),
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

