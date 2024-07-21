<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              {{ $t("cashbook.balances.create.form_title") }}
            </h3>
            <router-link
              :to="{ name: 'balances.index' }"
              class="btn btn-dark float-right"
            >
              <i class="fas fa-long-arrow-alt-left" /> {{ $t("common.back") }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form
            role="form"
            @submit.prevent="saveAdjustment"
            @keydown="form.onKeydown($event)"
          >
            <div class="card-body">
              <div class="row" v-if="items">
                <div class="form-group col-md-6">
                  <label for="account"
                    >{{ $t("common.account") }}
                    <span class="required">*</span></label
                  >
                  <v-select
                    v-model="form.account"
                    :options="items"
                    label="label"
                    :class="{ 'is-invalid': form.errors.has('account') }"
                    name="account"
                    :placeholder="$t('common.account_placeholder')"
                  />
                  <has-error :form="form" field="account" />
                </div>
                <div class="form-group col-md-6">
                  <label for="type">{{ $t("common.type") }}</label>
                  <select
                    id="type"
                    v-model="form.type"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('type') }"
                  >
                    <option value="1">
                      {{ $t("common.add_balance") }}
                    </option>
                    <option value="0">
                      {{ $t("common.remove_balance") }}
                    </option>
                  </select>
                  <has-error :form="form" field="type" />
                </div>
              </div>
              <div class="row" v-if="form.account">
                <div class="form-group col-md-6">
                  <label for="availableAmount">{{
                    $t("common.available_balance")
                  }}</label>
                  <input
                    id="availableAmount"
                    v-model="form.account.availableBalance"
                    type="number"
                    step="any"
                    class="form-control"
                    name="availableAmount"
                    readonly
                  />
                </div>
                <div class="form-group col-md-6">
                  <label for="amount"
                    >{{ $t("common.amount") }}
                    <span class="required">*</span></label
                  >
                  <input
                    id="amount"
                    v-model="form.amount"
                    type="number"
                    step="any"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('amount') }"
                    name="amount"
                    :placeholder="$t('common.amount_placeholder')"
                    :max="form.type == 0 ? form.account.availableBalance : ''"
                  />
                  <has-error :form="form" field="amount" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="date">{{ $t("common.date") }}</label>
                  <input
                    id="date"
                    v-model="form.date"
                    type="date"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('date') }"
                    name="date"
                  />
                  <has-error :form="form" field="date" />
                </div>
                <div class="form-group col-md-6">
                  <label for="status">{{ $t("common.status") }}</label>
                  <select
                    id="status"
                    v-model="form.status"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('status') }"
                  >
                    <option value="1">
                      {{ $t("common.active") }}
                    </option>
                    <option value="0">
                      {{ $t("common.in_active") }}
                    </option>
                  </select>
                  <has-error :form="form" field="status" />
                </div>
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
            <!-- /.card-body -->
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
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import { mapGetters } from "vuex";
export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("cashbook.balances.create.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "cashbook.balances.create.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "cashbook.balances.create.breadcrumbs_first",
        url: "home",
      },
      {
        name: "cashbook.balances.create.breadcrumbs_second",
        url: "",
      },
      {
        name: "cashbook.balances.create.breadcrumbs_third",
        url: "balances.index",
      },
      {
        name: "cashbook.balances.create.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      account: null,
      type: 1,
      amount: "",
      date: new Date().toISOString().slice(0, 10),
      note: "",
      status: 1,
    }),
    loading: true,
  }),
  computed: {
    ...mapGetters("operations", ["items", "appInfo"]),
  },
  created() {
    this.getAccounts();
  },
  methods: {
    // get all accounts
    async getAccounts() {
      await this.$store.dispatch("operations/allData", {
        path: "/api/all-accounts",
      });
      // assign default account
      if (this.items && this.items.length > 0) {
        let defaultAccountSlug = this.appInfo.defaultAccountSlug;
        this.form.account = this.items.find(
          (account) => account.slug == defaultAccountSlug
        );
      }
    },
    // save adjustment
    async saveAdjustment() {
      await this.form
        .post(window.location.origin + "/api/balances")
        .then(() => {
          toast.fire({
            type: "success",
            title: this.$t("cashbook.balances.create.success_msg"),
          });
          this.$router.push({ name: "balances.index" });
        })
        .catch(() => {
          toast.fire({ type: "error", title: this.$t("common.error_msg") });
        });
    },
  },
};
</script>
