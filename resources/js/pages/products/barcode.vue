<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card no-print">
          <div class="card-header">
            <h3 class="card-title">
              {{ $t('products.list.barcode.generate_barcode') }}
            </h3>
            <router-link :to="{ name: 'products.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="generateBarcode" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row">
                <div v-if="items" class="form-group col-md-4">
                  <label for="product">{{ $t('common.select_products') }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.product" :options="items" label="label"
                    :class="{ 'is-invalid': form.errors.has('product') }" name="product" required="true"
                    :placeholder="$t('common.select_products_placeholder')" />
                  <has-error :form="form" field="product" />
                </div>
                <div class="form-group col-md-4">
                  <label for="paperSize">{{
                    $t('products.list.barcode.paper_size')
                  }}</label>
                  <select id="paperSize" v-model="form.paperSize" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('paperSize') }">
                    <option selected disabled value="0">
                      {{ $t('products.list.barcode.paper_size') }}
                    </option>
                    <option value="40">
                      {{ $t('products.list.barcode.page_option_0') }}
                    </option>
                    <option value="30">
                      {{ $t('products.list.barcode.page_option_1') }}
                    </option>
                    <option value="24">
                      {{ $t('products.list.barcode.page_option_2') }}
                    </option>
                    <option value="20">
                      {{ $t('products.list.barcode.page_option_3') }}
                    </option>
                    <option value="18">
                      {{ $t('products.list.barcode.page_option_4') }}
                    </option>
                    <option value="14">
                      {{ $t('products.list.barcode.page_option_5') }}
                    </option>
                    <option value="12">
                      {{ $t('products.list.barcode.page_option_6') }}
                    </option>
                    <option value="10">
                      {{ $t('products.list.barcode.page_option_7') }}
                    </option>
                  </select>
                  <has-error :form="form" field="paperSize" />
                </div>
                <div class="form-group col-md-4">
                  <label for="quantity">{{ $t('common.quantity') }}
                    <span class="required">*</span></label>
                  <input v-model="form.quantity" type="number" class="form-control" id="printQty"
                    :placeholder="$t('products.list.barcode.print_qty')" required min="1" max="1000" />
                </div>
              </div>

              <div class="row ">
                <div v-if="items" class="form-group col-md-6 mt-3">
                  <toggle-button v-model="generateWithPrice" /> {{ $t("Generate barcode with price") }}
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button class="btn btn-primary">
                <i class="fas fa-edit" />
                {{ $t('products.list.barcode.generate_barcode') }}
              </button>
              <button type="reset" class="btn btn-secondary float-right" @click="form.reset()">
                <i class="fas fa-power-off" /> {{ $t('common.reset') }}
              </button>
            </div>
          </form>
        </div>

        <div class="card" v-if="showBarcode">
          <div class="card-header">
            <h3 class="card-title">
              {{ $t('products.list.barcode.print_barcode') }}
            </h3>
            <button class="btn btn-info float-right" @click="print">
              <i class="fas fa-print" /> {{ $t('common.print') }}
            </button>
          </div>
          <div class="barcode-wrapper card-body" id="printMe">
            <div class="print-page-layout display-grid" :class="perPage" v-for="(pageNumber, i) in pages" :key="i">
              <div class="barcode-item" v-for="j in parseInt(pageNumber)" :key="j">
                <span class="barcode-name">{{ form.product.name }}</span>
                <barcode width="1" height="25" fontSize="15" :value="form.product.code">
                  {{ $t('common.rendering_fails') }}
                </barcode>
                <span v-if="generateWithPrice" class="barcode-name"> {{ $t("Price") }}: {{ appInfo.currency.symbol }}{{
                  form.product.sellingPrice }} <br>
                  <span class="barcode-tax" v-if="form.product.taxRate > 0">( {{ form.product.taxRate }}% {{ $t("TAX") }}
                    {{
                      form.product.taxType }} )</span> </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VueBarcode from 'vue-barcode'
import { mapGetters } from 'vuex'
import Form from 'vform'

export default {
  middleware: ['auth', 'check-permissions'],
  metaInfo() {
    return { title: this.$t('products.list.barcode.page_title') }
  },
  components: {
    barcode: VueBarcode,
  },

  data: () => ({
    breadcrumbsCurrent: 'products.list.barcode.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'products.list.barcode.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'products.list.barcode.breadcrumbs_second',
        url: 'products.index',
      },
      {
        name: 'products.list.barcode.breadcrumbs_active',
        url: '',
      },
    ],
    generateWithPrice: false,
    form: new Form({
      product: '',
      paperSize: 0,
      quantity: 10,
    }),
    pageSize: 'print-page-a4',
    perPage: 'per-page-40',
    showBarcode: false,
    quantity: 10,
    totalPages: 0,
    productPrefix: '',
    pages: [],
  }),
  computed: {
    ...mapGetters('operations', ['items', 'appInfo']),
  },
  created() {
    this.getProducts()
    this.productPrefix = this.appInfo.productPrefix
  },
  methods: {
    // get all products
    async getProducts() {
      await this.$store.dispatch('operations/allData', {
        path: '/api/all-products',
      })
    },

    // update class name
    updateClassName() {
      var hasError = false

      if (!this.form.product) {
        this.form.errors.set({
          product: 'This product field is required',
        })
        hasError = true
      }
      if (!this.form.paperSize) {
        this.form.errors.set({
          paperSize: 'This paper size field is required',
        })
        hasError = true
      }

      if (!hasError) {
        this.pages = []
        this.pageSize =
          this.form.paperSize % 2 == 0 ? 'print-page-a4' : 'print-page-non-a4'

        this.perPage = 'per-page-' + this.form.paperSize
        this.totalPages = Math.ceil(this.quantity / this.form.paperSize)

        for (let page = 0; page < this.totalPages; page++) {
          this.pages.push(
            this.quantity > this.form.paperSize
              ? this.form.paperSize
              : this.quantity
          )
          this.quantity = this.quantity - this.form.paperSize
        }
      }
    },

    // update quantity
    async generateBarcode() {
      console.log(this.appInfo.currency.symbol);
      this.quantity = Number(this.form.quantity)
      await this.updateClassName()
      this.showBarcode = true
    },

    async print() {
      // Pass the element id here
      await this.$htmlToPaper('printMe')
    },
  },
}
</script>

<style scoped>
.barcode-tax {
  font-size: 8px;
}
</style>
