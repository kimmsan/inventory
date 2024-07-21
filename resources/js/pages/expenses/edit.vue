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
              {{ $t('expenses.list.edit.form_title') }}
            </h3>
            <router-link :to="{ name: 'expenses.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="updateExpense" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="reason">{{
                    $t('expenses.list.common.expense_reason')
                  }}</label>
                  <input id="reason" v-model="form.reason" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('reason') }" name="reason" :placeholder="$t('expenses.list.common.expense_reason_placeholder')
                      " />
                  <has-error :form="form" field="reason" />
                </div>
                <div v-if="items" class="form-group col-md-6">
                  <label for="subCategory">{{
                    $t('common.category_name')
                  }}</label>
                  <v-select v-model="form.subCategory" :options="items" label="name"
                    :class="{ 'is-invalid': form.errors.has('subCategory') }" name="subCategory"
                    :placeholder="$t('common.category_name_placeholder')" />
                  <has-error :form="form" field="subCategory" />
                </div>
              </div>
              <div class="row" v-if="accounts">
                <div class="form-group col-md-6">
                  <label for="account">{{ $t('common.account') }}</label>
                  <v-select v-model="form.account" :options="accounts" label="label"
                    :class="{ 'is-invalid': form.errors.has('account') }" name="account"
                    :placeholder="$t('common.account_placeholder')" @input="updateBalance" />
                  <has-error :form="form" field="account" />
                </div>
                <div class="form-group col-md-6">
                  <label for="availableBalance">{{
                    $t('common.available_balance')
                  }}</label>
                  <input id="availableBalance" v-model="form.availableBalance" type="number" step="any"
                    class="form-control" :class="{
                      'is-invalid': form.errors.has('availableBalance'),
                    }" name="availableBalance" readonly />
                  <has-error :form="form" field="availableBalance" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="amount">{{ $t('common.amount') }}</label>
                  <input id="amount" v-model="form.amount" type="number" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('amount') }" name="amount"
                    :placeholder="$t('common.amount_placeholder')" />
                  <has-error :form="form" field="amount" />
                </div>
                <div class="form-group col-md-4">
                  <label for="chequeNo">{{ $t('common.cheque_no') }}</label>
                  <input id="chequeNo" v-model="form.chequeNo" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('chequeNo') }" name="chequeNo"
                    :placeholder="$t('common.cheque_placeholder')" />
                  <has-error :form="form" field="chequeNo" />
                </div>
                <div class="form-group col-md-4">
                  <label for="voucherNo">{{ $t('common.voucher_no') }}</label>
                  <input id="voucherNo" v-model="form.voucherNo" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('voucherNo') }" name="voucherNo"
                    :placeholder="$t('common.voucher_placeholder')" />
                  <has-error :form="form" field="voucherNo" />
                </div>
              </div>
              <div class="form-group">
                <label for="note">{{ $t('common.note') }}</label>
                <textarea id="note" v-model="form.note" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('note') }" :placeholder="$t('common.note_placeholder')" />
                <has-error :form="form" field="note" />
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="date">{{ $t('common.date') }}</label>
                  <input id="date" v-model="form.date" type="date" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('date') }" name="date" />
                  <has-error :form="form" field="date" />
                </div>
                <div class="form-group col-md-4">
                  <label for="status">{{ $t('common.status') }}</label>
                  <select id="status" v-model="form.status" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('status') }">
                    <option value="1">
                      {{ $t('common.active') }}
                    </option>
                    <option value="0">
                      {{ $t('common.in_active') }}
                    </option>
                  </select>
                  <has-error :form="form" field="status" />
                </div>
                <div class="form-group col-md-4">
                  <label for="image">{{ $t('common.image') }}</label>
                  <div class="custom-file">
                    <input id="image" type="file" class="custom-file-input" name="image"
                      :class="{ 'is-invalid': form.errors.has('image') }" @change="onFileChange" />
                    <label class="custom-file-label" for="image">{{
                      $t('common.choose_file')
                    }}</label>
                  </div>
                  <has-error :form="form" field="image" />
                  <div class="bg-light mt-4 w-25">
                    <img v-if="url" :src="url" class="img-fluid" :alt="$t('common.image_alt')" />
                  </div>
                </div>
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
import Form from 'vform'
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
  middleware: ['auth', 'check-permissions'],
  metaInfo() {
    return { title: this.$t('expenses.list.edit.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'expenses.list.edit.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'expenses.list.edit.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'expenses.list.edit.breadcrumbs_second',
        url: 'expenses.index',
      },
      {
        name: 'expenses.list.edit.breadcrumbs_active',
        url: '',
      },
    ],
    form: new Form({
      reason: '',
      subCategory: '',
      account: '',
      availableBalance: '',
      amount: '',
      chequeNo: '',
      voucherNo: '',
      date: '',
      note: '',
      status: 1,
      image: '',
    }),
    url: null,
    accounts: [],
  }),
  computed: {
    ...mapGetters('operations', ['items']),
  },
  mounted() {
    this.expense()
    this.getSubCatgories()
    this.getAccounts()
  },
  methods: {
    // get all expense categories
    async getSubCatgories() {
      await this.$store.dispatch('operations/allData', {
        path: '/api/all-expense-sub-categories',
      })
    },

    // get accounts
    async getAccounts() {
      const { data } = await axios.get(
        window.location.origin + '/api/all-accounts'
      )
      this.accounts = data.data
    },

    // get expense
    async expense() {
      const { data } = await axios.get(
        window.location.origin + '/api/expenses/' + this.$route.params.slug
      )
      this.form.reason = data.data.reason
      this.form.subCategory = data.data.subCategory
      this.form.account = data.data.account
      this.form.availableBalance = data.data.account.availableBalance
      this.form.amount = data.data.transaction.amount
      this.form.chequeNo = data.data.transaction.cheque_no
      this.form.voucherNo = data.data.transaction.receipt_no
      this.form.date = data.data.date
      this.form.note = data.data.note
      this.form.status = data.data.status
      this.url = data.data.image
    },

    // update available balance
    updateBalance() {
      this.form.availableBalance = 0
      if (this.form.account) {
        this.form.availableBalance = this.form.account.availableBalance
      }
      return
    },

    // vue file upload
    onFileChange(e) {
      const file = e.target.files[0]
      const reader = new FileReader()
      if (
        file.size < 2111775 &&
        (file.type === 'image/jpeg' ||
          file.type === 'image/png' ||
          file.type === 'image/gif')
      ) {
        reader.onloadend = (file) => {
          this.form.image = reader.result
        }
        reader.readAsDataURL(file)
        this.url = URL.createObjectURL(file)
      } else {
        Swal.fire('error!', this.$t('common.image_error'), 'error')
      }
    },

    // update expense
    async updateExpense() {
      await this.form
        .patch(
          window.location.origin + '/api/expenses/' + this.$route.params.slug
        )
        .then((response) => {
          toast.fire({
            type: 'success',
            title: this.$t('expenses.list.edit.success_msg'),
          })
          this.$router.push({ name: 'expenses.index' })
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

