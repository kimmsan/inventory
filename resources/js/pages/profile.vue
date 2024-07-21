<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row">
      <div v-if="user" class="col-md-12 col-lg-3">
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <img class="profile-user-img img-fluid img-circle" :src="user.photo_url" :alt="$t('common.image_alt')" />
            </div>
            <h3 class="profile-username text-center">{{ user.name }}</h3>
            <p class="text-muted text-center">{{ user.roles[0] }}</p>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
      <!-- /.col -->
      <div class="col-md-12 col-lg-9">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">{{ $t("user_profile.form_title") }}</h3>
            <router-link :to="{ name: 'home' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t("common.back") }}
            </router-link>
          </div>
          <div class="card-body">
            <form class="form-horizontal" @submit.prevent="updateProfile" @keydown="form.onKeydown($event)">
              <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label">{{ $t("common.name") }}
                  <span class="required">*</span></label>
                <div class="col-sm-10">
                  <input type="text" v-model="form.name" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('name') }" id="name"
                    :placeholder="$t('common.name_placeholder')" />
                  <has-error :form="form" field="name" />
                </div>
              </div>
              <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">{{ $t("common.email") }}
                  <span class="required">*</span></label>
                <div class="col-sm-10">
                  <input type="email" v-model="form.email" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('email') }" id="email"
                    :placeholder="$t('common.email_placeholder')" />
                  <has-error :form="form" field="email" />
                </div>
              </div>
              <div class="form-group row">
                <label for="currentPassword" class="col-sm-2 col-form-label">{{
                  $t("user_profile.current_password")
                }}</label>
                <div class="col-sm-10">
                  <input type="password" v-model="form.currentPassword" class="form-control" :class="{
                    'is-invalid': form.errors.has('currentPassword'),
                  }" id="currentPassword" :placeholder="$t('user_profile.current_password')" />
                  <has-error :form="form" field="currentPassword" />
                </div>
              </div>
              <div class="form-group row">
                <label for="newPassword" class="col-sm-2 col-form-label">{{
                  $t("user_profile.new_password")
                }}</label>
                <div class="col-sm-10">
                  <input type="password" v-model="form.newPassword" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('newPassword') }" id="newPassword"
                    :placeholder="$t('user_profile.new_password_placeholder')" />
                  <has-error :form="form" field="newPassword" />
                </div>
              </div>
              <div class="form-group row">
                <label for="confirmPassword" class="col-sm-2 col-form-label">{{
                  $t("user_profile.confirm_password")
                }}</label>
                <div class="col-sm-10">
                  <input type="password" v-model="form.confirmPassword" class="form-control" :class="{
                    'is-invalid': form.errors.has('confirmPassword'),
                  }" id="confirmPassword" :placeholder="$t('user_profile.confirm_password_placeholder')
    " />
                  <has-error :form="form" field="confirmPassword" />
                </div>
              </div>
              <div class="form-group row">
                <div class="offset-sm-2 col-sm-10">
                  <v-button :loading="form.busy" class="btn btn-primary">
                    <i class="fas fa-edit" /> {{ $t("common.save_changes") }}
                  </v-button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import axios from "axios";

export default {
  middleware: "auth",
  metaInfo() {
    return { title: this.$t("user_profile.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "user_profile.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "user_profile.breadcrumbs_first",
        url: "home",
      },
      {
        name: "user_profile.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      name: "",
      email: "",
      currentPassword: "",
      newPassword: "",
      confirmPassword: "",
    }),
    loading: true,
    user: "",
    isDemoMode: window.config.isDemoMode,
  }),
  created() {
    this.getUser();
  },
  methods: {
    // get the user
    async getUser() {
      const { data } = await axios.get(
        window.location.origin + "/api/user"
      );
      this.user = data.data;
      this.form.name = data.data.name;
      this.form.email = data.data.email;
    },

    // update profile

    async updateProfile() {
      if (!this.isDemoMode) {
        await this.form
          .post(window.location.origin + "/api/update-profile")
          .then(() => {
            toast.fire({
              type: "success",
              title: this.$t("user_profile.success_msg"),
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

    }
  },
};
</script>

<style lang="scss" scoped></style>
