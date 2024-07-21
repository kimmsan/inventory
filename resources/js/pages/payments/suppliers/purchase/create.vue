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
              {{ $t("payments.suppliers.purchase.create.form_title") }}
            </h3>
            <router-link :to="{ name: 'purchasePayments.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t("common.back") }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="savePayment" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row" v-if="items">
                <div class="form-group col-md-12">
                  <label for="supplier">{{ $t("common.supplier")
                  }}<span class="required">*</span></label>
                  <v-select v-model="form.supplier" :options="items" label="name"
                    :class="{ 'is-invalid': form.errors.has('supplier') }" name="supplier" placeholder="Select a supplier"
                    @input="getPurchases" />
                  <has-error :form="form" field="supplier" />
                </div>
              </div>
              <div v-if="form.supplier" class="row">
                <div class="form-group col-md-4">
                  <label for="purchaseTotal">{{
                    $t("purchases.list.common.purchase_total")
                  }}</label>
                  <input id="purchaseTotal" v-model="form.supplier.purchaseTotal" type="text" class="form-control"
                    name="purchaseTotal" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="totalPaid">{{ $t("common.total_paid") }}</label>
                  <input id="totalPaid" v-model="form.supplier.purchaseTotalPaid" type="text" class="form-control"
                    name="totalPaid" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="purchaseDue">{{ $t("common.total_due") }}</label>
                  <input id="purchaseDue" v-model="form.supplier.purchaseDue" type="text" class="form-control"
                    name="purchaseDue" readonly />
                </div>
              </div>
              <div class="row mb-3" v-if="form.supplier && purchases">
                <div class="form-group col-md-12">
                  <label for="purchase">{{ $t("payments.common.select_purchase")
                  }}<span class="required">*</span></label>
                  <v-select v-model="form.purchase" :options="purchases" label="purchaseNo"
                    :class="{ 'is-invalid': form.errors.has('purchase') }" name="purchase" :placeholder="$t('payments.common.select_purchase_placeholder')
                      " @input="storePurchase(form.purchase)" />
                  <has-error :form="form" field="purchase" />
                </div>
              </div>

              <div v-if="form.selectedPurchases" class="col-md-11 m-auto">
                <div v-for="(item, i) in form.selectedPurchases" :key="i" class="card bg-light border-dark mb-3">
                  <div class="card-header">
                    {{ item.purchaseNo }}
                    {{ $t("payments.common.purchase_details") }}
                    <button type="button" class="btn btn-danger float-right" @click="removeItem(item)">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="form-group col-md-3">
                        <label for="purchaseNumber">{{
                          $t("purchases.list.common.purchase_no")
                        }}</label>
                        <input type="text" :id="`purchaseNumber-${++i}`" class="form-control" :value="item.purchaseNo"
                          readonly />
                      </div>
                      <div class="form-group col-md-3">
                        <label for="purchaseTotal">{{
                          $t("common.total")
                        }}</label>
                        <input type="text" :id="`purchaseTotal-${i}`" class="form-control" :value="item.purchaseTotal"
                          readonly />
                      </div>
                      <div class="form-group col-md-3">
                        <label for="purchaseDue">{{ $t("common.due") }}</label>
                        <input type="text" :id="`purchaseDue-${i}`" class="form-control" :value="item.newDue" readonly />
                      </div>
                      <div class="form-group col-md-3">
                        <label for="paidAmount">{{ $t("common.paid_amount")
                        }}<span class="required">*</span></label>
                        <input type="number" step="any" class="form-control" :id="`paidAmount-${i}`"
                          :placeholder="$t('common.paid_amount_placeholder')"
                          @change="updateArray($event.target.value, i - 1)"
                          @keyup="updateArray($event.target.value, i - 1)" :value="item.paidAmount" required min="1"
                          :max="item.originalDue" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-if="accounts" class="row">
                <div class="form-group col-md-3">
                  <label for="finalTotal">{{ $t("payments.common.total_payment") }}
                  </label>
                  <input id="finalTotal" v-model="form.finalTotal" type="number" step="any" class="form-control"
                    name="finalTotal" readonly />
                </div>
                <div class="form-group col-md-6">
                  <label for="account">{{ $t("common.account") }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.account" :options="accounts" label="label"
                    :class="{ 'is-invalid': form.errors.has('account') }" name="account"
                    :placeholder="$t('common.account_placeholder')" @input="updateBalance" />
                  <has-error :form="form" field="account" />
                </div>
                <div class="form-group col-md-3">
                  <label for="availableBalance">{{
                    $t("common.available_balance")
                  }}</label>
                  <input id="availableBalance" v-model="form.availableBalance" type="number" step="any"
                    class="form-control" :class="{
                      'is-invalid': form.errors.has('availableBalance'),
                    }" name="availableBalance" readonly />
                  <has-error :form="form" field="availableBalance" />
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
import { mapGetters } from "vuex";
import { ToggleButton } from "vue-js-toggle-button";
import axios from "axios";


