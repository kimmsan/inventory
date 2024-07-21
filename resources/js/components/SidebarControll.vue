<template>
  <aside class="control-sidebar control-sidebar-dark px-3 py-4">
    <!-- Control sidebar content goes here -->
    <div class="mb-4 d-flex justify-content-between sidebarc-top">
      <h5>{{ $t("sidebar_control.customize_acculance") }}</h5>
      <button @click="closeSideBarControl" class="btn btn-danger close-sidebar-btn">x</button>
    </div>

    <div class="sidebarc-body">
      <div class="mb-4">
        <div class="custom-control custom-checkbox">
          <input class="custom-control-input" type="checkbox" id="dark-modee" value="1" :checked="isDark ? true : false"
            @click="addBodyClass('isDark', 'dark-mode')" />
          <label for="dark-modee" class="custom-control-label">{{
            $t("sidebar_control.dark_mode")
          }}</label>
        </div>
      </div>
      <!-- Dark Mode -->

      <!-- <div class="mb-4">
        <div class="custom-control custom-checkbox">
          <input
            class="custom-control-input"
            type="checkbox"
            id="rtl-mode"
            value="1"
            :checked="isRTL ? true : false"
            @click="rtlHandler"
          />
          <label for="rtl-mode" class="custom-control-label">{{
            $t('RTL mode')
          }}</label>
        </div>
      </div> -->

      <div>
        <h6>{{ $t("sidebar_control.header_options") }}</h6>
        <div class="custom-control custom-checkbox mb-1">
          <input class="custom-control-input" type="checkbox" id="layout-navbar-fixed" value="1"
            :checked="isNavFixed ? true : false" @click="addBodyClass('isNavFixed', 'layout-navbar-fixed')" />
          <label for="layout-navbar-fixed" class="custom-control-label">{{
            $t("sidebar_control.fixed")
          }}</label>
        </div>
        <div class="custom-control custom-checkbox mb-4">
          <input class="custom-control-input" type="checkbox" id="border-bottom-0" value="1"
            :checked="isBorderBtm ? true : false" @click="addBodyClass('isBorderBtm', 'border-bottom-0')" />
          <label for="border-bottom-0" class="custom-control-label">{{
            $t("sidebar_control.no_border")
          }}</label>
        </div>
      </div>

      <!-- Header-options -->

      <div>
        <h6>{{ $t("sidebar_control.sidebar_options") }}</h6>
        <div class="custom-control custom-checkbox mb-1">
          <input class="custom-control-input" type="checkbox" id="sidebar-dark" value="1"
            :checked="isSidebarDark ? true : false" @click="addSidebarClass('isSidebarDark', 'dark-sidebar')" />
          <label for="sidebar-dark" class="custom-control-label">{{
            $t("sidebar_control.sidebar_dark")
          }}</label>
        </div>
        <div class="custom-control custom-checkbox mb-1">
          <input class="custom-control-input" type="checkbox" id="collapsed" value="1"
            :checked="isSidebarCollasped ? true : false" @click="addBodyClass('isSidebarCollasped', 'sidebar-collapse')" />
          <label for="collapsed" class="custom-control-label">{{
            $t("sidebar_control.collapsed")
          }}</label>
        </div>
        <div class="custom-control custom-checkbox mb-1">
          <input class="custom-control-input" type="checkbox" id="layout-fixed" value="1"
            :checked="isLayoutFixed ? true : false" @click="addBodyClass('isLayoutFixed', 'layout-fixed')" />
          <label for="layout-fixed" class="custom-control-label">{{
            $t("sidebar_control.sidebar_fixed")
          }}</label>
        </div>
        <div class="custom-control custom-checkbox mb-1">
          <input class="custom-control-input" type="checkbox" id="sidebar-mini" value="1"
            :checked="isSidebarMini ? true : false" @click="addBodyClass('isSidebarMini', 'sidebar-mini')" />
          <label for="sidebar-mini" class="custom-control-label">{{
            $t("sidebar_control.sidebar_mini")
          }}</label>
        </div>
        <div class="custom-control custom-checkbox mb-1">
          <input class="custom-control-input" type="checkbox" id="nav-flat" value="1" :checked="isNavFlat ? true : false"
            @click="addNavSidebarClass('isNavFlat', 'nav-flat')" />
          <label for="nav-flat" class="custom-control-label">{{
            $t("sidebar_control.nav_flat_style")
          }}</label>
        </div>
        <div class="custom-control custom-checkbox mb-1">
          <input class="custom-control-input" type="checkbox" id="nav-legacy" value="1"
            :checked="isNavLegacy ? true : false" @click="addNavSidebarClass('isNavLegacy', 'nav-legacy')" />
          <label for="nav-legacy" class="custom-control-label">{{
            $t("sidebar_control.nav_legacy_style")
          }}</label>
        </div>
        <div class="custom-control custom-checkbox mb-1">
          <input class="custom-control-input" type="checkbox" id="nav-child-indent" value="1"
            :checked="isNavChildIndent ? true : false"
            @click="addNavSidebarClass('isNavChildIndent', 'nav-child-indent')" />
          <label for="nav-child-indent" class="custom-control-label">{{
            $t("sidebar_control.nav_child_indent")
          }}</label>
        </div>
        <div class="custom-control custom-checkbox mb-4">
          <input class="custom-control-input" type="checkbox" id="disableHoverExpand" value="1"
            :checked="isDisableHoverExpand ? true : false"
            @click="addSidebarClass('isDisableHoverExpand', 'sidebar-no-expand')" />
          <label for="disableHoverExpand" class="custom-control-label">{{
            $t("sidebar_control.disable_hover")
          }}</label>
        </div>
      </div>
      <!-- Header-options -->
    </div>
  </aside>
