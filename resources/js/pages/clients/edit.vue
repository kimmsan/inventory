<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">{{ $t('clients.edit.form_title') }}</h3>
            <router-link :to="{ name: 'clients.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="saveClient" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="name">{{ $t('common.name') }}
                    <span class="required">*</span></label>
                  <input id="name" v-model="form.name" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('name') }" name="name"
                    :placeholder="$t('common.name_placeholder')" />
                  <has-error :form="form" field="name" />
                </div>
                <div class="form-group col-md-6">
                  <label for="email">{{ $t('common.email') }}</label>
                  <input id="email" v-model="form.email" type="email" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('email') }" name="email"
                    :placeholder="$t('common.email_placeholder')" />
                  <has-error :form="form" field="email" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="phoneNumber">{{ $t('common.contact_number') }}
                    <span class="required">*</span></label>
                  <input id="phoneNumber" v-model="form.phoneNumber" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('phoneNumber') }" name="phoneNumber"
                    :placeholder="$t('common.contact_number_placeholder')" />
                  <has-error :form="form" field="phoneNumber" />
                </div>
                <div class="form-group col-md-6">
                  <label for="companyName">{{
                    $t('common.company_name')
                  }}</label>
                  <input id="companyName" v-model="form.companyName" type="companyName" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('companyName') }" name="companyName"
                    :placeholder="$t('common.company_name_placeholder')" />
                  <has-error :form="form" field="companyName" />
                </div>
              </div>
              <div class="form-group">
                <label for="address">{{ $t('common.address') }}</label>
                <textarea id="address" v-model="form.address" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('address') }" :placeholder="$t('common.address_placeholder')" />
                <has-error :form="form" field="address" />
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="image">{{ $t('common.image') }}</label>
                  <div class="custom-file">
                    <input id="image" type="file" class="custom-file-input" name="image"
                      :class="{ 'is-invalid': form.errors.has('image') }" @change="onFileChange" />
                    <label class="custom-file-label" for="image">{{
                      $t('common.choose_file')
                    }}</label>
                  </div>
                  <has-error :form="form" field="image" />
                  <div class="bg-light mt-4 w-25">
                    <img v-if="url" :src="url" class="img-fluid" :alt="$t('common.image_alt')" />
                  </div>
                </div>
                <div class="form-group col-md-6">
                  <label for="status">{{ $t('common.status') }}</label>
                  <select id="status" v-model="form.status" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('status') }">
                    <option value="1">{{ $t('common.active') }}</option>
                    <option value="0">{{ $t('common.in_active') }}</option>
                  </select>
                  <has-error :form="form" field="status" />
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <v-button :loading="form.busy" class="btn btn-primary">
                <i class="fas fa-edit" /> {{ $t('common.save_changes') }}
              </v-button>
              <button type="reset" class="btn btn-secondary float-right" @click="form.reset()">
                <i class="fas fa-power-off" /> {{ $t('common.reset') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import axios from 'axios'

export default {
  middleware: ['auth', 'check-permissions'],
  metaInfo() {
    return { title: this.$t('clients.edit.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'clients.edit.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'clients.edit.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'clients.edit.breadcrumbs_second',
        url: 'clients.index',
      },
      {
        name: 'clients.edit.breadcrumbs_active',
        url: '',
      },
    ],
    form: new Form({
      name: '',
      email: '',
      phoneNumber: '',
      companyName: '',
      address: '',
      image: '',
      status: 1,
    }),
    loading: true,
    url: null,
  }),
  mounted() {
    this.getClient()
  },
  methods: {
    // get client
    async getClient() {
      const { data } = await axios.get(
        window.location.origin + '/api/clients/' + this.$route.params.slug
      )
      this.form.name = data.data.name
      this.form.clientID = data.data.clientID
      this.form.email = data.data.email
      this.form.phoneNumber = data.data.phoneNumber
      this.form.companyName = data.data.companyName
      this.form.address = data.data.address
      this.form.status = data.data.status
      this.url = data.data.image
    },

    // vue file upload
    onFileChange(e) {
      const file = e.target.files[0]
      const reader = new FileReader()
      if (
        file.size < 2111775 &&
        (file.type === 'image/jpeg' ||
          file.type === 'image/png' ||
          file.type === 'image/gif')
      ) {
        reader.onloadend = () => {
          this.form.image = reader.result
        }
        reader.readAsDataURL(file)
        this.url = URL.createObjectURL(file)
      } else {
        Swal.fire(
          this.$t('common.error'),
          this.$t('common.image_error'),
          'error'
        )
      }
    },

    // update client
    async saveClient() {
      await this.form
        .patch(
          window.location.origin + '/api/clients/' + this.$route.params.slug
        )
        .then((response) => {
          toast.fire({
            type: 'success',
            title: this.$t('clients.edit.success_msg'),
          })
          this.$router.push({ name: 'clients.index' })
        })
        .catch(() => {
          toast.fire({
            type: 'error',
            title: this.$t('common.error_msg'),
          })
        })
    },
  },
}
</script>

