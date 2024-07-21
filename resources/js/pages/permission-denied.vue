<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="col-lg-12 col-xl-8 offset-xl-2 h-100">
      <div class="row align-items-center h-100">
        <div class="text-center w-100 mt-5 box-wrapper">
          <h1>
            <code>{{ $t('permission_denied.page_title') }}</code>
          </h1>
          <hr />
          <h3>{{ $t('permission_denied.error_msg') }}</h3>
          <h3>🚫🚫🚫🚫</h3>
          <h6>{{ $t('permission_denied.error_code') }}</h6>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  middleware: 'auth',
  metaInfo() {
    return { title: this.$t('permission_denied.page_title') }
  },

  data: () => ({
    breadcrumbsCurrent: 'permission_denied.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'permission_denied.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'permission_denied.breadcrumbs_active',
        url: '',
      },
    ],
  }),
}
</script>

<style>
h1 {
  color: red;
  font-size: 5em;
}

h6 {
  color: red;
  text-decoration: underline;
}

hr {
  margin: auto;
  width: 50%;
}
</style>
