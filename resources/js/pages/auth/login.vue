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
                <img v-if="appInfo" :src="appInfo.blackLogo" :alt="appInfo.companyName" class="lg-logo img-fluid" />
                <p class="text-muted mb-4 mt-2">{{ $t("login_txt") }}</p>
                <form @submit.prevent="login" @keydown="form.onKeydown($event)">
                  <div class="form-group mb-3">
                    <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }"
                      class="form-control rounded-pill border-0 shadow-sm px-4 text-primary" type="email" name="email"
                      :placeholder="$t('email_placeholder')" />
                    <has-error :form="form" field="email" />
                  </div>
                  <div class="form-group mb-3">
                    <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }"
                      class="form-control rounded-pill border-0 shadow-sm px-4 text-primary" type="password"
                      name="password" :placeholder="$t('password_placeholder')" />
                    <has-error :form="form" field="password" />
                  </div>
                  <div class="row mb-5">
                    <div class="col-md-6">
                      <checkbox v-model="remember" name="remember">
                        {{ $t("remember_me") }}
                      </checkbox>
                    </div>
                    <div class="col-md-6 text-right">
                      <router-link :to="{ name: 'password.request' }" class="ml-auto my-auto">
                        {{ $t("forgot_password") }}
                      </router-link>
                    </div>
                  </div>
                  <!-- Submit Button -->
                  <v-button :loading="form.busy"
                    class="btn btn-primary btn-block text-uppercase mb-2 rounded-pill shadow-sm">
                    <i class="fas fa-sign-in-alt" />
                    <strong>{{ $t("login") }}</strong>
                  </v-button>
                </form>
              </div>
              <!-- Login  Credentials For Demo -->
              <div class="col-12 mt-4">
                <div class="card" v-if="isDemoMode">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12">
                        <h3 class="text-center font-bold font-up danger-text">
                          Login Credentials
                        </h3>
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-bordered red-border text-center">
                        <thead>
                          <tr>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th scope="row">Super Admin</th>
                            <td>superadmin@acculance.com</td>
                            <td>acculance2022</td>
                            <td scope="row">
                              <button v-tooltip="$t('Login as super admin')" class="btn" @click="
                                loginCredential(
                                  'superadmin@acculance.com',
                                  'acculance2022'
                                )
                                ">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                  stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">Manager</th>
                            <td>manager@acculance.com</td>
                            <td>manager2022</td>
                            <td scope="row">
                              <button v-tooltip="$t('Login as super manager')" class="btn" @click="
                                loginCredential(
                                  'manager@acculance.com',
                                  'manager2022'
                                )
                                ">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                  stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">Salesman</th>
                            <td>sales@acculance.com</td>
                            <td>sales2022</td>
                            <td scope="row">
                              <button v-tooltip="$t('Login as super salesman')" class="btn" @click="
                                loginCredential(
                                  'sales@acculance.com',
                                  'sales2022'
                                )
                                ">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                  stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                              </button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="w-100 text-center mt-4">
                  <p>
                    {{ $t("Acculance v4.0.0. Developed by") }}
                    <a href="https://codeshaper.net/" class="font-italic text-muted" target="__blank">
                      <u>Codeshaper</u>
                    </a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End -->
    </div>
  </div>
</template>
<script>
import Form from "vform";
import Cookies from "js-cookie";
import { mapGetters } from "vuex";
import Button from "../../components/Button.vue";
export default {
  components: { Button },
  layout: "basic",
  middleware: "guest",
  metaInfo() {
    return { title: this.$t("login") };
  },
  data: () => ({
    form: new Form({
      email: "",
      password: "",
    }),
    remember: false,
    appName: window.config.appName,
    isDemoMode: window.config.isDemoMode,
  }),
  // Map Getters
  computed: {
    ...mapGetters("operations", ["appInfo"]),
  },
  methods: {
    async login() {
      // Submit the form.
      const { data } = await this.form.post("/api/login");
      // Save the token.
      this.$store.dispatch("auth/saveToken", {
        token: data.token,
        remember: this.remember,
      });
      // Fetch the user.
      await this.$store.dispatch("auth/fetchUser");
      // Redirect home.
      this.redirect();
    },
    redirect() {
      const intendedUrl = Cookies.get("intended_url");
      if (intendedUrl) {
        Cookies.remove("intended_url");
        this.$router.push({ path: intendedUrl });
      } else {
        this.$router.push({ name: "home" });
      }
    },
    loginCredential(email, pass) {
      this.form.email = email;
      this.form.password = pass;
      this.login();
    },
  },
};
</script>
