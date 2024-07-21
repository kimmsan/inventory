<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->

    <div class="row no-print mb-2">
      <div class="w-100 text-right float-right">
        <div class="btn-group" v-if="allData">
          <a @click="generatePDF()" href="#" class="btn btn-primary">
            <i class="fas fa-download"></i> {{ $t("download") }}
          </a>
          <a @click="printWindow()" href="#" class="btn btn-secondary">
            <i class="fas fa-print"></i> {{ $t("common.print") }}
          </a>
          <router-link v-if="$can('payroll-edit')" :to="{
            name: 'payroll.edit',
            params: { slug: allData.slug },
          }" class="btn btn-info">
            <i class="fas fa-edit" /> {{ $t("common.edit") }}
          </router-link>
          <router-link :to="{ name: 'payroll.index' }" class="btn btn-dark float-right">
            <i class="fas fa-long-arrow-alt-left" /> {{ $t("common.back") }}
          </router-link>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Main content -->
      <div class="invoice p-3 mb-3 w-100" id="content-to-pdf">
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            <CompanyInfo />
          </div>
          <!-- /.col -->
          <div class="col-sm-6 offset-sm-2 invoice-col float-right text-md-right">
            <div v-if="allData.employee && allData.employee.department">
              <h5>{{ $t("common.employee_details") }}</h5>
              <strong>{{ $t("common.emp_id") }}:</strong>
              {{ allData.employee.empID | withPrefix(employeePrefix) }}<br />
              <strong>{{ $t("common.emp_name") }}:</strong>
              {{ allData.employee.name }}<br />
              <strong>{{ $t("common.department") }}:</strong>
              {{ allData.employee.department.name }}<br />
              <strong>{{ $t("common.designation") }}:</strong>
              {{ allData.employee.designation }}<br />
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- Table row -->
        <div class="row" v-if="allData.employee &&
          allData.transaction &&
          allData.transaction.cashbook_account
          ">
          <div class="col-12">
            <strong class="mt-3 mb-2 d-block">{{ $t("payroll.view.page_title") }}:</strong>
            <div class="table-responsive table-custom">
              <table class="table">
                <thead>
                  <tr>
                    <th v-if="allData.image">{{ $t("common.image") }}</th>
                    <th v-if="allData.employee">
                      {{ $t("common.employee") }}
                    </th>
                    <th v-if="allData.salaryMonth">
                      {{ $t("common.month") }}
                    </th>
                    <th v-if="allData.transaction">
                      {{ $t("common.paid") }}
                    </th>
                    <th v-if="allData.transaction &&
                      allData.transaction.cashbook_account
                      ">
                      {{ $t("common.account") }}
                    </th>
                    <th v-if="allData.transaction.cheque_no">
                      {{ $t("common.cheque_no") }}
                    </th>
                    <th v-if="allData.deductionReason">
                      {{ $t("payroll.common.deduction_reason") }}
                    </th>
                    <th v-if="allData.note">{{ $t("common.note") }}</th>
                    <th>{{ $t("common.status") }}</th>
                    <th v-if="allData.salaryDate">{{ $t("common.date") }}</th>
                    <th v-if="allData.createdBy" class="text-right">
                      {{ $t("common.created_by") }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td v-if="allData.image">
                      <a href="#" id="show-modal" @click="showModal = true">
                        <img :src="allData.image" class="rounded preview-sm" loading="lazy" />
                      </a>
                    </td>
                    <td v-if="allData.employee">
                      {{ allData.employee.name }}
                    </td>
                    <td v-if="allData.salaryMonth">
                      {{ allData.salaryMonth }}
                    </td>
                    <td v-if="allData.transaction">
                      {{ allData.transaction.amount | withCurrency }}
                    </td>
                    <td v-if="allData.transaction &&
                      allData.transaction.cashbook_account
                      ">
                      {{
                        allData.transaction.cashbook_account.account_number
                      }}
                    </td>
                    <td v-if="allData.transaction.cheque_no">
                      {{ allData.transaction.cheque_no }}
                    </td>
                    <td v-if="allData.deductionReason">
                      {{ allData.deductionReason }}
                    </td>
                    <td v-if="allData.note">{{ allData.note }}</td>
                    <td>
                      <span v-if="allData.status === 1" class="badge bg-success">{{ $t("common.active") }}</span>
                      <span v-else class="badge bg-danger">{{
                        $t("common.in_active")
                      }}</span>
                    </td>
                    <td v-if="allData.salaryDate">
                      {{ allData.salaryDate | moment("Do MMM, YYYY") }}
                    </td>
                    <td class="text-right">
                      <span v-if="allData.createdBy">{{
                        allData.createdBy
                      }}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- /.row -->
        <div class="row mb-5 mt-4">
          <div class="offset-xl-8 col-lg-12 col-xl-4 text-xl-right" v-if="allData.employee && allData.transaction">
            <div class="table-responsive table-custom table-border-y-0">
              <table class="table">
                <tbody>
                  <tr class="bg-gray-light">
                    <th>{{ $t("payroll.common.present_salary") }}:</th>
                    <td>{{ allData.employee.totalSalary | withCurrency }}</td>
                  </tr>
                  <tr>
                    <th>{{ $t("payroll.common.deduction_amount") }}:</th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ allData.deductionAmount | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t("payroll.common.mobile_bill") }}:</th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.mobileBill | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t("payroll.common.food_bill") }}:</th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.foodBill | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t("payroll.common.bonus") }}:</th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.bonus | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t("payroll.common.commission") }}:</th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.commission | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t("payroll.common.advance") }}:</th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.advance | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t("payroll.common.festival_bonus") }}:</th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.festivalBonus | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t("payroll.common.travel_allowance") }}:</th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.travelAllowance | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t("payroll.common.others") }}:</th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.others | withCurrency }}
                    </td>
                  </tr>
                  <tr v-if="allData.transaction" class="bg-indigo-light">
                    <th>{{ $t("common.total") }}:</th>
                    <td>
                      <span class="equal-sign">=</span>
                      {{ allData.transaction.amount | withCurrency }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
    </div>
    <!-- use the modal component, pass in the prop -->
    <Modal v-if="showModal" @close="showModal = false">
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
import html2pdf from "html2pdf.js";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("payroll.view.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "payroll.view.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "payroll.view.breadcrumbs_first",
        url: "home",
      },
      {
        name: "payroll.view.breadcrumbs_second",
        url: "payroll.index",
      },
      {
        name: "payroll.view.breadcrumbs_active",
        url: "",
      },
    ],
    showModal: false,
    allData: "",
    employeePrefix: "",
  }),
  computed: mapGetters({
    appInfo: "operations/appInfo",
  }),
  created() {
    this.getPayroll();
    this.employeePrefix = this.appInfo.employeePrefix;
  },
  methods: {
    // get the payroll
    async getPayroll() {
      const { data } = await axios.get(
        window.location.origin + "/api/payroll/" + this.$route.params.slug
      );
      this.allData = data.data;
    },

    // print
    printWindow() {
      window.print();
    },

    // download pdf
    generatePDF() {
      // Get the HTML content to be converted
      const element = document.getElementById("content-to-pdf");
      // Options for PDF generation
      const options = {
        margin: 5,
        filename: "Payroll-" + this.$route.params.slug + ".pdf",
        image: { type: "jpeg", quality: 0.98 },
        pagebreak: { mode: "avoid-all", before: "#page-break" },
        html2canvas: { scale: 2 },
        jsPDF: { unit: "mm", format: "a4", orientation: "landscape" },
      };
      // Generate PDF from HTML content
      html2pdf().from(element).set(options).save();
    },
  },
};
</script>
