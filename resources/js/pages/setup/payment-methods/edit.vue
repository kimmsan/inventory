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
        <form role="form" @submit.prevent="updateMethod" @keydown="form.onKeydown($event)">
          <div class="card">
            <div class="card-header setings-header">
              <div class="col-xl-4 col-4">
                <h3 class="card-title">
                  {{ $t("setup.payment_methods.edit.form_title") }}
                </h3>
              </div>
              <div class="col-xl-8 col-8 float-right text-right">
                <router-link :to="{ name: 'paymentMethods.index' }" class="btn btn-dark float-right">
                  <i class="fas fa-long-arrow-alt-left" />
                  {{ $t("common.back") }}
                </router-link>
              </div>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label for="name">{{ $t("common.name") }}
                  <span class="required">*</span></label>
                <input id="name" v-model="form.name" type="text" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }" name="name"
                  :placeholder="$t('common.name_placeholder')" />
                <has-error :form="form" field="name" />
              </div>

              <div class="form-group">
                <label for="shortCode">{{ $t("setup.common.sort_code") }}
                  <span class="required">*</span></label>
                <input id="shortCode" v-model="form.shortCode" type="text" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('shortCode') }" name="shortCode"
                  :placeholder="$t('setup.common.sort_code_placeholder')" />
                <has-error :form="form" field="shortCode" />
              </div>
              <div class="form-group">
                <label for="status">{{ $t("common.status") }}</label>
                <select id="status" v-model="form.status" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('status') }">
                  <option value="1">{{ $t("common.active") }}</option>
                  <option value="0">{{ $t("common.in_active") }}</option>
                </select>
                <has-error :form="form" field="status" />
              </div>

              <div class="form-group">
                <label for="note">{{ $t("common.note") }}</label>
                <textarea id="note" v-model="form.note" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('note') }" :placeholder="$t('common.note_placeholder')" />
                <has-error :form="form" field="note" />
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
import axios from "axios";
import Form from "vform";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("setup.payment_methods.edit.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "setup.payment_methods.edit.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "setup.payment_methods.edit.breadcrumbs_first",
        url: "home",
      },
      {
        name: "setup.payment_methods.edit.breadcrumbs_second",
        url: "setup.index",
      },
      {
        name: "setup.payment_methods.edit.breadcrumbs_third",
        url: "paymentMethods.index",
      },
      {
        name: "setup.payment_methods.edit.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      name: "",
      shortCode: "",
      status: 1,
      code: "",
    }),
    loading: true,
  }),

  mounted() {
    this.getMethod();
  },
  methods: {
    // get payment method
    async getMethod() {
      const { data } = await axios.get(
        window.location.origin +
        "/api/payment-methods/" +
        this.$route.params.slug
      );
      this.form.name = data.name;
      this.form.shortCode = data.code;
      this.form.note = data.note;
      this.form.status = data.status;
    },

    // update payment method
    async updateMethod() {
      await this.form
        .patch(
          window.location.origin +
          "/api/payment-methods/" +
          this.$route.params.slug
        )
        .then((response) => {
          toast.fire({
            type: "success",
            title: this.$t("setup.payment_methods.edit.success_msg"),
          });
          this.$router.push({ name: "paymentMethods.index" });
        })
        .catch(() => {
          toast.fire({
            type: "error",
            title: this.$t("common.error"),
          });
        });
    },
  },
};
</script>
