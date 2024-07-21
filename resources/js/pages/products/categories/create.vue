<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card w-100">
          <div class="card-header">
            <h3 class="card-title">
              {{ $t('products.categories.create.form_title') }}
            </h3>
            <router-link :to="{ name: 'productCats.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="saveCategory" @keydown="form.onKeydown($event)">
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
    return { title: this.$t('products.categories.create.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'products.categories.create.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'products.categories.create.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'products.categories.create.breadcrumbs_second',
        url: 'products.index',
      },
      {
        name: 'products.categories.create.breadcrumbs_third',
        url: 'productCats.index',
      },
      {
        name: 'products.categories.create.breadcrumbs_active',
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
  methods: {
    // save category
    async saveCategory() {
      await this.form
        .post(window.location.origin + '/api/product-categories')
        .then(() => {
          toast.fire({
            type: 'success',
            title: this.$t('products.categories.create.success_msg'),
          })
          this.$router.push({ name: 'productCats.index' })
        })
        .catch(() => {
          toast.fire({ type: 'error', title: this.$t('common.error_msg') })
        })
    },
  },
}
</script>