export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("payments.suppliers.purchase.create.page_title") };
  },
  components: {
    ToggleButton,
  },
  data: () => ({
    breadcrumbsCurrent:
      "payments.suppliers.purchase.create.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "payments.suppliers.purchase.create.breadcrumbs_first",
        url: "home",
      },
      {
        name: "payments.suppliers.purchase.create.breadcrumbs_second",
        url: "",
      },
      {
        name: "payments.suppliers.purchase.create.breadcrumbs_third",
        url: "purchasePayments.index",
      },
      {
        name: "payments.suppliers.purchase.create.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      supplier: "",
      selectedPurchases: [],
      account: "",
      availableBalance: 0,
      total: 0,
      finalTotal: 0,
      chequeNo: "",
      receiptNo: "",
      note: "",
      status: 1,
      paymentDate: new Date().toISOString().slice(0, 10),
      isSendEmail: false,
      isSendSMS: false,
    }),
    accounts: "",
    purchases: "",
    isDemoMode: window.config.isDemoMode,
  }),
  computed: {
    ...mapGetters("operations", ["items", "appInfo"]),
  },
  created() {
    this.getSuppliers();
    this.getAccounts();
  },
  methods: {
    // get all suppliers
    async getSuppliers() {
      await this.$store.dispatch("operations/allData", {
        path: "/api/all-suppliers",
      });
    },

    // get purchases
    async getPurchases() {
      this.form.selectedPurchases = [];
      if (this.form.supplier) {
        const { data } = await axios.get(
          window.location.origin +
          "/api/supplier/" +
          this.form.supplier.slug +
          "/purchases"
        );
        this.purchases = data.purchases;
        this.form.supplier = data.supplier;
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
        this.updateBalance();
      }
    },

    // update available balance
    updateBalance() {
      this.form.availableBalance = 0;
      if (this.form.account) {
        this.form.availableBalance = this.form.account.availableBalance;
      }
      return;
    },

    // store item in array
    storePurchase(purchase) {
      var index = this.form.selectedPurchases.findIndex(
        (x) => x.id == purchase.id
      );
      if (index === -1) {
        // store product
        this.form.selectedPurchases.push({
          id: purchase.id,
          slug: purchase.slug,
          purchaseNo: purchase.purchaseNo,
          purchaseTotal: purchase.purchaseTotal,
          newDue: purchase.due,
          originalDue: purchase.due,
          maxAmount: purchase.due,
          paidAmount: 1,
        });
      }
      return;
    },

    // update array
    updateArray(value, index) {
      let purchase = this.form.selectedPurchases[index];
      if (purchase && value <= purchase.maxAmount) {
        purchase.paidAmount = Number(value);
        purchase.newDue = Number(
          (purchase.originalDue - purchase.paidAmount).toFixed(2)
        );
      }
      this.form.selectedPurchases[index] = purchase;
      this.calculateTotal();
      return;
    },

    // remove item from array
    removeItem(item) {
      let index = this.form.selectedPurchases.indexOf(item);
      if (index > -1) {
        this.form.selectedPurchases.splice(index, 1);
      }
      this.calculateTotal();
      this.form.purchase = "";
      return;
    },

    // calculate total
    calculateTotal() {
      // total
      this.form.finalTotal = this.form.selectedPurchases.reduce(function (
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
        .post(window.location.origin + "/api/payments/purchase")
        .then(() => {
          toast.fire({
            type: "success",
            title: this.$t("payments.suppliers.purchase.create.success_msg"),
          });
          this.$router.push({ name: "purchasePayments.index" });
        })
        .catch(() => {
          toast.fire({ type: "error", title: this.$t("common.error_msg") });
        });
    },
  },
};
</script>

