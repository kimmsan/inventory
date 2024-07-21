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
                {{ $t("setup.sms_configuration.index.page_title") }}
              </h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="twilio_account_sid">{{
                    $t("setup.sms_configuration.form.twilio_account_sid")
                  }}
                    <span class="required">*</span></label>
                  <input id="twilio_account_sid" v-model="form.twilio_account_sid" type="text" class="form-control"
                    :class="{
                      'is-invalid': form.errors.has('twilio_account_sid'),
                    }" name="twilio_account_sid" :placeholder="$t('setup.sms_configuration.form.twilio_account_sid')
  " required />
                  <has-error :form="form" field="twilio_account_sid" />
                </div>
                <div class="form-group col-md-12">
                  <label for="twilio_auth_token">{{ $t("setup.sms_configuration.form.twilio_auth_token") }}
                    <span class="required">*</span></label>
                  <input id="twilio_auth_token" v-model="form.twilio_auth_token" type="text" class="form-control" :class="{
                    'is-invalid': form.errors.has('twilio_auth_token'),
                  }" name="twilio_auth_token" :placeholder="$t('setup.sms_configuration.form.twilio_auth_token')
  " required />
                  <has-error :form="form" field="twilio_auth_token" />
                </div>
                <div class="form-group col-md-12">
                  <label for="twilio_from">{{ $t("setup.sms_configuration.form.twilio_from") }}
                    <span class="required">*</span></label>
                  <input id="twilio_from" v-model="form.twilio_from" type="text" class="form-control" :class="{
                    'is-invalid': form.errors.has('twilio_from'),
                  }" name="twilio_from" :placeholder="$t('setup.sms_configuration.form.twilio_from')
  " required />
                  <has-error :form="form" field="twilio_from" />
                </div>
                <div class="form-group col-md-12">
                  <label for="twilio_sms_service_sid">{{
                    $t("setup.sms_configuration.form.twilio_sms_service_sid")
                  }}
                    <span class="required">*</span></label>
                  <input id="twilio_sms_service_sid" v-model="form.twilio_sms_service_sid" type="text"
                    class="form-control" :class="{
                      'is-invalid': form.errors.has('twilio_sms_service_sid'),
                    }" name="twilio_sms_service_sid" :placeholder="$t('setup.sms_configuration.form.twilio_sms_service_sid')
  " required />
                  <has-error :form="form" field="twilio_sms_service_sid" />
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
    return { title: this.$t("setup.sms_configuration.index.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "setup.sms_configuration.index.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "setup.sms_configuration.index.breadcrumbs_first",
        url: "home",
      },
      {
        name: "setup.sms_configuration.index.breadcrumbs_second",
        url: "setup.index",
      },
      {
        name: "setup.sms_configuration.index.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      twilio_account_sid: "",
      twilio_auth_token: "",
      twilio_from: "",
      twilio_sms_service_sid: "",
    }),
    isDemoMode: window.config.isDemoMode,
  }),

  created() {
    this.getSMSServerValues();
  },
  methods: {
    // get all current values
    async getSMSServerValues() {
      const { data } = await axios.get(
        window.location.origin + "/api/sms-configuration/"
      );
      this.form.twilio_account_sid = data.twilio_account_sid.value;
      this.form.twilio_auth_token = data.twilio_auth_token.value;
      this.form.twilio_from = data.twilio_from.value;
      this.form.twilio_sms_service_sid = data.twilio_sms_service_sid.value;
    },

    // update settings
    async updateSettings() {

      if (!this.isDemoMode) {
        await this.form
          .post(window.location.origin + "/api/update-sms-configuration")
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
      } else {
        toast.fire({
          type: "warning",
          title: this.$t("You are not allowed to do this in demo version."),
        });
      }
    }
  },
};
</script>
