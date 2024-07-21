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
                {{ $t("setup.mail_configuration.index.page_title") }}
              </h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="mail_mailer">{{ $t("setup.mail_configuration.form.mail_mailer") }}
                    <span class="required">*</span></label>
                  <input id="mail_mailer" v-model="form.mail_mailer" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('mail_mailer') }" name="mail_mailer" :placeholder="$t('setup.mail_configuration.form.mail_mailer')
                      " required />
                  <has-error :form="form" field="mail_mailer" />
                </div>
                <div class="form-group col-md-12">
                  <label for="mail_host">{{ $t("setup.mail_configuration.form.mail_host") }}
                    <span class="required">*</span></label>
                  <input id="mail_host" v-model="form.mail_host" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('mail_host') }" name="mail_host"
                    :placeholder="$t('setup.mail_configuration.form.mail_host')" required />
                  <has-error :form="form" field="mail_host" />
                </div>
                <div class="form-group col-md-12">
                  <label for="mail_port">{{ $t("setup.mail_configuration.form.mail_port") }}
                    <span class="required">*</span></label>
                  <input id="mail_port" v-model="form.mail_port" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('mail_port') }" name="mail_port"
                    :placeholder="$t('setup.mail_configuration.form.mail_port')" required />
                  <has-error :form="form" field="mail_port" />
                </div>
                <div class="form-group col-md-12">
                  <label for="mail_username">{{ $t("setup.mail_configuration.form.mail_username") }}
                    <span class="required">*</span></label>
                  <input id="mail_username" v-model="form.mail_username" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('mail_username') }" name="mail_username" :placeholder="$t('setup.mail_configuration.form.mail_username')
                      " required />
                  <has-error :form="form" field="mail_username" />
                </div>
                <div class="form-group col-md-12">
                  <label for="mail_password">{{ $t("setup.mail_configuration.form.mail_password") }}
                    <span class="required">*</span></label>
                  <input id="mail_password" v-model="form.mail_password" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('mail_password') }" name="mail_password" :placeholder="$t('setup.mail_configuration.form.mail_password')
                      " required />
                  <has-error :form="form" field="mail_password" />
                </div>
                <div class="form-group col-md-12">
                  <label for="mail_encryption">{{ $t("setup.mail_configuration.form.mail_encryption") }}
                    <span class="required">*</span></label>
                  <input id="mail_encryption" v-model="form.mail_encryption" type="text" class="form-control" :class="{
                    'is-invalid': form.errors.has('mail_encryption'),
                  }" name="mail_encryption" :placeholder="$t('setup.mail_configuration.form.mail_encryption')"
                    required />
                  <has-error :form="form" field="mail_encryption" />
                </div>
                <div class="form-group col-md-12">
                  <label for="mail_from_address">{{
                    $t("setup.mail_configuration.form.mail_from_address")
                  }}
                    <span class="required">*</span></label>
                  <input id="mail_from_address" v-model="form.mail_from_address" type="text" class="form-control" :class="{
                    'is-invalid': form.errors.has('mail_from_address'),
                  }" name="mail_from_address" :placeholder="$t('setup.mail_configuration.form.mail_from_address')"
                    required />
                  <has-error :form="form" field="mail_from_address" />
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
import axios from "axios";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("setup.mail_configuration.index.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "setup.mail_configuration.index.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "setup.mail_configuration.index.breadcrumbs_first",
        url: "home",
      },
      {
        name: "setup.mail_configuration.index.breadcrumbs_second",
        url: "setup.index",
      },
      {
        name: "setup.mail_configuration.index.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      mail_mailer: "",
      mail_host: "",
      mail_port: "",
      mail_username: "",
      mail_password: "",
      mail_encryption: "",
      mail_from_address: "",
    }),
    isDemoMode: window.config.isDemoMode,
  }),

  created() {
    this.getMailServerValues();
  },
  methods: {
    // get all current values
    async getMailServerValues() {
      const { data } = await axios.get(
        window.location.origin + "/api/mail-configuration/"
      );
      this.form.mail_mailer = data.mail_mailer.value;
      this.form.mail_host = data.mail_host.value;
      this.form.mail_port = data.mail_port.value;
      this.form.mail_username = data.mail_username.value;
      this.form.mail_password = data.mail_password.value;
      this.form.mail_encryption = data.mail_encryption.value;
      this.form.mail_from_address = data.mail_from_address.value;
    },

    // update settings
    async updateSettings() {
      if (!this.isDemoMode) {
        await this.form
          .post(window.location.origin + "/api/update-mail-configuration")
          .then(() => {
            toast.fire({
              type: "success",
              title: this.$t("setup.general_settings.index.success_message"),
            });
          })
          .catch(() => {
            toast.fire({
              type: "error",
              title: this.$t("common.error_msg"),
            });
          });
      }
      else {
        toast.fire({
          type: "warning",
          title: this.$t("You are not allowed to do this in demo version."),
        });
      }
    },
  },
};
</script>
