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
              {{ $t('payments.clients.non_invoice.edit.form_title') }}
            </h3>
            <router-link :to="{ name: 'nonInvoicePayments.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="updatePayment" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row" v-if="items">
                <div class="form-group col-md-6">
                  <label for="client">{{ $t('common.client')
                  }}<span class="required">*</span></label>
                  <v-select v-model="form.client" :options="items" label="name"
                    :class="{ 'is-invalid': form.errors.has('client') }" name="client"
                    :placeholder="$t('common.client_placeholder')" disabled />
                  <has-error :form="form" field="client" />
                </div>
                <div class="form-group col-md-6">
                  <label for="type">{{ $t('common.type') }}</label>
                  <select id="type" v-model="form.type" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('type') }" disabled>
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
              <div v-if="form.client" class="row">
                <div class="form-group col-md-4">
                  <label for="nonInvoiceTotal">{{
                    $t('payments.common.non_invoice_total')
                  }}</label>
                  <input id="nonInvoiceTotal" v-model="form.nonInvoiceTotal" type="number" step="any" class="form-control"
                    name="nonInvoiceTotal" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="nonInvoicePaid">{{
                    $t('payments.common.non_invoice_paid')
                  }}</label>
                  <input id="nonInvoicePaid" v-model="form.nonInvoicePaid" type="number" step="any" class="form-control"
                    name="nonInvoicePaid" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="currentDue">{{
                    $t('payments.common.current_due')
                  }}</label>
                  <input id="nonInvoiceDue" v-model="form.nonInvoiceDue" type="number" step="any" class="form-control"
                    name="nonInvoiceDue" readonly />
                </div>
              </div>
              <div class="row" v-if="form.type == 1 && accounts">
                <div class="form-group col-md-6">
                  <label for="account">{{ $t('common.account') }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.account" :options="accounts" label="label"
                    :class="{ 'is-invalid': form.errors.has('account') }" name="account"
                    :placeholder="$t('common.account_placeholder')" />
                  <has-error :form="form" field="account" />
                </div>
                <div class="form-group col-md-3">
                  <label for="chequeNo">{{ $t('common.cheque_no') }}</label>
                  <input id="chequeNo" v-model="form.chequeNo" type="text" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('chequeNo') }" name="chequeNo"
                    :placeholder="$t('common.cheque_placeholder')" />
                  <has-error :form="form" field="chequeNo" />
                </div>
                <div class="form-group col-md-3">
                  <label for="receiptNo">{{ $t('common.receipt_no') }}</label>
                  <input id="receiptNo" v-model="form.receiptNo" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('receiptNo') }" name="receiptNo"
                    :placeholder="$t('common.receipt_no_placeholder')" />
                  <has-error :form="form" field="receiptNo" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="paidAmount">{{ $t('common.amount') }}
                    <span class="required">*</span></label>
                  <input id="paidAmount" v-model="form.paidAmount" type="number" step="any" min="1" :max="form.max"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('paidAmount') }" name="paidAmount"
                    :placeholder="$t('common.amount_placeholder')" @change="updateValues" @keyup="updateValues" />
                  <has-error :form="form" field="paidAmount" />
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
                <i class="fas fa-edit" /> {{ $t('common.save_changes') }}
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
    return { title: this.$t('payments.clients.non_invoice.edit.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'payments.clients.non_invoice.edit.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'payments.clients.non_invoice.edit.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'payments.clients.non_invoice.edit.breadcrumbs_second',
        url: '',
      },
      {
        name: 'payments.clients.non_invoice.edit.breadcrumbs_third',
        url: 'nonInvoicePayments.index',
      },
      {
        name: 'payments.clients.non_invoice.edit.breadcrumbs_active',
        url: '',
      },
    ],
    form: new Form({
      client: '',
      type: 0,
      account: '',
      paidAmount: '',
      chequeNo: '',
      receiptNo: '',
      max: 99999999999,
      nonInvoiceTotal: 0,
      nonInvoicePaid: 0,
      nonInvoiceDue: 0,
      rowDue: 0,
      rowPaid: 0,
      paymentDate: new Date().toISOString().slice(0, 10),
      note: '',
      status: 1,
    }),
    accounts: '',
  }),
  computed: {
    ...mapGetters('operations', ['items']),
  },
  created() {
    this.getClients()
    this.getAccounts()
    this.getPayment()
  },
  methods: {
    // get all clients
    async getClients() {
      await this.$store.dispatch('operations/allData', {
        path: '/api/all-clients',
      })
    },

    // get payment
    async getPayment() {
      const { data } = await axios.get(
        window.location.origin +
        '/api/payments/non-invoice/' +
        this.$route.params.slug
      )
      this.form.client = data.data.client
      this.form.type = data.data.type
      this.form.nonInvoiceTotal = data.data.client.nonInvoiceDue
      this.form.nonInvoicePaid = data.data.client.nonInvoicePaid
      this.form.nonInvoiceDue = data.data.client.nonInvoiceCurrentDue

      this.form.rowDue = data.data.client.nonInvoiceCurrentDue
      this.form.rowPaid = data.data.amount

      this.form.account = data.data.account
      this.form.paidAmount = data.data.amount
      this.form.paymentDate = data.data.date
      this.form.note = data.data.note
      this.form.status = data.data.status

      this.form.chequeNo = data.data.transaction
        ? data.data.transaction.cheque_no
        : ''
      this.form.receiptNo = data.data.transaction
        ? data.data.transaction.receipt_no
        : ''
      this.form.max =
        data.data.type == 1
          ? data.data.client.nonInvoiceCurrentDue + data.data.amount
          : 99999999999
    },

    // get accounts
    async getAccounts() {
      const { data } = await axios.get(
        window.location.origin + '/api/all-accounts'
      )
      this.accounts = data.data
    },

    // update values
    updateValues() {
      let paidAmount = Number(this.form.paidAmount)
      if (this.form.client && this.form.type == 1) {
        this.form.nonInvoicePaid =
          Number(this.form.client.nonInvoicePaid) -
          Number(this.form.rowPaid) +
          paidAmount
        this.form.nonInvoiceDue =
          Number(this.form.client.nonInvoiceCurrentDue + this.form.rowPaid) -
          paidAmount
      }

      if (this.form.client && this.form.type == 0) {
        this.form.nonInvoiceTotal =
          Number(this.form.client.nonInvoiceDue) -
          Number(this.form.rowPaid) +
          paidAmount
        this.form.nonInvoicePaid = Number(this.form.client.nonInvoicePaid)
        this.form.nonInvoiceDue =
          Number(this.form.client.nonInvoiceCurrentDue - this.form.rowPaid) +
          paidAmount
      }
      return
    },

    // update payment
    async updatePayment() {
      await this.form
        .patch(
          window.location.origin +
          '/api/payments/non-invoice/' +
          this.$route.params.slug
        )
        .then((response) => {
          toast.fire({
            type: 'success',
            title: this.$t('payments.clients.non_invoice.edit.success_msg'),
          })
          this.$router.push({ name: 'nonInvoicePayments.index' })
        })
        .catch(() => {
          toast.fire({
            type: 'error',
            title: this.$t('common.error_msg'),
          })
        })
    },
  },
}
</script>
