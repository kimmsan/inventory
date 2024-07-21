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
              {{ $t('cashbook.accounts.create.form_title') }}
            </h3>
            <router-link :to="{ name: 'accounts.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="saveAccount" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="bankName">{{ $t('cashbook.common.bank_name') }}
                    <span class="required">*</span></label>
                  <input id="bankName" v-model="form.bankName" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('bankName') }" name="bankName"
                    :placeholder="$t('cashbook.common.bank_name_placeholder')" />
                  <has-error :form="form" field="bankName" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="branchName">{{ $t('cashbook.common.branch_name') }}
                  </label>
                  <input id="branchName" v-model="form.branchName" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('branchName') }" name="branchName"
                    :placeholder="$t('cashbook.common.branch_name_placeholder')" />
                  <has-error :form="form" field="branchName" />
                </div>
                <div class="form-group col-md-6">
                  <label for="accountNumber">{{ $t('cashbook.common.account_number') }}
                    <span class="required">*</span></label>
                  <input id="accountNumber" v-model="form.accountNumber" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('accountNumber') }" name="accountNumber" :placeholder="
                      $t('cashbook.common.account_number_placeholder')
                    " />
                  <has-error :form="form" field="accountNumber" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="date">{{ $t('common.date') }}</label>
                  <input id="date" v-model="form.date" type="date" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('date') }" name="date" />
                  <has-error :form="form" field="date" />
                </div>
                <div class="form-group col-md-6">
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
import Form from 'vform'
export default {
  middleware: ['auth', 'check-permissions'],
  metaInfo() {
    return { title: this.$t('cashbook.accounts.create.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'cashbook.accounts.create.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'cashbook.accounts.create.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'cashbook.accounts.create.breadcrumbs_second',
        url: '',
      },
      {
        name: 'cashbook.accounts.create.breadcrumbs_third',
        url: 'accounts.index',
      },
      {
        name: 'cashbook.accounts.create.breadcrumbs_active',
        url: '',
      },
    ],
    form: new Form({
      bankName: '',
      branchName: '',
      accountNumber: '',
      date: new Date().toISOString().slice(0, 10),
      note: '',
      status: 1,
    }),
    loading: true,
  }),

  methods: {
    // save account
    async saveAccount() {
      await this.form
        .post(window.location.origin + '/api/accounts')
        .then(() => {
          toast.fire({
            type: 'success',
            title: this.$t('cashbook.accounts.create.success_msg'),
          })
          this.$router.push({ name: 'accounts.index' })
        })
        .catch(() => {
          toast.fire({ type: 'error', title: this.$t('common.error_msg') })
        })
    },
  },
}
</script>

