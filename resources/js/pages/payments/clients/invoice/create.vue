<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row">
      <div class="col-lg-12 col-xl-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              {{ $t("payments.clients.invoice.create.form_title") }}
            </h3>
            <router-link :to="{ name: 'invoicePayments.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t("common.back") }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="savePayment" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row" v-if="items">
                <div class="form-group col-md-12">
                  <label for="client">{{ $t("common.client") }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.client" :options="items" label="name"
                    :class="{ 'is-invalid': form.errors.has('client') }" name="client"
                    :placeholder="$t('common.client_placeholder')" @input="getInvoices" />
                  <has-error :form="form" field="client" />
                </div>
              </div>
              <div v-if="form.client" class="row">
                <div class="form-group col-md-4">
                  <label for="clientInvoiceTotal">{{
                    $t("common.invoice_total")
                  }}</label>
                  <input id="clientInvoiceTotal" v-model="form.client.clientInvoiceTotal" type="text" class="form-control"
                    name="clientInvoiceTotal" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="clientTotalPaid">{{
                    $t("common.total_paid")
                  }}</label>
                  <input id="clientTotalPaid" v-model="form.client.clientTotalPaid" type="text" class="form-control"
                    name="clientTotalPaid" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="clientDue">{{ $t("common.total_due") }}</label>
                  <input id="clientDue" v-model="form.client.clientDue" type="text" class="form-control" name="clientDue"
                    readonly />
                </div>
              </div>
              <div class="row mb-3" v-if="form.client && invoices">
                <div class="form-group col-md-12">
                  <label for="invoice">{{ $t("payments.common.select_invoice") }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.invoice" :options="invoices" label="label"
                    :class="{ 'is-invalid': form.errors.has('invoice') }" name="invoice" :placeholder="$t('payments.common.select_invoice_placeholder')
                      " @input="storeInvoice(form.invoice)" />
                  <has-error :form="form" field="invoice" />
                </div>
              </div>

              <div v-if="form.errors.errors && form.errors.errors.selectedInvoices" class="col-md-11 m-auto">
                <div v-for="(msg, i) in form.errors.errors.selectedInvoices" :key="i" class="callout callout-danger">
                  <p><i class="icon fas fa-ban"></i> {{ msg }}</p>
                </div>
              </div>

              <div v-if="form.selectedInvoices" class="col-md-11 m-auto">
                <div v-for="(item, i) in form.selectedInvoices" :key="i" class="card bg-light border-dark mb-3">
                  <div class="card-header">
                    {{ item.invoiceNo }} {{ $t("common.invoice_details") }}
                    <button type="button" class="btn btn-danger float-right" @click="removeItem(item)">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="form-group col-md-3">
                        <label for="invoiceNumber">{{
                          $t("common.invoice_no")
                        }}</label>
                        <input type="text" :id="`invoiceNumber-${++i}`" class="form-control" :value="item.invoiceNo"
                          readonly />
                      </div>
                      <div class="form-group col-md-3">
                        <label for="invoiceTotal">{{
                          $t("common.invoice_total")
                        }}</label>
                        <input type="text" :id="`invoiceTotal-${i}`" class="form-control" :value="item.invoiceTotal"
                          readonly />
                      </div>
                      <div class="form-group col-md-3">
                        <label for="invoiceDue">{{
                          $t("common.invoice_due")
                        }}</label>
                        <input type="text" :id="`invoiceDue-${i}`" class="form-control" :value="item.newDue" readonly />
                      </div>
                      <div class="form-group col-md-3">
                        <label for="paidAmount">{{
                          $t("common.paid_amount")
                        }}</label>
                        <input type="number" step="any" class="form-control" :id="`paidAmount-${i}`"
                          :placeholder="$t('common.paid_amount_placeholder')"
                          @change="updateArray($event.target.value, i - 1)" @keyup="
                            updateArray(
                              $event.target.value,
                              'paidAmount',
                              i - 1
                            )
                            " required min="1" :max="item.originalDue" value="1" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-if="accounts" class="row">
                <div class="form-group col-md-6">
                  <label for="totalPayment">{{ $t("payments.common.total_payment") }}
                    <span class="required">*</span></label>
                  <input id="totalPayment" v-model="form.totalPayment" type="number" step="any" class="form-control"
                    name="totalPayment" readonly />
                </div>
                <div class="form-group col-md-6">
                  <label for="account">{{ $t("common.account") }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.account" :options="accounts" label="label"
                    :class="{ 'is-invalid': form.errors.has('account') }" name="account"
                    :placeholder="$t('common.account_placeholder')" />
                  <has-error :form="form" field="account" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-3">
                  <label for="chequeNo">{{ $t("common.cheque_no") }}</label>
                  <input type="text" v-model="form.chequeNo" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('chequeNo') }" id="chequeNo"
                    :placeholder="$t('common.cheque_placeholder')" />
                  <has-error :form="form" field="chequeNo" />
                </div>
                <div class="form-group col-md-3">
                  <label for="receiptNo">{{ $t("common.receipt_no") }}</label>
                  <input type="text" v-model="form.receiptNo" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('receiptNo') }" id="receiptNo"
                    :placeholder="$t('common.receipt_no_placeholder')" />
                  <has-error :form="form" field="receiptNo" />
                </div>
                <div class="form-group col-md-3">
                  <label for="paymentDate">{{
                    $t("common.payment_date")
                  }}</label>
                  <input id="paymentDate" v-model="form.paymentDate" type="date" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('paymentDate') }" name="paymentDate" />
                  <has-error :form="form" field="paymentDate" />
                </div>
                <div class="form-group col-md-3">
                  <label for="status">{{ $t("common.status") }}</label>
                  <select id="status" v-model="form.status" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('status') }">
                    <option value="1">{{ $t("common.active") }}</option>
                    <option value="0">{{ $t("common.in_active") }}</option>
                  </select>
                  <has-error :form="form" field="status" />
                </div>
              </div>
              <div class="form-group">
                <label for="note">{{ $t("common.note") }}</label>
                <textarea id="note" v-model="form.note" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('note') }" :placeholder="$t('common.note_placeholder')" />
                <has-error :form="form" field="note" />
              </div>
              <div class="form-group col-12 d-flex flex-wrap">
                <div class="pr-5">
                  <toggle-button v-model="form.isSendEmail" :disabled="isDemoMode" />
                  {{ $t("Send Email Notification") }}
                </div>
              </div>
              <div class="form-group col-12 d-flex flex-wrap">
                <div class="pr-5">
                  <toggle-button v-model="form.isSendSMS" :disabled="isDemoMode" />
                  {{ $t("Send SMS Notification") }}
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <v-button :loading="form.busy" class="btn btn-primary">
                <i class="fas fa-save" /> {{ $t("common.save") }}
              </v-button>
              <button type="reset" class="btn btn-secondary float-right" @click="form.reset()">
                <i class="fas fa-power-off" /> {{ $t("common.reset") }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import axios from "axios";
import { mapGetters } from "vuex";
import { ToggleButton } from "vue-js-toggle-button";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("payments.clients.invoice.create.page_title") };
  },
  components: {
    ToggleButton,
  },
  data: () => ({
    breadcrumbsCurrent: "payments.clients.invoice.create.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "payments.clients.invoice.create.breadcrumbs_first",
        url: "home",
      },
      {
        name: "payments.clients.invoice.create.breadcrumbs_second",
        url: "",
      },
      {
        name: "payments.clients.invoice.create.breadcrumbs_third",
        url: "invoicePayments.index",
      },
      {
        name: "payments.clients.invoice.create.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      client: "",
      selectedInvoices: [],
      totalPayment: 0,
      paymentDate: new Date().toISOString().slice(0, 10),
      note: "",
      status: 1,
      account: "",
      chequeNo: "",
      receiptNo: "",
      isSendEmail: false,
      isSendSMS: false,
    }),
    accounts: "",
    invoices: "",
    isDemoMode: window.config.isDemoMode,
  }),
  computed: {
    ...mapGetters("operations", ["items", "appInfo"]),
  },
  created() {
    this.getClients();
    this.getAccounts();
  },
  methods: {
    // get all clients
    async getClients() {
      await this.$store.dispatch("operations/allData", {
        path: "/api/all-clients",
      });
    },

    // get invoices
    async getInvoices() {
      this.form.selectedInvoices = [];
      if (this.form.client) {
        this.form.clientBalance = this.form.client.clientTotalAdvance;
        const { data } = await axios.get(
          window.location.origin +
          "/api/client/" +
          this.form.client.slug +
          "/invoices"
        );
        this.invoices = data.invoices;
        this.form.client = data.client;
      }
    },

    // get accounts
    async getAccounts() {
      const { data } = await axios.get(
        window.location.origin + "/api/all-accounts"
      );
      this.accounts = data.data;

      // assign default account
      if (this.accounts && this.accounts.length > 0) {
        let defaultAccountSlug = this.appInfo.defaultAccountSlug;
        this.form.account = this.accounts.find(
          (account) => account.slug == defaultAccountSlug
        );
      }
    },

    // store item in array
    storeInvoice(invoice) {
      var index = this.form.selectedInvoices.findIndex(
        (x) => x.id == invoice.id
      );
      if (index === -1) {
        // store product
        this.form.selectedInvoices.push({
          id: invoice.id,
          slug: invoice.slug,
          invoiceNo: invoice.label,
          invoiceTotal: invoice.invoiceTotal,
          newDue: invoice.due,
          originalDue: invoice.due,
          maxAmount: invoice.due,
          paidAmount: 1,
          note: "",
        });
      }
      return true;
    },

    // update array
    updateArray(value, index) {
      let invoice = this.form.selectedInvoices[index];
      if (invoice && value <= invoice.maxAmount) {
        this.form.selectedInvoices[index].paidAmount = Number(value);
        invoice.newDue = Number(
          (invoice.originalDue - invoice.paidAmount).toFixed(2)
        );
      }
      this.form.selectedInvoices[index] = invoice;
      this.calculateTotal();
      return;
    },

    // remove item from array
    removeItem(item) {
      let index = this.form.selectedInvoices.indexOf(item);
      if (index > -1) {
        this.form.selectedInvoices.splice(index, 1);
      }
      this.calculateTotal();
      this.form.invoice = "";
      return;
    },

    // calculate total
    calculateTotal() {
      this.form.totalPayment = this.form.selectedInvoices.reduce(function (
        prev,
        cur
      ) {
        return prev + cur.paidAmount;
      },
        0);
      return;
    },

    // save payment
    async savePayment() {
      await this.form
        .post(window.location.origin + "/api/payments/invoice")
        .then(() => {
          toast.fire({
            type: "success",
            title: this.$t("payments.clients.invoice.create.success_msg"),
          });
          this.$router.push({ name: "invoicePayments.index" });
        })
        .catch(() => {
          toast.fire({ type: "error", title: this.$t("common.error_msg") });
        });
    },
  },
};
</script>
