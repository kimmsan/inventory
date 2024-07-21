<template>
  <div class="position-absolute w-100 h-100 opacity-50 z-10 tbl-loading">
    <div class="loader07" />
  </div>
</template>

<script>
export default {
  name: "TableLoading",
};
</script>

<style lang="scss">
.loader07 {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  position: relative;
  animation: loader07-u04c322d6 1s linear infinite;
  top: 50%;
  margin: -8px auto 0;
}

@keyframes loader-scale {
  0% {
    transform: scale(0);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: scale(1);
    opacity: 0;
  }
}

.loader07 {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  position: relative;
  animation: loader07-u04c322d6 1s linear infinite;
  top: 50%;
  margin: -8px auto 0;
}

@keyframes loader07-u04c322d6 {
  0% {
    box-shadow: 0 -27px 0 0 rgba(0, 82, 236, 0.05),
      19px -19px 0 0 rgba(0, 82, 236, 0.1), 27px 0 0 0 rgba(0, 82, 236, 0.2),
      19px 19px 0 0 rgba(0, 82, 236, 0.3), 0 27px 0 0 rgba(0, 82, 236, 0.4),
      -19px 19px 0 0 rgba(0, 82, 236, 0.6), -27px 0 0 0 rgba(0, 82, 236, 0.8),
      -19px -19px 0 0 #0052ec;
  }
  12.5% {
    box-shadow: 0 -27px 0 0 #0052ec, 19px -19px 0 0 rgba(0, 82, 236, 0.05),
      27px 0 0 0 rgba(0, 82, 236, 0.1), 19px 19px 0 0 rgba(0, 82, 236, 0.2),
      0 27px 0 0 rgba(0, 82, 236, 0.3), -19px 19px 0 0 rgba(0, 82, 236, 0.4),
      -27px 0 0 0 rgba(0, 82, 236, 0.6), -19px -19px 0 0 rgba(0, 82, 236, 0.8);
  }
  25% {
    box-shadow: 0 -27px 0 0 rgba(0, 82, 236, 0.8), 19px -19px 0 0 #0052ec,
      27px 0 0 0 rgba(0, 82, 236, 0.05), 19px 19px 0 0 rgba(0, 82, 236, 0.1),
      0 27px 0 0 rgba(0, 82, 236, 0.2), -19px 19px 0 0 rgba(0, 82, 236, 0.3),
      -27px 0 0 0 rgba(0, 82, 236, 0.4), -19px -19px 0 0 rgba(0, 82, 236, 0.6);
  }
  37.5% {
    box-shadow: 0 -27px 0 0 rgba(0, 82, 236, 0.6),
      19px -19px 0 0 rgba(0, 82, 236, 0.8), 27px 0 0 0 #0052ec,
      19px 19px 0 0 rgba(0, 82, 236, 0.05), 0 27px 0 0 rgba(0, 82, 236, 0.1),
      -19px 19px 0 0 rgba(0, 82, 236, 0.2), -27px 0 0 0 rgba(0, 82, 236, 0.3),
      -19px -19px 0 0 rgba(0, 82, 236, 0.4);
  }
  50% {
    box-shadow: 0 -27px 0 0 rgba(0, 82, 236, 0.4),
      19px -19px 0 0 rgba(0, 82, 236, 0.6), 27px 0 0 0 rgba(0, 82, 236, 0.8),
      19px 19px 0 0 #0052ec, 0 27px 0 0 rgba(0, 82, 236, 0.05),
      -19px 19px 0 0 rgba(0, 82, 236, 0.1), -27px 0 0 0 rgba(0, 82, 236, 0.2),
      -19px -19px 0 0 rgba(0, 82, 236, 0.3);
  }
  62.5% {
    box-shadow: 0 -27px 0 0 rgba(0, 82, 236, 0.3),
      19px -19px 0 0 rgba(0, 82, 236, 0.4), 27px 0 0 0 rgba(0, 82, 236, 0.6),
      19px 19px 0 0 rgba(0, 82, 236, 0.8), 0 27px 0 0 #0052ec,
      -19px 19px 0 0 rgba(0, 82, 236, 0.05), -27px 0 0 0 rgba(0, 82, 236, 0.1),
      -19px -19px 0 0 rgba(0, 82, 236, 0.2);
  }
  75% {
    box-shadow: 0 -27px 0 0 rgba(0, 82, 236, 0.2),
      19px -19px 0 0 rgba(0, 82, 236, 0.3), 27px 0 0 0 rgba(0, 82, 236, 0.4),
      19px 19px 0 0 rgba(0, 82, 236, 0.6), 0 27px 0 0 rgba(0, 82, 236, 0.8),
      -19px 19px 0 0 #0052ec, -27px 0 0 0 rgba(0, 82, 236, 0.05),
      -19px -19px 0 0 rgba(0, 82, 236, 0.1);
  }
  87.5% {
    box-shadow: 0 -27px 0 0 rgba(0, 82, 236, 0.1),
      19px -19px 0 0 rgba(0, 82, 236, 0.2), 27px 0 0 0 rgba(0, 82, 236, 0.3),
      19px 19px 0 0 rgba(0, 82, 236, 0.4), 0 27px 0 0 rgba(0, 82, 236, 0.6),
      -19px 19px 0 0 rgba(0, 82, 236, 0.8), -27px 0 0 0 #0052ec,
      -19px -19px 0 0 rgba(0, 82, 236, 0.05);
  }
  100% {
    box-shadow: 0 -27px 0 0 rgba(0, 82, 236, 0.05),
      19px -19px 0 0 rgba(0, 82, 236, 0.1), 27px 0 0 0 rgba(0, 82, 236, 0.2),
      19px 19px 0 0 rgba(0, 82, 236, 0.3), 0 27px 0 0 rgba(0, 82, 236, 0.4),
      -19px 19px 0 0 rgba(0, 82, 236, 0.6), -27px 0 0 0 rgba(0, 82, 236, 0.8),
      -19px -19px 0 0 #0052ec;
  }
}

.tbl-loading {
  background: #fff;
  z-index: 20;
}

.dark-mode .tbl-loading {
  background: #111827;
}
</style>
