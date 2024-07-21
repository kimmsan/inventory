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
              {{ $t('expenses.categories.edit.form_title') }}
            </h3>
            <router-link :to="{ name: 'expenseCats.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="updateCategory" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="name">{{ $t('common.name') }}
                    <span class="required">*</span></label>
                  <input id="name" v-model="form.name" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('name') }" name="name"
                    :placeholder="$t('common.name_placeholder')" />
                  <has-error :form="form" field="name" />
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
                <div class="form-group col-md-12">
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

export default {
  middleware: ['auth', 'check-permissions'],
  metaInfo() {
    return { title: this.$t('expenses.categories.edit.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'expenses.categories.edit.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'expenses.categories.edit.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'expenses.categories.edit.breadcrumbs_second',
        url: 'expenseCats.index',
      },
      {
        name: 'expenses.categories.edit.breadcrumbs_active',
        url: '',
      },
    ],
    form: new Form({
      name: '',
      note: '',
      status: 1,
    }),
    loading: true,
  }),

  mounted() {
    this.getCategory()
  },
  methods: {
    // get category
    async getCategory() {
      const { data } = await axios.get(
        window.location.origin +
        '/api/expense-categories/' +
        this.$route.params.slug
      )
      this.form.name = data.data.name
      this.form.note = data.data.note
      this.form.status = data.data.status
    },
    // update category
    async updateCategory() {
      await this.form
        .patch(
          window.location.origin +
          '/api/expense-categories/' +
          this.$route.params.slug
        )
        .then(() => {
          toast.fire({
            type: 'success',
            title: this.$t('expenses.categories.edit.success_msg'),
          })
          this.$router.push({ name: 'expenseCats.index' })
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
