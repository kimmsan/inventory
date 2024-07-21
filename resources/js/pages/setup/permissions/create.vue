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
          @submit.prevent="savePermission"
          @keydown="form.onKeydown($event)"
        >
          <div class="card">
            <div class="card-header setings-header">
              <div class="col-xl-4 col-4">
                <h3 class="card-title">
                  {{ $t("setup.permissions.create.form_title") }}
                </h3>
              </div>
              <div class="col-xl-8 col-8 float-right text-right">
                <router-link
                  :to="{ name: 'permissions.index' }"
                  class="btn btn-dark float-right"
                >
                  <i class="fas fa-long-arrow-alt-left" />
                  {{ $t("common.back") }}
                </router-link>
              </div>
            </div>
            <div class="card-body">
              <div class="form-group col-md-12">
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
              <div class="form-group col-md-12">
                <label for="guardName"
                  >{{ $t("setup.permissions.common.guard_name") }}
                  <span class="required">*</span></label
                >
                <input
                  id="guardName"
                  v-model="form.guardName"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('guardName') }"
                  name="guardName"
                  :placeholder="
                    $t('setup.permissions.common.guard_name_placeholder')
                  "
                />
                <has-error :form="form" field="guardName" />
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
    return { title: this.$t("setup.permissions.create.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "setup.permissions.create.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "setup.permissions.create.breadcrumbs_first",
        url: "home",
      },
      {
        name: "setup.permissions.create.breadcrumbs_second",
        url: "setup.index",
      },
      {
        name: "setup.permissions.create.breadcrumbs_third",
        url: "permissions.index",
      },
      {
        name: "setup.permissions.create.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      name: "",
      guardName: "",
    }),
    loading: true,
  }),
  methods: {
    // save permission
    async savePermission() {
      await this.form
        .post(window.location.origin + "/api/permissions")
        .then(() => {
          toast.fire({
            type: "success",
            title: this.$t("setup.permissions.create.success_msg"),
          });
          this.$router.push({ name: "permissions.index" });
        })
        .catch(() => {
          toast.fire({ type: "error", title: this.$t("common.error_msg") });
        });
    },
  },
};
</script>
