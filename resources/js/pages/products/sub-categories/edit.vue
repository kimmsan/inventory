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
              {{ $t('products.sub_categories.edit.form_title') }}
            </h3>
            <router-link :to="{ name: 'productSubCats.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="updateSubCategory" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="form-group">
                <label for="name">{{ $t('common.name') }}
                  <span class="required">*</span></label>
                <input id="name" v-model="form.name" type="text" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }" name="name"
                  :placeholder="$t('common.name_placeholder')" />
                <has-error :form="form" field="name" />
              </div>
              <div class="row">
                <div v-if="items" class="form-group col-md-6">
                  <label for="category">{{ $t('common.category_name') }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.category" :options="items" label="name"
                    :class="{ 'is-invalid': form.errors.has('category') }" name="category"
                    :placeholder="$t('common.category_name_placeholder')" />
                  <has-error :form="form" field="category" />
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
    return { title: this.$t('products.sub_categories.edit.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'products.sub_categories.edit.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'products.sub_categories.edit.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'products.sub_categories.edit.breadcrumbs_second',
        url: 'products.index',
      },
      {
        name: 'products.sub_categories.edit.breadcrumbs_third',
        url: 'productSubCats.index',
      },
      {
        name: 'products.sub_categories.edit.breadcrumbs_active',
        url: '',
      },
    ],
    form: new Form({
      name: '',
      note: '',
      status: 1,
      category: null,
    }),
    loading: true,
  }),
  computed: {
    ...mapGetters('operations', ['items']),
  },
  created() {
    this.getSubCategory()
    this.getCatgories()
  },
  methods: {
    // get all product categories
    async getCatgories() {
      await this.$store.dispatch('operations/allData', {
        path: '/api/all-product-categories',
      })
    },
    // get category
    async getSubCategory() {
      const { data } = await axios.get(
        window.location.origin +
        '/api/product-sub-categories/' +
        this.$route.params.slug
      )
      this.form.name = data.data.name
      this.form.note = data.data.note
      this.form.status = data.data.status
      this.form.category = data.data.category
    },
    // update category
    async updateSubCategory() {
      await this.form
        .patch(
          window.location.origin +
          '/api/product-sub-categories/' +
          this.$route.params.slug
        )
        .then((response) => {
          toast.fire({
            type: 'success',
            title: this.$t('products.sub_categories.edit.success_msg'),
          })
          this.$router.push({ name: 'productSubCats.index' })
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
