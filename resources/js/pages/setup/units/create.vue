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
        <form
          role="form"
          @submit.prevent="saveUnit"
          @keydown="form.onKeydown($event)"
        >
          <div class="card">
            <div class="card-header setings-header">
              <div class="col-xl-4 col-4">
                <h3 class="card-title">
                  {{ $t("setup.units.create.form_title") }}
                </h3>
              </div>
              <div class="col-xl-8 col-8 float-right text-right">
                <router-link
                  :to="{ name: 'units.index' }"
                  class="btn btn-dark float-right"
                >
                  <i class="fas fa-long-arrow-alt-left" />
                  {{ $t("common.back") }}
                </router-link>
              </div>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label for="name"
                  >{{ $t("common.name") }}
                  <span class="required">*</span></label
                >
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                  name="name"
                  :placeholder="$t('common.name_placeholder')"
                />
                <has-error :form="form" field="name" />
              </div>
              <div class="form-group">
                <label for="code"
                  >{{ $t("common.code") }}
                  <span class="required">*</span></label
                >
                <input
                  id="code"
                  v-model="form.code"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('code') }"
                  name="code"
                  :placeholder="$t('common.code_placeholder')"
                />
                <has-error :form="form" field="code" />
              </div>
              <div class="form-group">
                <label for="status">{{ $t("common.status") }}</label>
                <select
                  id="status"
                  v-model="form.status"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('status') }"
                >
                  <option value="1">{{ $t("common.active") }}</option>
                  <option value="0">{{ $t("common.in_active") }}</option>
                </select>
                <has-error :form="form" field="status" />
              </div>
              <div class="form-group">
                <label for="note">{{ $t("common.note") }}</label>
                <textarea
                  id="note"
                  v-model="form.note"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('note') }"
                  :placeholder="$t('common.note_placeholder')"
                />
                <has-error :form="form" field="note" />
              </div>
            </div>
            <div class="card-footer">
              <v-button :loading="form.busy" class="btn btn-primary">
                <i class="fas fa-save" /> {{ $t("common.save") }}
              </v-button>
              <button
                type="reset"
                class="btn btn-secondary float-right"
                @click="form.reset()"
              >
                <i class="fas fa-power-off" /> {{ $t("common.reset") }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";
export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("setup.units.create.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "setup.units.create.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "setup.units.create.breadcrumbs_first",
        url: "home",
      },
      {
        name: "setup.units.create.breadcrumbs_second",
        url: "setup.index",
      },
      {
        name: "setup.units.create.breadcrumbs_third",
        url: "units.index",
      },
      {
        name: "setup.units.create.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      name: "",
      note: "",
      status: 1,
      code: "",
    }),
    loading: true,
  }),
  methods: {
    // save unit
    async saveUnit() {
      await this.form
        .post(window.location.origin + "/api/units")
        .then(() => {
          toast.fire({
            type: "success",
            title: this.$t("setup.units.create.success_msg"),
          });
          this.$router.push({ name: "units.index" });
        })
        .catch(() => {
          toast.fire({
            type: "error",
            title: this.$t("common.error_msg"),
          });
        });
    },
  },
};
</script>

