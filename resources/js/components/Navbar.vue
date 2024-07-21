<template>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M4 6h16M4 12h16M4 18h7"
            />
          </svg>
        </a>
      </li>

      <locale-dropdown />
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li>
        <router-link :to="{ name: 'pos.create' }" class="btn pos-btn">
          <i class="fas fa-cash-register"></i>
          {{ this.$t("pos.pos") }}</router-link
        >
      </li>
      <li
        v-if="
          $can('lc-create') ||
          $can('order-create') ||
          $can('invoice-create') ||
          $can('expense-create') ||
          $can('international-purchase-create')
        "
        class="nav-item dropdown"
      >
        <a
          class="nav-link"
          data-toggle="dropdown"
          href="#"
          aria-expanded="true"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
        </a>
        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
          <router-link
            v-if="$can('invoice-create')"
            :to="{ name: 'invoices.create' }"
            class="dropdown-item dropdown-icon-center"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"
              />
            </svg>
            {{ $t("topbar.new_invoice") }}
          </router-link>
          <div class="dropdown-divider"></div>
          <router-link
            v-if="$can('expense-create')"
            :to="{ name: 'expenses.create' }"
            class="dropdown-item dropdown-icon-center"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
              />
            </svg>
            {{ $t("topbar.new_expense") }}
          </router-link>
          <div class="dropdown-divider"></div>
          <router-link
            v-if="$can('purchase-create')"
            :to="{ name: 'purchases.create' }"
            class="dropdown-item dropdown-icon-center"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
              />
            </svg>
            {{ $t("topbar.new_purchase") }}
          </router-link>
          <div class="dropdown-divider"></div>
          <router-link
            v-if="$can('quotation-create')"
            :to="{ name: 'quotations.create' }"
            class="dropdown-item dropdown-icon-center"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M4 6h16M4 10h16M4 14h16M4 18h16"
              />
            </svg>
            {{ $t("topbar.new_quotation") }}
          </router-link>
        </div>
      </li>

      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
            />
          </svg>
          <span
            v-if="notificationCount > 0"
            class="badge badge-warning navbar-badge"
            >{{ notificationCount }}</span
          >
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <router-link
            v-if="notificationCount > 0"
            class="dropdown-item"
            :to="{ name: 'stockAlertProducts' }"
          >
            <i class="fas fa-exclamation-circle mr-2 red"></i>
            {{ notificationCount }} {{ $t("common.stock_notification") }}
          </router-link>
          <a v-else href="#" class="dropdown-item">
            <i class="fas fa-check-circle mr-2 green"></i>
            {{ $t("common.stock_notification_empty") }}
          </a>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"
            />
          </svg>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" @click="sideBarControl" href="#">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
            />
          </svg>
        </a>
      </li>

      <!-- User Dropdown Menu -->
      <li v-if="user" class="nav-item dropdown">
        <a class="nav-link user-profile" data-toggle="dropdown" href="#">
          <div>
            <img :src="user.photo_url" :alt="user.name" />
          </div>
          <div>
            <p class="mb-0 ml-2 d-none d-md-block">{{ user.name }}</p>
          </div>
          <span class="mt-1 ml-1">
            <i class="fas fa-angle-down"></i>
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
          <router-link
            :to="{ name: 'profile' }"
            class="dropdown-item dropdown-icon-center"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            {{ $t("topbar.profile") }}
          </router-link>
          <div class="dropdown-divider" />
          <router-link
            :to="{ name: 'setup.general' }"
            class="dropdown-item dropdown-icon-center"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
              />
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
              />
            </svg>
            {{ $t("topbar.setup") }}
          </router-link>
          <div class="dropdown-divider" />
          <a
            href="#"
            class="dropdown-item dropdown-icon-center"
            @click.prevent="logout"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
              />
            </svg>
            {{ $t("topbar.logout") }}
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";
import LocaleDropdown from "./LocaleDropdown";

export default {
  components: {
    LocaleDropdown,
  },

  data: () => ({
    appName: window.config.appName,
    notificationCount: 0,
  }),

  computed: mapGetters({
    user: "auth/user",
  }),

  created() {
    this.stockNotification();
  },

  methods: {
    // get stock notification
    async stockNotification() {
      const { data } = await axios.get(
        window.location.origin + "/api/dashboard/stock-notification"
      );
      this.notificationCount = data;
    },

    async logout() {
      // Log out the user.
      await this.$store.dispatch("auth/logout");
      // Redirect to login.
      this.$router.push({ name: "login" });
    },

    sideBarControl() {
      document.body.classList.toggle("control-sidebar-slide-open");
    },
  },
};
</script>

<style scoped>
.user-profile img {
  width: 40px;
  height: 40px;
  border-radius: 100%;
}

.user-profile {
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
}
.dropdown-icon-center {
  display: flex;
  align-items: center;
}
.dropdown-icon-center svg {
  margin-right: 4px;
}

.btn-pos {
  background: transparent;
  color: #111;
  border: 2px solid #6366f1;
  border-radius: 34px;
  padding: 4px 15px;
}
</style>
