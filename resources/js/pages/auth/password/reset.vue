<template>
  <div class="container-fluid">
    <div class="row no-gutter">
      <!-- The image half -->
      <div class="col-md-6 d-none d-md-flex bg-image"></div>
      <!-- The content half -->
      <div class="col-md-6 bg-light">
        <div class="auth-wrapper d-flex align-items-center py-5">
          <!-- Demo content-->
          <div class="container">
            <div class="row">
              <div class="col-lg-10 col-xl-7 mx-auto">
                <img
                  v-if="appInfo"
                  :src="appInfo.blackLogo"
                  :alt="appInfo.companyName"
                  class="lg-logo img-fluid"
                />
                <alert-success :form="form" :message="status" />
                <form @submit.prevent="reset" @keydown="form.onKeydown($event)">
                  <div class="form-group mb-3">
                    <input
                      v-model="form.email"
                      :class="{ 'is-invalid': form.errors.has('email') }"
                      class="form-control rounded-pill border-0 shadow-sm px-4 text-primary"
                      type="email"
                      name="email"
                      placeholder="Enter your email address"
                      readonly
                    />
                    <has-error :form="form" field="email" />
                  </div>
                  <div class="form-group mb-3">
                    <input
                      v-model="form.password"
                      :class="{ 'is-invalid': form.errors.has('password') }"
                      class="form-control rounded-pill border-0 shadow-sm px-4 text-primary"
                      type="password"
                      name="password"
                      placeholder="Enter your password"
                    />
                    <has-error :form="form" field="password" />
                  </div>
                  <div class="form-group mb-3">
                    <input
                      v-model="form.password_confirmation"
                      :class="{
                        'is-invalid': form.errors.has('password_confirmation'),
                      }"
                      class="form-control rounded-pill border-0 shadow-sm px-4 text-primary"
                      type="password"
                      name="password_confirmation"
                      placeholder="Enter confirm password"
                    />
                  </div>

                  <!-- Submit Button -->
                  <v-button
                    :loading="form.busy"
                    class="btn btn-primary btn-block text-uppercase mb-2 rounded-pill shadow-sm"
                  >
                    <i class="fas fa-sign-in-alt" />
                    <strong>{{ $t("reset_password") }}</strong>
                  </v-button>
                  <div class="text-center d-flex justify-content-between mt-4">
                    <router-link
                      :to="{ name: 'login' }"
                      class="ml-auto my-auto"
                    >
                      {{ $t("error_not_found.back_to_login") }}
                    </router-link>
                  </div>
                </form>
              </div>
              <div class="w-100 text-center mt-4">
                <p>
                  {{ $t("Acculance v4.0.0. Developed by") }}
                  <a
                    href="https://codeshaper.net/"
                    class="font-italic text-muted"
                    target="__blank"
                  >
                    <u>Codeshaper</u>
                  </a>
                </p>
              </div>
            </div>
          </div>
          <!-- End -->
        </div>
      </div>
      <!-- End -->
    </div>
  </div>
</template>

<script>
import Form from "vform";
import { mapGetters } from "vuex";

export default {
  layout: "basic",
  middleware: "guest",
  metaInfo() {
    return { title: this.$t("reset_password") };
  },

  data: () => ({
    status: "",
    form: new Form({
      token: "",
      email: "",
      password: "",
      password_confirmation: "",
    }),
  }),

  // Map Getters
  computed: {
    ...mapGetters("operations", ["appInfo"]),
  },

  created() {
    this.form.email = this.$route.query.email;
    this.form.token = this.$route.params.token;
  },

  methods: {
    async reset() {
      const { data } = await this.form.post("/api/password/reset");
      this.status = data.status;
      this.form.reset();
    },
  },
};
</script>