</template>

<script>
export default {
  name: "sidebar-controll",
  data: () => ({
    isDark: false,
    isRTL: false,
    isBorderBtm: false,
    isNavFixed: false,
    isSidebarCollasped: false,
    isLayoutFixed: false,
    isSidebarMini: false,
    isNavFlat: false,
    isSidebarDark: false,
    isNavChildIndent: false,
    isNavLegacy: false,
    isDisableHoverExpand: false,
  }),

  mounted() {
    this.getLocalStorageData();
  },

  methods: {
    rtlHandler() {
      localStorage.setItem("isRTL", !this.isRTL);
      this.isRTL = !this.isRTL;
      window.location.reload();
    },
    // ADD OR REMOVE VALUE TO LOCALSTORAGE AND SET OR REMOVE IN BODY CLASS
    addBodyClass(varVal, className) {
      this[varVal] = !this[varVal];
      if (this[varVal]) {
        localStorage[varVal] = true;
        document.body.classList.add(className);
      } else {
        localStorage.removeItem(varVal);
        document.body.classList.remove(className);
      }
    },

    // ADD OR REMOVE CLASS TO MAIN-SIDEBAR
    addSidebarClass(varVal, className) {
      this[varVal] = !this[varVal];

      if (this[varVal]) {
        localStorage[varVal] = true;
        var data = document.querySelector(".main-sidebar");
        data.classList.add(className);
      } else {
        localStorage.removeItem(varVal);
        var data = document.querySelector(".main-sidebar");
        data.classList.remove(className);
      }
    },

    // ADD CLASS TO SIDEBAR NAV
    addNavSidebarClass(varVal, className) {
      this[varVal] = !this[varVal];

      if (this[varVal]) {
        localStorage[varVal] = true;
        var data = document.querySelector(".nav-sidebar");
        data.classList.add(className);
      } else {
        localStorage.removeItem(varVal);
        var data = document.querySelector(".nav-sidebar");
        data.classList.remove(className);
      }
    },

    // Dark mode check from localstorage
    getLocalStorageData() {
      // Check if isDark true in localstorage
      if (localStorage.isDark) {
        document.body.classList.add("dark-mode");
        this.isDark = true;
      }
      if (localStorage.getItem("isRTL") === "true") {
        this.isRTL = true;
      }

      // Check if isDark true in localstorage
      if (localStorage.isBorderBtm) {
        document.body.classList.add("border-bottom-0");
        this.isBorderBtm = true;
      }

      // Check if headerFixed true in localstorage
      if (localStorage.isNavFixed) {
        document.body.classList.add("layout-navbar-fixed");
        this.isNavFixed = true;
      }

      // Check if headerFixed true in localstorage
      if (localStorage.isSidebarCollasped) {
        document.body.classList.add("sidebar-collapse");
        this.isSidebarCollasped = true;
      }

      // Check if headerFixed true in localstorage
      if (localStorage.isLayoutFixed) {
        document.body.classList.add("layout-fixed");
        this.isLayoutFixed = true;
      }

      // Check if headerFixed true in localstorage
      if (localStorage.isSidebarMini) {
        document.body.classList.add("sidebar-mini");
        this.isSidebarMini = true;
      }

      // Check if headerFixed true in localstorage
      if (localStorage.isNavFlat) {
        this.isNavFlat = true;
        var navSidebar = document.querySelector(".nav-sidebar");
        navSidebar.classList.add("nav-flat");
      }

      // Check if headerFixed true in localstorage
      if (localStorage.isSidebarDark) {
        this.isSidebarDark = true;
        var sideBarDark = document.querySelector(".main-sidebar");
        sideBarDark.classList.add("dark-sidebar");
      }

      // Check if headerFixed true in localstorage
      if (localStorage.isNavChildIndent) {
        this.isNavChildIndent = true;
        var navChildIndent = document.querySelector(".nav-sidebar");
        navChildIndent.classList.add("nav-child-indent");
      }

      // Check if headerFixed true in localstorage
      if (localStorage.isNavLegacy) {
        this.isNavLegacy = true;
        var navLegacy = document.querySelector(".nav-sidebar");
        navLegacy.classList.add("nav-legacy");
      }

      // Check if headerFixed true in localstorage
      if (localStorage.addSidebarClass) {
        this.addSidebarClass = true;
        var sidebarClass = document.querySelector(".main-sidebar");
        sidebarClass.classList.add("sidebar-no-expand");
      }
    },

    closeSideBarControl() {
      document.body.classList.toggle("control-sidebar-slide-open");
    },

  },
};
</script>
