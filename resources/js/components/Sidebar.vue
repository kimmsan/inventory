<template>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar">
    <!-- Brand Logo -->
    <router-link :to="{ name: 'home' }" class="brand-link">
      <img
        v-if="appInfo"
        :src="appInfo.blackLogo"
        :alt="appInfo.companyName"
        class="lg-logo light-logo"
      />
      <img
        v-if="appInfo"
        :src="appInfo.logo"
        :alt="appInfo.companyName"
        class="lg-logo dark-logo"
      />
      <img
        v-if="appInfo"
        :src="appInfo.smallLogo"
        alt="appInfo.companyName"
        class="sm-logo"
      />
    </router-link>

    <!-- Sidebar -->
    <div class="sidebar custom-sidebar">
      <!-- Sidebar Menu -->
      <nav class="py-3 pb-5">
        <ul
          class="nav nav-pills nav-sidebar flex-column"
          data-widget="treeview"
          role="menu"
        >
          <li class="nav-header text-uppercase text-bold">
            {{ $t("sidebar.dashboard") }}
          </li>
          <li class="nav-item">
            <router-link :to="{ name: 'home' }" class="nav-link">
              <i class="nav-icon fas fa-home" />
              <p>{{ $t("sidebar.dashboard") }}</p>
            </router-link>
          </li>
          <li class="nav-header text-bold">{{ $t("sidebar.activities") }}</li>
          <li
            v-if="
              $can('expense-category-list') ||
              $can('expense-category-create') ||
              $can('expense-category-edit') ||
              $can('expense-category-delete') ||
              $can('expense-sub-category-list') ||
              $can('expense-sub-category-create') ||
              $can('expense-sub-category-edit') ||
              $can('expense-sub-category-delete') ||
              $can('expense-list') ||
              $can('expense-create') ||
              $can('expense-edit') ||
              $can('expense-view') ||
              $can('expense-delete')
            "
            class="nav-item has-treeview"
            :class="
              menuOpen('expenseCats') ||
              menuOpen('expenseSubCats') ||
              menuOpen('expenses')
                ? 'menu-is-opening menu-open'
                : ''
            "
          >
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-calculator" />
              <p>
                {{ $t("sidebar.expenses") }}
                <i class="fas fa-angle-left right" />
              </p>
            </a>
            <ul
              class="nav nav-treeview"
              :class="
                menuOpen('expenseCats') ||
                menuOpen('expenseSubCats') ||
                menuOpen('expenses')
                  ? 'display: block'
                  : 'display: none'
              "
            >
              <li
                v-if="
                  $can('expense-category-list') ||
                  $can('expense-category-create') ||
                  $can('expense-category-edit') ||
                  $can('expense-category-delete')
                "
                class="nav-item"
              >
                <router-link
                  :to="{ name: 'expenseCats.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-tags nav-icon" />
                  <p>{{ $t("sidebar.categories") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('expense-sub-category-list') ||
                  $can('expense-sub-category-create') ||
                  $can('expense-sub-category-edit') ||
                  $can('expense-sub-category-delete')
                "
                class="nav-item"
              >
                <router-link
                  :to="{ name: 'expenseSubCats.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-code-branch nav-icon" />
                  <p>{{ $t("sidebar.sub_categories") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('expense-list') ||
                  $can('expense-create') ||
                  $can('expense-edit') ||
                  $can('expense-view') ||
                  $can('expense-delete')
                "
                class="nav-item"
              >
                <router-link :to="{ name: 'expenses.index' }" class="nav-link">
                  <i class="fas fa-list-ul nav-icon" />
                  <p>{{ $t("sidebar.expenses_list") }}</p>
                </router-link>
              </li>
            </ul>
          </li>
          <li
            v-if="
              $can('purchase-list') ||
              $can('purchase-create') ||
              $can('purchase-edit') ||
              $can('purchase-view') ||
              $can('purchase-delete') ||
              $can('purchase-return-list') ||
              $can('purchase-return-create') ||
              $can('purchase-return-edit') ||
              $can('purchase-return-view') ||
              $can('purchase-return-delete')
            "
            class="nav-item has-treeview"
            :class="
              menuOpen('purchases') || menuOpen('purchaseReturns')
                ? 'menu-is-opening menu-open'
                : ''
            "
          >
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-shopping-basket" />
              <p>
                {{ $t("sidebar.purchases") }}
                <i class="right fas fa-angle-left" />
              </p>
            </a>
            <ul
              class="nav nav-treeview"
              :style="
                menuOpen('purchases') || menuOpen('purchaseReturns')
                  ? 'display: block'
                  : 'display: none'
              "
            >
              <li
                v-if="
                  $can('purchase-list') ||
                  $can('purchase-create') ||
                  $can('purchase-edit') ||
                  $can('purchase-view') ||
                  $can('purchase-delete')
                "
                class="nav-item"
              >
                <router-link :to="{ name: 'purchases.index' }" class="nav-link">
                  <i class="fas fa-truck-loading nav-icon" />
                  <p>{{ $t("sidebar.purchases_list") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('purchase-return-list') ||
                  $can('purchase-return-create') ||
                  $can('purchase-return-edit') ||
                  $can('purchase-return-view') ||
                  $can('purchase-return-delete')
                "
                class="nav-item"
              >
                <router-link
                  :to="{ name: 'purchaseReturns.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-undo-alt nav-icon" />
                  <p>{{ $t("sidebar.returns_list") }}</p>
                </router-link>
              </li>
            </ul>
          </li>
          <li
            v-if="
              $can('quotation-list') ||
              $can('quotation-create') ||
              $can('quotation-view') ||
              $can('quotation-edit') ||
              $can('quotation-delete') ||
              $can('quotation-to-invoice') ||
              $can('invoice-list') ||
              $can('invoice-create') ||
              $can('invoice-view') ||
              $can('invoice-edit') ||
              $can('invoice-delete') ||
              $can('invoice-return-list') ||
              $can('invoice-return-create') ||
              $can('invoice-return-view') ||
              $can('invoice-return-edit') ||
              $can('invoice-return-delete')
            "
            class="nav-item has-treeview"
            :class="
              menuOpen('quotations') ||
              menuOpen('invoices') ||
              menuOpen('invoiceReturns')
                ? 'menu-is-opening menu-open'
                : ''
            "
          >
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-shopping-bag" />
              <p>
                {{ $t("sidebar.sales") }}
                <i class="right fas fa-angle-left" />
              </p>
            </a>
            <ul
              class="nav nav-treeview"
              :style="
                menuOpen('quotations') ||
                menuOpen('invoices') ||
                menuOpen('invoiceReturns')
                  ? 'display: block'
                  : 'display: none'
              "
            >
              <li
                v-if="
                  $can('quotation-list') ||
                  $can('quotation-create') ||
                  $can('quotation-view') ||
                  $can('quotation-edit') ||
                  $can('quotation-delete') ||
                  $can('quotation-to-invoice')
                "
                class="nav-item"
              >
                <router-link
                  :to="{ name: 'quotations.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-th-list nav-icon" />
                  <p>{{ $t("sidebar.quotations_list") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('invoice-list') ||
                  $can('invoice-create') ||
                  $can('invoice-view') ||
                  $can('invoice-edit') ||
                  $can('invoice-delete')
                "
                class="nav-item"
              >
                <router-link :to="{ name: 'invoices.index' }" class="nav-link">
                  <i class="fas fa-file-invoice nav-icon" />
                  <p>{{ $t("sidebar.invoices_list") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('invoice-list') ||
                  $can('invoice-create') ||
                  $can('invoice-view') ||
                  $can('invoice-edit') ||
                  $can('invoice-delete')
                "
                class="nav-item"
              >
                <router-link :to="{ name: 'pos.create' }" class="nav-link">
                  <i class="fas fa-cash-register nav-icon"></i>
                  <p>{{ $t("pos.pos") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('invoice-return-list') ||
                  $can('invoice-return-create') ||
                  $can('invoice-return-view') ||
                  $can('invoice-return-edit') ||
                  $can('invoice-return-delete')
                "
                class="nav-item"
              >
                <router-link
                  :to="{ name: 'invoiceReturns.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-undo-alt nav-icon" />
                  <p>{{ $t("sidebar.returns_list") }}</p>
                </router-link>
              </li>
            </ul>
          </li>
          <li class="nav-header text-bold">{{ $t("sidebar.accounting") }}</li>
          <li
            v-if="
              $can('account-list') ||
              $can('account-create') ||
              $can('account-view') ||
              $can('account-edit') ||
              $can('account-delete') ||
              $can('account-balance-list') ||
              $can('account-balance-create') ||
              $can('account-balance-edit') ||
              $can('account-balance-delete') ||
              $can('account-transfer-balance-list') ||
              $can('account-transfer-balance-create') ||
              $can('account-transfer-balance-edit') ||
              $can('account-transfer-balance-view') ||
              $can('account-transfer-balance-delete') ||
              $can('transaction-history')
            "
            class="nav-item has-treeview"
            :class="
              menuOpen('accounts') ||
              menuOpen('balances') ||
              menuOpen('transferBalances') ||
              menuOpen('transactions')
                ? 'menu-is-opening menu-open'
                : ''
            "
          >
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book" />
              <p>
                {{ $t("sidebar.cashbook") }}
                <i class="right fas fa-angle-left" />
              </p>
            </a>
            <ul
              class="nav nav-treeview"
              :style="
                menuOpen('accounts') ||
                menuOpen('balances') ||
                menuOpen('transferBalances') ||
                menuOpen('transactions')
                  ? 'display: block'
                  : 'display: none'
              "
            >
              <li
                v-if="
                  $can('account-list') ||
                  $can('account-create') ||
                  $can('account-view') ||
                  $can('account-edit') ||
                  $can('account-delete')
                "
                class="nav-item"
              >
                <router-link :to="{ name: 'accounts.index' }" class="nav-link">
                  <i class="fas fa-grip-horizontal nav-icon" />
                  <p>{{ $t("sidebar.accounts") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('account-balance-list') ||
                  $can('account-balance-create') ||
                  $can('account-balance-edit') ||
                  $can('account-balance-delete')
                "
                class="nav-item"
              >
                <router-link :to="{ name: 'balances.index' }" class="nav-link">
                  <i class="fas fa-sliders-h nav-icon" />
                  <p>{{ $t("sidebar.balance_adjustments") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('account-transfer-balance-list') ||
                  $can('account-transfer-balance-create') ||
                  $can('account-transfer-balance-edit') ||
                  $can('account-transfer-balance-view') ||
                  $can('account-transfer-balance-delete')
                "
                class="nav-item"
              >
                <router-link
                  :to="{ name: 'transferBalances.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-exchange-alt nav-icon" />
                  <p>{{ $t("sidebar.balance_transfers") }}</p>
                </router-link>
              </li>
              <li v-if="$can('transaction-history')" class="nav-item">
                <router-link
                  :to="{ name: 'transactions.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-history nav-icon" />
                  <p>{{ $t("sidebar.transaction_history") }}</p>
                </router-link>
              </li>
            </ul>
          </li>
          <li
            v-if="
              $can('non-purchase-payment-list') ||
              $can('non-purchase-payment-create') ||
              $can('non-purchase-payment-edit') ||
              $can('non-purchase-payment-view') ||
              $can('non-purchase-payment-delete') ||
              $can('purchase-payment-list') ||
              $can('purchase-payment-create') ||
              $can('purchase-payment-edit') ||
              $can('purchase-payment-view') ||
              $can('purchase-payment-delete') ||
              $can('non-invoice-payment-list') ||
              $can('non-invoice-payment-create') ||
              $can('non-invoice-payment-edit') ||
              $can('non-invoice-payment-view') ||
              $can('non-invoice-payment-delete') ||
              $can('invoice-payment-list') ||
              $can('invoice-payment-create') ||
              $can('invoice-payment-view') ||
              $can('invoice-payment-edit') ||
              $can('invoice-payment-delete')
            "
            class="nav-item has-treeview"
            :class="
              menuOpen('nonInvoicePayments') ||
              menuOpen('invoicePayments') ||
              menuOpen('purchasePayments') ||
              menuOpen('nonPurchasePayments')
                ? 'menu-is-opening menu-open'
                : ''
            "
          >
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-receipt" />
              <p>
                {{ $t("sidebar.payments") }}
                <i class="right fas fa-angle-left" />
              </p>
            </a>
            <ul
              class="nav nav-treeview"
              :style="
                menuOpen('nonInvoicePayments') ||
                menuOpen('invoicePayments') ||
                menuOpen('purchasePayments') ||
                menuOpen('nonPurchasePayments')
                  ? 'display: block'
                  : 'display: none'
              "
            >
              <li
                v-if="
                  $can('non-invoice-payment-list') ||
                  $can('non-invoice-payment-create') ||
                  $can('non-invoice-payment-edit') ||
                  $can('non-invoice-payment-view') ||
                  $can('non-invoice-payment-delete') ||
                  $can('invoice-payment-list') ||
                  $can('invoice-payment-create') ||
                  $can('invoice-payment-view') ||
                  $can('invoice-payment-edit') ||
                  $can('invoice-payment-delete')
                "
                class="nav-item has-treeview"
                :class="
                  menuOpen('nonInvoicePayments') || menuOpen('invoicePayments')
                    ? 'menu-is-opening menu-open'
                    : ''
                "
              >
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-users" />
                  <p>
                    {{ $t("sidebar.clients") }}
                    <i class="fas fa-angle-left right" />
                  </p>
                </a>
                <ul
                  class="nav nav-treeview"
                  :style="
                    menuOpen('nonInvoicePayments') ||
                    menuOpen('invoicePayments')
                  "
                >
                  <li
                    v-if="
                      $can('invoice-payment-list') ||
                      $can('invoice-payment-create') ||
                      $can('invoice-payment-view') ||
                      $can('invoice-payment-edit') ||
                      $can('invoice-payment-delete')
                    "
                    class="nav-item"
                  >
                    <router-link
                      :to="{ name: 'invoicePayments.index' }"
                      class="nav-link"
                    >
                      <i class="fas fa-file-invoice nav-icon" />
                      <p>{{ $t("sidebar.invoice") }}</p>
                    </router-link>
                  </li>
                  <li
                    v-if="
                      $can('non-invoice-payment-list') ||
                      $can('non-invoice-payment-create') ||
                      $can('non-invoice-payment-edit') ||
                      $can('non-invoice-payment-view') ||
                      $can('non-invoice-payment-delete')
                    "
                    class="nav-item"
                  >
                    <router-link
                      :to="{ name: 'nonInvoicePayments.index' }"
                      class="nav-link"
                    >
                      <i class="fas fa-file-alt nav-icon" />
                      <p>{{ $t("sidebar.non_invoice") }}</p>
                    </router-link>
                  </li>
                </ul>
              </li>

              <li
                v-if="
                  $can('purchase-payment-list') ||
                  $can('purchase-payment-create') ||
                  $can('purchase-payment-edit') ||
                  $can('purchase-payment-view') ||
                  $can('purchase-payment-delete') ||
                  $can('non-purchase-payment-list') ||
                  $can('non-purchase-payment-create') ||
                  $can('non-purchase-payment-edit') ||
                  $can('non-purchase-payment-view') ||
                  $can('non-purchase-payment-delete')
                "
                class="nav-item has-treeview"
                :class="
                  menuOpen('nonPurchasePayments') ||
                  menuOpen('purchasePayments')
                    ? 'menu-is-opening menu-open'
                    : ''
                "
              >
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-people-carry" />
                  <p>
                    {{ $t("sidebar.suppliers") }}
                    <i class="fas fa-angle-left right" />
                  </p>
                </a>
                <ul
                  class="nav nav-treeview"
                  :style="
                    menuOpen('nonPurchasePayments') ||
                    menuOpen('purchasePayments')
                      ? 'display: block'
                      : 'display: none'
                  "
                >
                  <li
                    v-if="
                      $can('purchase-payment-list') ||
                      $can('purchase-payment-create') ||
                      $can('purchase-payment-edit') ||
                      $can('purchase-payment-view') ||
                      $can('purchase-payment-delete')
                    "
                    class="nav-item"
                  >
                    <router-link
                      :to="{ name: 'purchasePayments.index' }"
                      class="nav-link"
                    >
                      <i class="fas fa-plane-departure nav-icon" />
                      <p>{{ $t("sidebar.purchase") }}</p>
                    </router-link>
                  </li>
                  <li
                    v-if="
                      $can('non-purchase-payment-list') ||
                      $can('non-purchase-payment-create') ||
                      $can('non-purchase-payment-edit') ||
                      $can('non-purchase-payment-view') ||
                      $can('non-purchase-payment-delete')
                    "
                    class="nav-item"
                  >
                    <router-link
                      :to="{ name: 'nonPurchasePayments.index' }"
                      class="nav-link"
                    >
                      <i class="fas fa-truck-pickup nav-icon" />
                      <p>{{ $t("sidebar.non_purchase") }}</p>
                    </router-link>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <li
            v-if="
              $can('loan-authority-list') ||
              $can('loan-authority-create') ||
              $can('loan-authority-view') ||
              $can('loan-authority-edit') ||
              $can('loan-authority-delete') ||
              $can('loan-list') ||
              $can('loan-create') ||
              $can('loan-view') ||
              $can('loan-edit') ||
              $can('loan-delete') ||
              $can('loan-payment-list') ||
              $can('loan-payment-create') ||
              $can('loan-payment-view') ||
              $can('loan-payment-edit') ||
              $can('loan-payment-delete')
            "
            class="nav-item has-treeview"
            :class="
              menuOpen('authorities') ||
              menuOpen('loans') ||
              menuOpen('loanPayments')
                ? 'menu-is-opening menu-open'
                : ''
            "
          >
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-piggy-bank" />
              <p>
                {{ $t("sidebar.loan_management") }}
                <i class="right fas fa-angle-left" />
              </p>
            </a>
            <ul
              class="nav nav-treeview"
              :style="
                menuOpen('authorities') ||
                menuOpen('loans') ||
                menuOpen('loanPayments')
                  ? 'display: block'
                  : 'display: none'
              "
            >
              <li
                v-if="
                  $can('loan-authority-list') ||
                  $can('loan-authority-create') ||
                  $can('loan-authority-view') ||
                  $can('loan-authority-edit') ||
                  $can('loan-authority-delete')
                "
                class="nav-item"
              >
                <router-link
                  :to="{ name: 'authorities.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-building nav-icon" />
                  <p>{{ $t("sidebar.authorities") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('loan-list') ||
                  $can('loan-create') ||
                  $can('loan-view') ||
                  $can('loan-edit') ||
                  $can('loan-delete')
                "
                class="nav-item"
              >
                <router-link :to="{ name: 'loans.index' }" class="nav-link">
                  <i class="fas fa-list-ul nav-icon" />
                  <p>{{ $t("sidebar.loans") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('loan-payment-list') ||
                  $can('loan-payment-create') ||
                  $can('loan-payment-view') ||
                  $can('loan-payment-edit') ||
                  $can('loan-payment-delete')
                "
                class="nav-item"
              >
                <router-link
                  :to="{ name: 'loanPayments.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-receipt nav-icon" />
                  <p>{{ $t("sidebar.payments") }}</p>
                </router-link>
              </li>
            </ul>
          </li>
          <li
            v-if="
              $can('asset-type-list') ||
              $can('asset-type-create') ||
              $can('asset-type-edit') ||
              $can('asset-type-delete') ||
              $can('asset-list') ||
              $can('asset-create') ||
              $can('asset-view') ||
              $can('asset-edit') ||
              $can('asset-delete')
            "
            class="nav-item has-treeview"
            :class="
              menuOpen('assetTypes') || menuOpen('assets')
                ? 'menu-is-opening menu-open'
                : ''
            "
          >
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-couch" />
              <p>
                {{ $t("sidebar.asset_management") }}
                <i class="right fas fa-angle-left" />
              </p>
            </a>
            <ul
              class="nav nav-treeview"
              :style="
                menuOpen('assetTypes') || menuOpen('assets')
                  ? 'display: block'
                  : 'display: none'
              "
            >
              <li
                v-if="
                  $can('asset-type-list') ||
                  $can('asset-type-create') ||
                  $can('asset-type-edit') ||
                  $can('asset-type-delete')
                "
                class="nav-item"
              >
                <router-link
                  :to="{ name: 'assetTypes.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-tags nav-icon" />
                  <p>{{ $t("sidebar.types") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('asset-list') ||
                  $can('asset-create') ||
                  $can('asset-view') ||
                  $can('asset-edit') ||
                  $can('asset-delete')
                "
                class="nav-item"
              >
                <router-link :to="{ name: 'assets.index' }" class="nav-link">
                  <i class="fas fa-list-ul nav-icon" />
                  <p>{{ $t("sidebar.assets") }}</p>
                </router-link>
              </li>
            </ul>
          </li>
          <li
            v-if="
              $can('payroll-list') ||
              $can('payroll-create') ||
              $can('payroll-view') ||
              $can('payroll-edit') ||
              $can('payroll-delete')
            "
            class="nav-item"
          >
            <router-link :to="{ name: 'payroll.index' }" class="nav-link">
              <i class="nav-icon fas fa-clipboard-list" />
              <p>{{ $t("sidebar.payroll") }}</p>
            </router-link>
          </li>
          <li class="nav-header text-bold">{{ $t("sidebar.people") }}</li>
          <li
            v-if="
              $can('client-list') ||
              $can('client-create') ||
              $can('client-view') ||
              $can('client-edit') ||
              $can('client-delete')
            "
            class="nav-item"
          >
            <router-link :to="{ name: 'clients.index' }" class="nav-link">
              <i class="nav-icon fas fa-users" />
              <p>{{ $t("sidebar.clients") }}</p>
            </router-link>
          </li>
          <li
            v-if="
              $can('supplier-list') ||
              $can('supplier-create') ||
              $can('supplier-view') ||
              $can('supplier-edit') ||
              $can('supplier-delete')
            "
            class="nav-item"
          >
            <router-link
              :to="{ name: 'suppliers.index' }"
              href="#"
              class="nav-link"
            >
              <i class="nav-icon fas fa-people-carry" />
              <p>{{ $t("sidebar.suppliers") }}</p>
            </router-link>
          </li>
          <li
            v-if="
              $can('department-list') ||
              $can('department-create') ||
              $can('department-edit') ||
              $can('department-delete') ||
              $can('employee-list') ||
              $can('employee-create') ||
              $can('employee-edit') ||
              $can('employee-delete') ||
              $can('employee-view') ||
              $can('increment-list') ||
              $can('increment-create') ||
              $can('increment-edit') ||
              $can('increment-view') ||
              $can('increment-delete')
            "
            class="nav-item has-treeview"
            :class="
              menuOpen('departments') ||
              menuOpen('employees') ||
              menuOpen('increments')
                ? 'menu-is-opening menu-open'
                : ''
            "
          >
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users-cog" />
              <p>
                {{ $t("sidebar.employees") }}
                <i class="fas fa-angle-left right" />
              </p>
            </a>
            <ul
              class="nav nav-treeview"
              :style="
                menuOpen('departments') ||
                menuOpen('employees') ||
                menuOpen('increments')
                  ? 'display: block'
                  : 'display: none'
              "
            >
              <li
                v-if="
                  $can('department-list') ||
                  $can('department-create') ||
                  $can('department-edit') ||
                  $can('department-delete')
                "
                class="nav-item"
              >
                <router-link
                  :to="{ name: 'departments.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-server nav-icon" />
                  <p>{{ $t("sidebar.departments") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('employee-list') ||
                  $can('employee-create') ||
                  $can('employee-edit') ||
                  $can('employee-delete') ||
                  $can('employee-view')
                "
                class="nav-item"
              >
                <router-link :to="{ name: 'employees.index' }" class="nav-link">
                  <i class="fas fa-list-ul nav-icon" />
                  <p>{{ $t("sidebar.employees_list") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('increment-list') ||
                  $can('increment-create') ||
                  $can('increment-edit') ||
                  $can('increment-view') ||
                  $can('increment-delete')
                "
                class="nav-item"
              >
                <router-link
                  :to="{ name: 'increments.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-list-ul nav-icon" />
                  <p>{{ $t("sidebar.increments") }}</p>
                </router-link>
              </li>
            </ul>
          </li>

          <li class="nav-header text-bold">
            {{ $t("sidebar.inventory_uppercase") }}
          </li>
          <li
            v-if="
              $can('product-category-create') ||
              $can('product-category-edit') ||
              $can('product-category-delete') ||
              $can('product-sub-category-create') ||
              $can('product-sub-category-edit') ||
              $can('product-sub-category-delete') ||
              $can('product-create') ||
              $can('product-view') ||
              $can('product-edit') ||
              $can('product-delete') ||
              $can('print-barcode')
            "
            class="nav-item has-treeview"
            :class="
              menuOpen('productCats') ||
              menuOpen('productSubCats') ||
              menuOpen('products') ||
              menuOpen('barcode')
                ? 'menu-is-opening menu-open'
                : ''
            "
          >
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-boxes" />
              <p>
                {{ $t("sidebar.products") }}
                <i class="fas fa-angle-left right" />
              </p>
            </a>
            <ul
              class="nav nav-treeview"
              :style="
                menuOpen('productCats') ||
                menuOpen('productSubCats') ||
                menuOpen('products') ||
                menuOpen('barcode')
                  ? 'display: block'
                  : 'display: none'
              "
            >
              <li
                v-if="
                  $can('product-category-create') ||
                  $can('product-category-edit') ||
                  $can('product-category-delete')
                "
                class="nav-item"
              >
                <router-link
                  :to="{ name: 'productCats.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-tags nav-icon" />
                  <p>{{ $t("sidebar.categories") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('product-sub-category-create') ||
                  $can('product-sub-category-edit') ||
                  $can('product-sub-category-delete')
                "
                class="nav-item"
              >
                <router-link
                  :to="{ name: 'productSubCats.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-code-branch nav-icon" />
                  <p>{{ $t("sidebar.sub_categories") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('product-create') ||
                  $can('product-view') ||
                  $can('product-edit') ||
                  $can('product-delete')
                "
                class="nav-item"
              >
                <router-link :to="{ name: 'products.index' }" class="nav-link">
                  <i class="fas fa-list-ul nav-icon" />
                  <p>{{ $t("sidebar.products_list") }}</p>
                </router-link>
              </li>

              <li v-if="$can('print-barcode')" class="nav-item">
                <router-link :to="{ name: 'barcode.print' }" class="nav-link">
                  <i class="fas fa-barcode nav-icon" />
                  <p>{{ $t("sidebar.barcode") }}</p>
                </router-link>
              </li>
            </ul>
          </li>

          <li
            v-if="
              $can('inventory') ||
              $can('adjustment-create') ||
              $can('adjustment-view') ||
              $can('adjustment-edit') ||
              $can('adjustment-delete')
            "
            class="nav-item has-treeview"
            :class="
              menuOpen('inventory') ||
              menuOpen('adjustments') ||
              menuOpen('nonzeroInventory')
                ? 'menu-is-opening menu-open'
                : ''
            "
          >
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-warehouse" />
              <p>
                {{ $t("sidebar.inventory") }}
                <i class="right fas fa-angle-left" />
              </p>
            </a>
            <ul
              class="nav nav-treeview"
              :style="
                menuOpen('inventory') ||
                menuOpen('adjustments') ||
                menuOpen('nonzeroInventory')
                  ? 'display: block'
                  : 'display: none'
              "
            >
              <li v-if="$can('inventory-history')" class="nav-item">
                <router-link :to="{ name: 'inventory.index' }" class="nav-link">
                  <i class="fas fa-pallet nav-icon" />
                  <p>{{ $t("sidebar.view_inventory") }}</p>
                </router-link>
              </li>
              <li v-if="$can('inventory-history')" class="nav-item">
                <router-link
                  :to="{ name: 'nonzeroInventory.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-boxes nav-icon" />
                  <p>{{ $t("sidebar.non_zero_inventory") }}</p>
                </router-link>
              </li>
              <li
                v-if="
                  $can('adjustment-create') ||
                  $can('adjustment-view') ||
                  $can('adjustment-edit') ||
                  $can('adjustment-delete')
                "
                class="nav-item"
              >
                <router-link
                  :to="{ name: 'adjustments.index' }"
                  class="nav-link"
                >
                  <i class="fas fa-sliders-h nav-icon" />
                  <p>{{ $t("sidebar.inventory_adjustment") }}</p>
                </router-link>
              </li>
            </ul>
          </li>

          <li class="nav-header text-bold">{{ $t("sidebar.reports") }}</li>
          <li v-if="$can('balance-sheet')" class="nav-item">
            <router-link
              :to="{ name: 'reports.balanceSheet' }"
              class="nav-link"
            >
              <i class="fas fa-file-invoice-dollar nav-icon" />
              <p>{{ $t("sidebar.balance_sheet") }}</p>
            </router-link>
          </li>
          <li v-if="$can('summary-report')" class="nav-item">
            <router-link :to="{ name: 'reports.summary' }" class="nav-link">
              <i class="fas fa-file-contract nav-icon" />
              <p>{{ $t("sidebar.summary_report") }}</p>
            </router-link>
          </li>
          <li v-if="$can('profit-loss')" class="nav-item">
            <router-link :to="{ name: 'reports.profitLoss' }" class="nav-link">
              <i class="fas fa-chart-line nav-icon" />
              <p>{{ $t("sidebar.profit_loss_report") }}</p>
            </router-link>
          </li>
          <li v-if="$can('expense-report')" class="nav-item">
            <router-link :to="{ name: 'reports.expenses' }" class="nav-link">
              <i class="fas fa-chart-pie nav-icon" />
              <p>{{ $t("sidebar.expense_report") }}</p>
            </router-link>
          </li>
          <li v-if="$can('item-report')" class="nav-item">
            <router-link :to="{ name: 'reports.items' }" class="nav-link">
              <i class="fas fa-chart-bar nav-icon" />
              <p>{{ $t("sidebar.product_report") }}</p>
            </router-link>
          </li>
          <li v-if="$can('inventory-report')" class="nav-item">
            <router-link :to="{ name: 'reports.inventory' }" class="nav-link">
              <i class="fas fa-chart-pie nav-icon" />
              <p>{{ $t("sidebar.inventory_report") }}</p>
            </router-link>
          </li>
          <li class="nav-header text-bold">{{ $t("sidebar.account") }}</li>
          <li
            v-if="
              $can('role-permissions') ||
              $can('units') ||
              $can('currencies') ||
              $can('general-settings')
            "
            class="nav-item"
          >
            <router-link :to="{ name: 'setup.general' }" class="nav-link">
              <i class="nav-icon fas fa-cogs" />
              <p>{{ $t("sidebar.setup") }}</p>
            </router-link>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user" />
              <p>
                {{ $t("sidebar.account_sm") }}
                <i class="right fas fa-angle-left" />
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li v-if="$can('update-profile')" class="nav-item">
                <router-link :to="{ name: 'profile' }" class="nav-link">
                  <i class="nav-icon fas fa-user-circle" />
                  <p>{{ $t("sidebar.profile") }}</p>
                </router-link>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link admin-logout"
                  href="#"
                  @click.prevent="logout"
                >
                  <i class="nav-icon fas fa-power-off" />
                  {{ $t("sidebar.logout") }}
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-header text-bold">{{ $t("sidebar.resources") }}</li>
          <li v-if="$can('database-backup')" class="nav-item">
            <a
              href="https://docs.codeshaper.tech/acculance/#section-changelog"
              class="nav-link"
              target="__blank"
            >
              <i class="nav-icon fas fa-upload" />
              <p>{{ $t("sidebar.upgrade") }}</p>
            </a>
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="https://codeshaperbd.freshdesk.com/support/login"
              target="__blank"
            >
              <i class="nav-icon fas fa-ticket-alt" />
              <p>{{ $t("sidebar.support") }}</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/system-info" target="__blank">
              <i class="nav-icon fas fa-info" />
              <p>{{ $t("sidebar.system_info") }}</p>
            </a>
          </li>
          <li class="nav-item">
            <a
              @click="executeAction('optimize:clear')"
              class="nav-link cursor-pointer"
              href="#!"
            >
              <i class="nav-icon fas fa-trash" />
              <span>{{ $t("sidebar.clear_cache") }}</span>
            </a>
          </li>
          <li v-if="$can('database-backup')" class="nav-item">
            <router-link :to="{ name: 'backup' }" class="nav-link">
              <i class="nav-icon fas fa-download" />
              <p>{{ $t("sidebar.database_backup") }}</p>
            </router-link>
          </li>
          <li class="nav-item">
            <a
              href="https://docs.codeshaper.tech/acculance/"
              class="nav-link"
              target="__blank"
            >
              <i class="nav-icon fas fa-book" />
              <p>{{ $t("sidebar.doc") }}</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";
export default {
  data: () => ({
    appName: window.config.appName,
  }),
  // Map Getters
  computed: {
    ...mapGetters("operations", ["appInfo"]),
  },
  mounted() {
    $('[data-widget="treeview"]').Treeview("init");
  },
  methods: {
    menuOpen(routeName) {
      if (this.$route.name) {
        return this.$route.name.indexOf(routeName) > -1 ? true : false;
      }
      return false;
    },

    async executeAction(command) {
      await axios
        .get("/api/server?command=" + command)
        .then(({ data }) => {
          toast.fire({
            type: "success",
            title: "Cache cleared successfully!",
          });
        })
        .catch(() => {
          toast.fire({ type: "error", title: this.$t("common.error_msg") });
        });
    },

    async logout() {
      // Log out the user.
      await this.$store.dispatch("auth/logout");
      // Redirect to login.
      this.$router.push({ name: "login" });
    },
  },
};
</script>
