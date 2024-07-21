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
              {{ $t('loans.authorities.create.form_title') }}
            </h3>
            <router-link :to="{ name: 'authorities.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="saveAuthority" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="name">{{ $t('common.name') }}
                    <span class="required">*</span></label>
                  <input id="name" v-model="form.name" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('name') }" name="name"
                    :placeholder="$t('common.name_placeholder')" />
                  <has-error :form="form" field="name" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="email">{{ $t('common.email') }}</label>
                  <input id="email" v-model="form.email" type="email" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('email') }" name="email"
                    :placeholder="$t('common.email_placeholder')" />
                  <has-error :form="form" field="email" />
                </div>
                <div class="form-group col-md-6">
                  <label for="contactNumber">{{ $t('common.contact_number') }}
                    <span class="required">*</span></label>
                  <input id="contactNumber" v-model="form.contactNumber" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('contactNumber') }" name="contactNumber"
                    :placeholder="$t('common.contact_number_placeholder')" />
                  <has-error :form="form" field="contactNumber" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="ccLoanLimit">{{ $t('loans.common.cash_credit_limit') }}
                    <span class="required">*</span></label>
                  <input id="ccLoanLimit" v-model="form.ccLoanLimit" type="number" step=".01" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('ccLoanLimit') }" name="ccLoanLimit"
                    :placeholder="$t('loans.common.cc_placeholder')" />
                  <has-error :form="form" field="ccLoanLimit" />
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
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="address">{{ $t('common.address') }}</label>
                  <textarea id="address" v-model="form.address" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('address') }" name="address"
                    :placeholder="$t('common.address_placeholder')" />
                  <has-error :form="form" field="address" />
                </div>
                <div class="form-group col-md-6">
                  <label for="note">{{ $t('common.note') }}</label>
                  <textarea id="note" v-model="form.note" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('note') }" :placeholder="$t('common.note_placeholder')" />
                  <has-error :form="form" field="note" />
                </div>
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
    return { title: this.$t('loans.authorities.create.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'loans.authorities.create.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'loans.authorities.create.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'loans.authorities.create.breadcrumbs_second',
        url: 'authorities.index',
      },
      {
        name: 'loans.authorities.create.breadcrumbs_active',
        url: '',
      },
    ],
    form: new Form({
      name: '',
      email: '',
      contactNumber: '',
      ccLoanLimit: 10000000,
      address: '',
      note: '',
      status: 1,
    }),
    loading: true,
  }),
  methods: {
    // save category
    async saveAuthority() {
      await this.form
        .post(window.location.origin + '/api/loan-authorities')
        .then(() => {
          toast.fire({
            type: 'success',
            title: this.$t('loans.authorities.create.success_msg'),
          })
          this.$router.push({ name: 'authorities.index' })
        })
        .catch(() => {
          toast.fire({ type: 'error', title: this.$t('common.error_msg') })
        })
    },
  },
}
</script>

