<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              {{ $t('payments.suppliers.non_purchase.create.form_title') }}
            </h3>
            <router-link :to="{ name: 'nonPurchasePayments.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="savePayment" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row" v-if="items">
                <div class="form-group col-md-6">
                  <label for="supplier">{{ $t('common.supplier')
                  }}<span class="required">*</span></label>
                  <v-select v-model="form.supplier" :options="items" label="name"
                    :class="{ 'is-invalid': form.errors.has('supplier') }" name="supplier"
                    :placeholder="$t('common.supplier_placeholder')" @input="calculateValues" />
                  <has-error :form="form" field="supplier" />
                </div>
                <div class="form-group col-md-6">
                  <label for="type">{{ $t('common.type') }}
                    <span class="required">*</span></label>
                  <select id="type" v-model="form.type" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('type') }" @change="updateMax">
                    <option value="0">
                      {{ $t('payments.common.add_due') }}
                    </option>
                    <option value="1">
                      {{ $t('payments.common.add_payment') }}
                    </option>
                  </select>
                  <has-error :form="form" field="type" />
                </div>
              </div>
              <div v-if="form.supplier" class="row">
                <div class="form-group col-md-4">
                  <label for="nonPurchaseTotal">{{
                    $t('payments.common.non_purchase_total')
                  }}</label>
                  <input id="nonPurchaseTotal" v-model="form.nonPurchaseTotal" type="number" step="any"
                    class="form-control" name="nonPurchaseTotal" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="nonPurchasePaid">{{
                    $t('payments.common.non_purchase_paid')
                  }}</label>
                  <input id="nonPurchasePaid" v-model="form.nonPurchasePaid" type="number" step="any" class="form-control"
                    name="nonPurchasePaid" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="currentDue">{{
                    $t('payments.common.current_due')
                  }}</label>
                  <input id="nonPurchaseDue" v-model="form.nonPurchaseDue" type="number" step="any" class="form-control"
                    name="nonPurchaseDue" readonly />
                </div>
              </div>
              <div class="row" v-if="form.type == 1 && accounts">
                <div class="form-group col-md-6">
                  <label for="account">{{ $t('common.account') }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.account" :options="accounts" label="label"
                    :class="{ 'is-invalid': form.errors.has('account') }" name="account"
                    :placeholder="$t('common.account_placeholder')" @input="updateBalance" />
                  <has-error :form="form" field="account" />
                </div>
                <div class="form-group col-md-6">
                  <label for="availableBalance">{{
                    $t('common.available_balance')
                  }}</label>
                  <input id="availableBalance" v-model="form.availableBalance" type="text" step="any" class="form-control"
                    :class="{
                      'is-invalid': form.errors.has('availableBalance'),
                    }" name="availableBalance" readonly />
                  <has-error :form="form" field="availableBalance" />
                </div>
                <div class="form-group col-md-6">
                  <label for="chequeNo">{{ $t('common.cheque_no') }}</label>
                  <input id="chequeNo" v-model="form.chequeNo" type="text" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('chequeNo') }" name="chequeNo"
                    :placeholder="$t('common.cheque_placeholder')" />
                  <has-error :form="form" field="chequeNo" />
                </div>
                <div class="form-group col-md-6">
                  <label for="receiptNo">{{ $t('common.receipt_no') }}</label>
                  <input id="receiptNo" v-model="form.receiptNo" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('receiptNo') }" name="receiptNo"
                    :placeholder="$t('common.receipt_no_placeholder')" />
                  <has-error :form="form" field="receiptNo" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="amount">{{ $t('common.amount') }}
                    <span class="required">*</span></label>
                  <input id="amount" v-model="form.amount" type="number" step="any" min="1" :max="form.max"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('amount') }" name="amount"
                    :placeholder="$t('common.amount_placeholder')" @change="updateValues" @keyup="updateValues" />
                  <has-error :form="form" field="amount" />
                </div>
                <div class="form-group col-md-4">
                  <label for="paymentDate">{{
                    $t('common.payment_date')
                  }}</label>
                  <input id="paymentDate" v-model="form.paymentDate" type="date" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('paymentDate') }" name="paymentDate" />
                  <has-error :form="form" field="paymentDate" />
                </div>
                <div class="form-group col-md-4">
                  <label for="status">{{ $t('common.status') }}</label>
                  <select id="status" v-model="form.status" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('status') }">
                    <option value="1">{{ $t('common.active') }}</option>
                    <option value="0">{{ $t('common.in_active') }}</option>
                  </select>
                  <has-error :form="form" field="status" />
                </div>
              </div>
              <div class="form-group">
                <label for="note">{{ $t('common.note') }}</label>
                <textarea id="note" v-model="form.note" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('note') }" :placeholder="$t('common.note_placeholder')" />
                <has-error :form="form" field="note" />
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <v-button :loading="form.busy" class="btn btn-primary">
                <i class="fas fa-save" /> {{ $t('common.save') }}
              </v-button>
              <button type="reset" class="btn btn-secondary float-right" @click="form.reset()">
                <i class="fas fa-power-off" /> {{ $t('common.reset') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Form from 'vform'
import axios from 'axios'

export default {
  middleware: ['auth', 'check-permissions'],
  metaInfo() {
    return {
      title: this.$t('payments.suppliers.non_purchase.create.page_title'),
    }
  },
  data: () => ({
    breadcrumbsCurrent:
      'payments.suppliers.non_purchase.create.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'payments.suppliers.non_purchase.create.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'payments.suppliers.non_purchase.create.breadcrumbs_second',
        url: '',
      },
      {
        name: 'payments.suppliers.non_purchase.create.breadcrumbs_third',
        url: 'nonPurchasePayments.index',
      },
      {
        name: 'payments.suppliers.non_purchase.create.breadcrumbs_active',
        url: '',
      },
    ],
    form: new Form({
      supplier: '',
      type: 0,
      account: '',
      amount: '',
      chequeNo: '',
      receiptNo: '',
      max: 99999999999,
      nonPurchaseTotal: 0,
      nonPurchasePaid: 0,
      nonPurchaseDue: 0,
      availableBalance: 0,
      paymentDate: new Date().toISOString().slice(0, 10),
      note: '',
      status: 1,
    }),
    accounts: '',
  }),
  computed: {
    ...mapGetters('operations', ['items', 'appInfo']),
  },
  created() {
    this.getSuppliers()
    this.getAccounts()
  },
  methods: {
    // get all suppliers
    async getSuppliers() {
      await this.$store.dispatch('operations/allData', {
        path: '/api/suppliers-for-nonpurchase-payments',
      })
    },

    // get accounts
    async getAccounts() {
      const { data } = await axios.get(
        window.location.origin + '/api/all-accounts'
      )
      this.accounts = data.data
      // assign default account
      if (this.accounts && this.accounts.length > 0) {
        let defaultAccountSlug = this.appInfo.defaultAccountSlug;
        this.form.account = this.accounts.find(account => account.slug == defaultAccountSlug);
        this.updateBalance()
      }
    },

    // update available balance
    updateBalance() {
      this.form.availableBalance = 0
      if (this.form.account) {
        this.form.availableBalance = this.form.account.availableBalance
      }
    },

    // calculate values
    calculateValues() {
      if (this.form.supplier) {
        this.form.nonPurchaseTotal = this.form.supplier.nonPurchaseTotalDue
        this.form.nonPurchasePaid = this.form.supplier.nonPurchasePaid
        this.form.nonPurchaseDue = this.form.supplier.nonPurchaseCurrentDue
        this.updateMax()
      }
      return
    },

    // update values
    updateValues() {
      let amount = Number(this.form.amount)
      if (this.form.supplier && this.form.type == 1) {
        this.form.nonPurchasePaid =
          Number(this.form.supplier.nonPurchasePaid) + amount
        this.form.nonPurchaseDue =
          Number(this.form.supplier.nonPurchaseCurrentDue) - amount
      }

      if (this.form.supplier && this.form.type == 0) {
        this.form.nonPurchaseTotal =
          Number(this.form.supplier.nonPurchaseTotalDue) + amount
        this.form.nonPurchasePaid = Number(this.form.supplier.nonPurchasePaid)
        this.form.nonPurchaseDue =
          Number(this.form.supplier.nonPurchaseCurrentDue) + amount
      }
      return
    },

    // update max amount
    updateMax() {
      if (this.form.supplier && this.form.type == 1) {
        this.form.max = this.form.supplier.nonPurchaseCurrentDue
      } else {
        this.form.max = 99999999999
      }
      this.updateValues()
      return
    },

    // save payment
    async savePayment() {
      await this.form
        .post(window.location.origin + '/api/payments/non-purchase')
        .then(() => {
          toast.fire({
            type: 'success',
            title: this.$t(
              'payments.suppliers.non_purchase.create.success_msg'
            ),
          })
          this.$router.push({ name: 'nonPurchasePayments.index' })
        })
        .catch(() => {
          toast.fire({ type: 'error', title: this.$t('common.error_msg') })
        })
    },
  },
}
</script>
