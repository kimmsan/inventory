<template>
  <div class="text-center py-8">
    <img
      src="/../../images/empty.png"
      class="w-64 m-auto"
      alt="result-not-found"
    />
    <p class="font-bold text-lg text-gray-600 dark:text-gray-200">
      {{ $t("sorry") }} 😔 {{ $t("no_data_found") }}.
    </p>
  </div>
</template>

<script>
export default {
  name: "EmptyTable",
};
</script>



