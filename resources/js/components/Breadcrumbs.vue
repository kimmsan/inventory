<template>
  <div class="content-header">
    <div class="row align-items-center breadcrumbs-style">
      <div class="col-sm-6">
        <h3 class="m-0">
          {{ $t(current) }}
        </h3>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li
            v-for="(data, index) in items"
            v-show="items"
            :key="index"
            class="breadcrumb-item"
          >
            <router-link v-show="data.url" :to="{ name: data.url }">
              {{ $t(data.name) }}
            </router-link>
            <span v-show="!data.url" href="#">{{ $t(data.name) }}</span>
          </li>
        </ol>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
</template>

<script>
export default {
  name: "Breadcrumbs",

  props: ["items", "current"],
};
</script>

<style scoped>
.content-header {
  padding: 25px 0;
}

.breadcrumb-item + .breadcrumb-item::before {
  content: ">";
}
</style>
