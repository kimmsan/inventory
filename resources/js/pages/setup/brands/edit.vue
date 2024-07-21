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
        <form role="form" enctype="multipart/form-data" @submit.prevent="updateBrand" @keydown="form.onKeydown($event)">
          <div class="card">
            <div class="card-header setings-header">
              <div class="col-xl-4 col-4">
                <h3 class="card-title">
                  {{ $t("setup.brands.edit.form_title") }}
                </h3>
              </div>
              <div class="col-xl-8 col-8 float-right text-right">
                <router-link :to="{ name: 'brands.index' }" class="btn btn-dark float-right">
                  <i class="fas fa-long-arrow-alt-left" />
                  {{ $t("common.back") }}
                </router-link>
              </div>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label for="name">{{ $t("setup.brands.common.brand_name") }}
                  <span class="required">*</span></label>
                <input id="name" v-model="form.name" type="text" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }" name="name" :placeholder="$t('setup.brands.common.brand_name_placeholder')
                    " />
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
                <label for="note">{{ $t("common.note") }}</label>
                <textarea id="note" v-model="form.note" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('note') }" :placeholder="$t('common.note_placeholder')" />
                <has-error :form="form" field="note" />
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
                <label for="image">{{ $t("common.image") }}</label>
                <div class="custom-file">
                  <input id="image" type="file" class="custom-file-input" name="image"
                    :class="{ 'is-invalid': form.errors.has('image') }" @change="onFileChange" />
                  <label class="custom-file-label" for="image">{{
                    $t("common.choose_file")
                  }}</label>
                </div>
                <has-error :form="form" field="image" />
                <div class="bg-light mt-4 w-25">
                  <img v-if="url" :src="url" class="img-fluid" :alt="$t('common.image_alt')" />
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
import axios from "axios";
import Form from "vform";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("setup.brands.edit.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "setup.brands.edit.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "setup.brands.edit.breadcrumbs_first",
        url: "home",
      },
      {
        name: "setup.brands.edit.breadcrumbs_second",
        url: "setup.index",
      },
      {
        name: "setup.brands.edit.breadcrumbs_third",
        url: "brands.index",
      },
      {
        name: "setup.brands.edit.breadcrumbs_active",
        url: "",
      },
    ],
    url: null,
    form: new Form({
      name: "",
      shortCode: "",
      image: "",
      note: "",
      status: 1,
    }),
    loading: true,
  }),

  created() {
    this.getBrand();
  },
  methods: {
    // get brand
    async getBrand() {
      const { data } = await axios.get(
        window.location.origin + "/api/brands/" + this.$route.params.slug
      );
      this.form.name = data.data.name;
      this.form.shortCode = data.data.code;
      this.form.note = data.data.note;
      this.form.status = data.data.status;
      this.url = data.data.image;
    },

    // vue file upload
    onFileChange(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      if (
        file.size < 2111775 &&
        (file.type === "image/jpeg" ||
          file.type === "image/png" ||
          file.type === "image/gif")
      ) {
        reader.onloadend = () => {
          this.form.image = reader.result;
        };
        reader.readAsDataURL(file);
        this.url = URL.createObjectURL(file);
      } else {
        Swal.fire(
          this.$t("common.error"),
          this.$t("common.image_error"),
          "error"
        );
      }
    },
    // update brand
    async updateBrand() {
      await this.form
        .patch(
          window.location.origin + "/api/brands/" + this.$route.params.slug
        )
        .then((response) => {
          toast.fire({
            type: "success",
            title: this.$t("setup.brands.edit.success_msg"),
          });
          this.$router.push({ name: "brands.index" });
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
