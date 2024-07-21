<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->

    <div class="row">
      <div class="col-12 col-xl-3">
        <SettingsSidebar />
      </div>
      <div class="col-12 col-xl-9">
        <form role="form" @submit.prevent="updateSettings" @keydown="form.onKeydown($event)">
          <div class="card">
            <div class="card-header setings-header">
              <h3 class="card-title">
                {{ $t("setup.general_settings.index.page_title") }}
              </h3>
            </div>
            <div class="card-body">
              <strong>{{
                $t("setup.general_settings.index.company_info.info_title")
              }}</strong>
              <hr />
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="companyName">{{ $t("common.company_name") }}
                    <span class="required">*</span></label>
                  <input id="companyName" v-model="form.companyName" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('companyName') }" name="companyName"
                    :placeholder="$t('common.company_name_placeholder')" />
                  <has-error :form="form" field="companyName" />
                </div>
                <div class="form-group col-md-6">
                  <label for="companyTagline">{{
                    $t(
                      "setup.general_settings.index.company_info.company_tagline"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="companyTagline" v-model="form.companyTagline" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('companyTagline') }" name="companyTagline" :placeholder="$t(
                      'setup.general_settings.index.company_info.company_tagline_placeholder'
                    )
                      " />
                  <has-error :form="form" field="companyTagline" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="emailAddress">{{
                    $t(
                      "setup.general_settings.index.company_info.email_address"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="emailAddress" v-model="form.emailAddress" type="email" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('emailAddress') }" name="emailAddress" :placeholder="$t(
                          'setup.general_settings.index.company_info.email_address_placeholder'
                        )
                        " />
                  <has-error :form="form" field="emailAddress" />
                </div>
                <div class="form-group col-md-6">
                  <label for="phoneNumber">{{
                    $t(
                      "setup.general_settings.index.company_info.phone_number"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="phoneNumber" v-model="form.phoneNumber" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('phoneNumber') }" name="phoneNumber" :placeholder="$t(
                      'setup.general_settings.index.company_info.phone_number_placeholder'
                    )
                      " />
                  <has-error :form="form" field="name" />
                </div>
              </div>
              <div class="form-group">
                <label for="address">{{ $t("common.address") }}</label>
                <textarea id="address" v-model="form.address" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('address') }" :placeholder="$t('common.address_placeholder')" />
                <has-error :form="form" field="address" />
              </div>
              <br />
              <strong>{{
                $t("setup.general_settings.index.code_prefixes.prefix_title")
              }}</strong>
              <hr />
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="clientPrefix">{{
                    $t(
                      "setup.general_settings.index.code_prefixes.client_prefix"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="clientPrefix" v-model="form.clientPrefix" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('clientPrefix') }" name="clientPrefix" :placeholder="$t(
                      'setup.general_settings.index.code_prefixes.client_prefix_placeholder'
                    )
                      " />
                  <has-error :form="form" field="clientPrefix" />
                </div>
                <div class="form-group col-md-4">
                  <label for="supplierPrefix">{{
                    $t(
                      "setup.general_settings.index.code_prefixes.supplier_prefix"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="supplierPrefix" v-model="form.supplierPrefix" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('supplierPrefix') }" name="supplierPrefix" :placeholder="$t(
                      'setup.general_settings.index.code_prefixes.supplier_prefix_placeholder'
                    )
                      " />
                  <has-error :form="form" field="supplierPrefix" />
                </div>
                <div class="form-group col-md-4">
                  <label for="employeePrefix">{{
                    $t(
                      "setup.general_settings.index.code_prefixes.employee_prefix"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="employeePrefix" v-model="form.employeePrefix" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('employeePrefix') }" name="employeePrefix" :placeholder="$t(
                      'setup.general_settings.index.code_prefixes.employee_prefix_placeholder'
                    )
                      " />
                  <has-error :form="form" field="employeePrefix" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="proCatPrefix">{{
                    $t(
                      "setup.general_settings.index.code_prefixes.product_category_prefix"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="proCatPrefix" v-model="form.proCatPrefix" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('proCatPrefix') }" name="proCatPrefix" :placeholder="$t(
                      'setup.general_settings.index.code_prefixes.product_category_prefix_placeholder'
                    )
                      " />
                  <has-error :form="form" field="proCatPrefix" />
                </div>
                <div class="form-group col-md-4">
                  <label for="proSubCatPrefix">{{
                    $t(
                      "setup.general_settings.index.code_prefixes.product_sub_category_prefix"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="proSubCatPrefix" v-model="form.proSubCatPrefix" type="text" class="form-control" :class="{
                    'is-invalid': form.errors.has('proSubCatPrefix'),
                  }" name="proSubCatPrefix" :placeholder="$t(
  'setup.general_settings.index.code_prefixes.product_sub_category_prefix_placeholder'
)
  " />
                  <has-error :form="form" field="proSubCatPrefix" />
                </div>
                <div class="form-group col-md-4">
                  <label for="productPrefix">{{
                    $t(
                      "setup.general_settings.index.code_prefixes.product_prefix"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="productPrefix" v-model="form.productPrefix" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('productPrefix') }" name="productPrefix" :placeholder="$t(
                      'setup.general_settings.index.code_prefixes.product_prefix_placeholder'
                    )
                      " />
                  <has-error :form="form" field="productPrefix" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-lg-6 col-xl-3">
                  <label for="expCatPrefix">{{
                    $t(
                      "setup.general_settings.index.code_prefixes.expense_category_prefix"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="expCatPrefix" v-model="form.expCatPrefix" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('expCatPrefix') }" name="expCatPrefix" :placeholder="$t(
                      'setup.general_settings.index.code_prefixes.expense_category_prefix_placeholder'
                    )
                      " />
                  <has-error :form="form" field="expCatPrefix" />
                </div>
                <div class="form-group col-lg-6 col-xl-3">
                  <label for="expSubCatPrefix">{{
                    $t(
                      "setup.general_settings.index.code_prefixes.expense_sub_category_prefix"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="expSubCatPrefix" v-model="form.expSubCatPrefix" type="text" class="form-control" :class="{
                    'is-invalid': form.errors.has('expSubCatPrefix'),
                  }" name="expSubCatPrefix" :placeholder="$t(
  'setup.general_settings.index.code_prefixes.expense_sub_category_prefix_placeholder'
)
  " />
                  <has-error :form="form" field="expSubCatPrefix" />
                </div>
                <div class="form-group col-lg-6 col-xl-3">
                  <label for="purchasePrefix">{{
                    $t(
                      "setup.general_settings.index.code_prefixes.purchase_prefix"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="purchasePrefix" v-model="form.purchasePrefix" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('purchasePrefix') }" name="purchasePrefix" :placeholder="$t(
                      'setup.general_settings.index.code_prefixes.purchase_prefix_placeholder'
                    )
                      " />
                  <has-error :form="form" field="purchasePrefix" />
                </div>
                <div class="form-group col-lg-6 col-xl-3">
                  <label for="purchaseReturnPrefix">{{
                    $t(
                      "setup.general_settings.index.code_prefixes.purchase_return_prefix"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="purchaseReturnPrefix" v-model="form.purchaseReturnPrefix" type="text" class="form-control"
                    :class="{
                      'is-invalid': form.errors.has('purchaseReturnPrefix'),
                    }" name="purchaseReturnPrefix" :placeholder="$t(
  'setup.general_settings.index.code_prefixes.purchase_return_prefix_placeholder'
)
  " />
                  <has-error :form="form" field="purchaseReturnPrefix" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-lg-6 col-xl-3">
                  <label for="quotationPrefix">{{
                    $t(
                      "setup.general_settings.index.code_prefixes.quotation_prefix"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="quotationPrefix" v-model="form.quotationPrefix" type="text" class="form-control" :class="{
                    'is-invalid': form.errors.has('quotationPrefix'),
                  }" name="quotationPrefix" :placeholder="$t(
  'setup.general_settings.index.code_prefixes.quotation_prefix_placeholder'
)
  " />
                  <has-error :form="form" field="quotationPrefix" />
                </div>
                <div class="form-group col-lg-6 col-xl-3">
                  <label for="invoicePrefix">{{
                    $t(
                      "setup.general_settings.index.code_prefixes.invoice_prefix"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="invoicePrefix" v-model="form.invoicePrefix" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('invoicePrefix') }" name="invoicePrefix" :placeholder="$t(
                      'setup.general_settings.index.code_prefixes.invoice_prefix_placeholder'
                    )
                      " />
                  <has-error :form="form" field="invoicePrefix" />
                </div>
                <div class="form-group col-lg-6 col-xl-3">
                  <label for="invoiceReturnPrefix">{{
                    $t(
                      "setup.general_settings.index.code_prefixes.invoice_return_prefix"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="invoiceReturnPrefix" v-model="form.invoiceReturnPrefix" type="text" class="form-control"
                    :class="{
                      'is-invalid': form.errors.has('invoiceReturnPrefix'),
                    }" name="invoiceReturnPrefix" :placeholder="$t(
  'setup.general_settings.index.code_prefixes.invoice_return_prefix_placeholder'
)
  " />
                  <has-error :form="form" field="invoiceReturnPrefix" />
                </div>
                <div class="form-group col-lg-6 col-xl-3">
                  <label for="adjustmentPrefix">{{
                    $t(
                      "setup.general_settings.index.code_prefixes.invoice_adjustment_prefix"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="adjustmentPrefix" v-model="form.adjustmentPrefix" type="text" class="form-control" :class="{
                    'is-invalid': form.errors.has('adjustmentPrefix'),
                  }" name="adjustmentPrefix" :placeholder="$t(
  'setup.general_settings.index.code_prefixes.invoice_adjustment_prefix_placeholder'
)
  " />
                  <has-error :form="form" field="adjustmentPrefix" />
                </div>
              </div>
              <br />
              <strong>{{
                $t(
                  "setup.general_settings.index.default_elements.default_elements_title"
                )
              }}</strong>
              <hr />
              <div class="row">
                <div v-if="items" class="form-group col-md-6">
                  <label for="currency">{{
                    $t(
                      "setup.general_settings.index.default_elements.default_currency"
                    )
                  }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.currency" :options="items" label="label"
                    :class="{ 'is-invalid': form.errors.has('currency') }" name="currency" :placeholder="$t(
                      'setup.general_settings.index.default_elements.select_a_currency'
                    )
                      " />
                  <has-error :form="form" field="currency" />
                </div>
                <div class="form-group col-md-6">
                  <label for="language">{{
                    $t(
                      "setup.general_settings.index.default_elements.default_language"
                    )
                  }}
                    <span class="required">*</span></label>
                  <select v-model="form.language" name="" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('language') }">
                    <option value="en">
                      {{
                        $t(
                          "setup.general_settings.index.default_elements.english_en"
                        )
                      }}
                    </option>
                    <option value="hi">
                      {{
                        $t(
                          "setup.general_settings.index.default_elements.hindi_hi"
                        )
                      }}
                    </option>
                  </select>
                  <has-error :form="form" field="language" />
                </div>
              </div>
              <div class="row">
                <!--/Default client-->
                <div v-if="this.allClients" class="form-group col-md-4">
                  <label for="client">{{
                    $t(
                      "setup.general_settings.index.default_elements.default_client"
                    )
                  }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.defaultClient" :options="this.allClients" label="name"
                    :class="{ 'is-invalid': form.errors.has('defaultClient') }" name="client" :placeholder="$t(
                      'setup.general_settings.index.default_elements.select_a_client'
                    )
                      " />
                  <has-error :form="form" field="defaultClient" />
                </div>
                <!--/Default Account-->
                <div v-if="this.allAccounts" class="form-group col-md-4">
                  <label for="client">{{
                    $t(
                      "setup.general_settings.index.default_elements.default_account"
                    )
                  }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.defaultAccount" :options="allAccounts" label="bankName"
                    :class="{ 'is-invalid': form.errors.has('defaultAccount') }" name="account" :placeholder="$t(
                      'setup.general_settings.index.default_elements.select_a_account'
                    )
                      " />
                  <has-error :form="form" field="defaultAccount" />
                </div>
                <!--/Default VatRate-->
                <div v-if="this.allVatRates" class="form-group col-md-4">
                  <label for="vatRate">{{
                    $t(
                      "setup.general_settings.index.default_elements.default_vat_rate"
                    )
                  }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.defaultVatRate" :options="this.allVatRates" label="name"
                    :class="{ 'is-invalid': form.errors.has('defaultVatRate') }" name="vatRate" :placeholder="$t(
                      'setup.general_settings.index.default_elements.select_a_vat_rate'
                    )
                      " />
                  <has-error :form="form" field="defaultVatRate" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="logo">{{
                    $t("setup.general_settings.index.logo.white_logo")
                  }}</label>
                  <div class="custom-file">
                    <input id="logo" type="file" class="custom-file-input" name="logo"
                      :class="{ 'is-invalid': form.errors.has('logo') }" @change="onLogoChange" />
                    <label class="custom-file-label" for="logo">{{
                      $t("common.choose_file")
                    }}</label>
                  </div>
                  <has-error :form="form" field="logo" />
                  <div class="bg-light mt-4 w-25">
                    <img v-if="logo" :src="logo" class="img-fluid" alt="Logo" />
                  </div>
                </div>
                <div class="form-group col-md-6">
                  <label for="blackLogo">{{
                    $t("setup.general_settings.index.logo.black_logo")
                  }}</label>
                  <div class="custom-file">
                    <input id="blackLogo" type="file" class="custom-file-input" name="blackLogo"
                      :class="{ 'is-invalid': form.errors.has('blackLogo') }" @change="onBlackLogoChange" />
                    <label class="custom-file-label" for="blackLogo">{{
                      $t("common.choose_file")
                    }}</label>
                  </div>
                  <has-error :form="form" field="blackLogo" />
                  <div class="bg-light mt-4 w-25">
                    <img v-if="blackLogo" :src="blackLogo" class="img-fluid" alt="Black Logo" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="smallLogo">{{
                    $t("setup.general_settings.index.logo.small_logo")
                  }}</label>
                  <div class="custom-file">
                    <input id="smallLogo" type="file" class="custom-file-input" name="smallLogo"
                      :class="{ 'is-invalid': form.errors.has('smallLogo') }" @change="onSmallLogoChange" />
                    <label class="custom-file-label" for="smallLogo">{{
                      $t("common.choose_file")
                    }}</label>
                  </div>
                  <has-error :form="form" field="smallLogo" />
                  <div class="bg-light mt-4 w-25">
                    <img v-if="smallLogo" :src="smallLogo" class="img-fluid" alt="Small Logo" />
                  </div>
                </div>
                <div class="form-group col-md-6">
                  <label for="favicon">{{
                    $t("setup.general_settings.index.logo.favicon")
                  }}</label>
                  <div class="custom-file">
                    <input id="favicon" type="file" class="custom-file-input" name="favicon"
                      :class="{ 'is-invalid': form.errors.has('favicon') }" @change="onFaviconChange" />
                    <label class="custom-file-label" for="favicon">{{
                      $t("common.choose_file")
                    }}</label>
                  </div>
                  <has-error :form="form" field="favicon" />
                  <div class="bg-light mt-4 w-25">
                    <img v-if="favicon" :src="favicon" class="img-fluid" alt="Favicon" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="copyrightText">{{
                    $t(
                      "setup.general_settings.index.default_elements.copyright_text"
                    )
                  }}
                    <span class="required">*</span></label>
                  <input id="copyrightText" v-model="form.copyrightText" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('copyrightText') }" name="copyrightText"
                    placeholder="$t('setup.general_settings.index.default_elements.copyright_text_placeholder')" />
                  <has-error :form="form" field="copyrightText" />
                </div>
              </div>
            </div>
            <div class="card-footer">
              <v-button :loading="form.busy" class="btn btn-primary">
                <i class="fas fa-edit" /> {{ $t("common.save_changes") }}
              </v-button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import { mapGetters } from "vuex";
import { loadMessages } from "~/plugins/i18n";
import axios from "axios";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("setup.general_settings.index.page_title") };
  },
  data: () => ({
    allClients: [],
    allAccounts: [],
    allVatRates: [],
    breadcrumbsCurrent: "setup.general_settings.index.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "setup.general_settings.index.breadcrumbs_first",
        url: "home",
      },
      {
        name: "setup.general_settings.index.breadcrumbs_second",
        url: "setup.index",
      },
      {
        name: "setup.general_settings.index.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      companyName: "",
      companyTagline: "",
      emailAddress: "",
      phoneNumber: "",
      address: "",
      clientPrefix: "",
      supplierPrefix: "",
      employeePrefix: "",
      proCatPrefix: "",
      proSubCatPrefix: "",
      productPrefix: "",
      expCatPrefix: "",
      expSubCatPrefix: "",
      purchasePrefix: "",
      purchaseReturnPrefix: "",
      quotationPrefix: "",
      invoicePrefix: "",
      invoiceReturnPrefix: "",
      adjustmentPrefix: "",
      currency: "",
      language: "en",
      copyrightText: "",
      logo: "",
      blackLogo: "",
      smallLogo: "",
      favicon: "",
      defaultClient: "",
      defaultAccount: "",
      defaultVatRate: "",
    }),
    logo: "",
    blackLogo: "",
    smallLogo: "",
    favicon: "",
    isDemoMode: window.config.isDemoMode,
  }),
  computed: mapGetters({
    appInfo: "operations/appInfo",
    items: "operations/items",
  }),

  created() {
    this.getVatRates();
    this.getCurrencies();
    this.getClients();
    this.getAccounts();
    this.assignValues();
  },
  methods: {
    // get all product categories
    async getCurrencies() {
      await this.$store.dispatch("operations/allData", {
        path: "/api/all-currencies",
      });
    },
    // get all clients
    async getClients() {
      await axios.get("/api/all-clients").then((response) => {
        this.allClients = response.data.data;
        // assign default client
        if (this.allClients && this.allClients.length > 0) {
          let defaultClientSlug = this.appInfo.defaultClientSlug;
          this.form.defaultClient = this.allClients.find(
            (client) => client.slug === defaultClientSlug
          );
        }
      });
    },
    // get all accounts
    async getAccounts() {
      await axios.get("/api/all-accounts").then((response) => {
        this.allAccounts = response.data.data;
        // assign default account
        if (this.allAccounts && this.allAccounts.length > 0) {
          let defaultAccountSlug = this.appInfo.defaultAccountSlug;
          this.form.defaultAccount = this.allAccounts.find(
            (account) => account.slug === defaultAccountSlug
          );
        }
      });
    },
    // get all vat rates
    getVatRates() {
      axios.get("/api/all-vat-rates").then((response) => {
        this.allVatRates = response.data.data;
        // assign default vat rate
        if (this.allVatRates && this.allVatRates.length > 0) {
          let defaultVatRateSlug = this.appInfo.defaultVatRateSlug;
          this.form.defaultVatRate = this.allVatRates.find(
            (tax) => tax.slug === defaultVatRateSlug
          );
        }
      });
    },
    // assign values
    assignValues() {
      if (this.appInfo) {
        this.form.companyName = this.appInfo.companyName;
        this.form.companyTagline = this.appInfo.companyTagline;
        this.form.emailAddress = this.appInfo.email;
        this.form.phoneNumber = this.appInfo.phone;
        this.form.address = this.appInfo.address;
        this.form.clientPrefix = this.appInfo.clientPrefix;
        this.form.supplierPrefix = this.appInfo.supplierPrefix;
        this.form.employeePrefix = this.appInfo.employeePrefix;
        this.form.proCatPrefix = this.appInfo.proCatPrefix;
        this.form.proSubCatPrefix = this.appInfo.proSubCatPrefix;
        this.form.productPrefix = this.appInfo.productPrefix;
        this.form.expCatPrefix = this.appInfo.expCatPrefix;
        this.form.expSubCatPrefix = this.appInfo.expSubCatPrefix;
        this.form.purchasePrefix = this.appInfo.purchasePrefix;
        this.form.purchaseReturnPrefix = this.appInfo.purchaseReturnPrefix;
        this.form.quotationPrefix = this.appInfo.quotationPrefix;
        this.form.invoicePrefix = this.appInfo.invoicePrefix;
        this.form.invoiceReturnPrefix = this.appInfo.invoiceReturnPrefix;
        this.form.adjustmentPrefix = this.appInfo.adjustmentPrefix;
        this.form.currency = this.appInfo.currency;
        this.form.language = this.appInfo.language;
        this.logo = this.appInfo.logo;
        this.blackLogo = this.appInfo.blackLogo;
        this.smallLogo = this.appInfo.smallLogo;
        this.favicon = this.appInfo.favicon;
        this.form.copyrightText = this.appInfo.copyright;
      }
    },

    // vue logo upload
    onLogoChange(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      if (
        file.size < 2111775 &&
        (file.type === "image/jpeg" ||
          file.type === "image/png" ||
          file.type === "image/gif" ||
          file.type === "image/svg")
      ) {
        reader.onloadend = (file) => {
          this.form.logo = reader.result;
        };
        reader.readAsDataURL(file);
        this.logo = URL.createObjectURL(file);
      } else {
        Swal.fire(
          this.$t("common.error"),
          this.$t("common.image_error"),
          "error"
        );
      }
    },

    // vue black logo upload
    onBlackLogoChange(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      if (
        file.size < 2111775 &&
        (file.type === "image/jpeg" ||
          file.type === "image/png" ||
          file.type === "image/gif" ||
          file.type === "image/svg")
      ) {
        reader.onloadend = (file) => {
          this.form.blackLogo = reader.result;
        };
        reader.readAsDataURL(file);
        this.blackLogo = URL.createObjectURL(file);
      } else {
        Swal.fire(
          this.$t("common.error"),
          this.$t("common.image_error"),
          "error"
        );
      }
    },

    // vue small logo upload
    onSmallLogoChange(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      if (
        file.size < 2111775 &&
        (file.type === "image/jpeg" ||
          file.type === "image/png" ||
          file.type === "image/gif" ||
          file.type === "image/svg")
      ) {
        reader.onloadend = (file) => {
          this.form.smallLogo = reader.result;
        };
        reader.readAsDataURL(file);
        this.smallLogo = URL.createObjectURL(file);
      } else {
        Swal.fire(
          this.$t("common.error"),
          this.$t("common.image_error"),
          "error"
        );
      }
    },

    // vue favicon upload
    onFaviconChange(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      if (
        file.size < 2111775 &&
        (file.type === "image/jpeg" ||
          file.type === "image/png" ||
          file.type === "image/gif")
      ) {
        reader.onloadend = (file) => {
          this.form.favicon = reader.result;
        };
        reader.readAsDataURL(file);
        this.favicon = URL.createObjectURL(file);
      } else {
        Swal.fire(
          this.$t("common.error"),
          this.$t("common.image_error"),
          "error"
        );
      }
    },

    // update settings
    async updateSettings() {
      // update locale
      let locale = this.form.language;
      if (this.$i18n.locale !== locale) {
        loadMessages(locale);
        this.$store.dispatch("lang/setLocale", { locale });
      }

      if (!this.isDemoMode) {
        await this.form
          .post(window.location.origin + "/api/update-settings")
          .then(() => {
            toast.fire({
              type: "success",
              title: this.$t("setup.general_settings.index.success_message"),
            });
            window.location.reload();
          })
          .catch(() => {
            toast.fire({
              type: "error",
              title: this.$t("common.error_msg"),
            });
          });
      } else {
        toast.fire({
          type: "warning",
          title: this.$t("You are not allowed to do this in demo version."),
        });
      }
    },
  },
};
</script>
