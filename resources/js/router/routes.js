function page(path) {
  return () =>
    import(/* webpackChunkName: '' */ `~/pages/${path}`).then(
      m => m.default || m
    );
}

export default [
  // Auth routes
  {
    path: "/",
    name: "welcome",
    component: page("auth/login.vue")
  },
  {
    path: "/login",
    name: "login",
    component: page("auth/login.vue")
  },
  {
    path: "/register",
    name: "register",
    component: page("auth/login.vue")
  },
  {
    path: "/password/reset",
    name: "password.request",
    component: page("auth/password/email.vue")
  },
  {
    path: "/password/reset/:token",
    name: "password.reset",
    component: page("auth/password/reset.vue")
  },
  {
    path: "/email/verify/:id",
    name: "verification.verify",
    component: page("auth/verification/verify.vue")
  },
  {
    path: "/email/resend",
    name: "verification.resend",
    component: page("auth/verification/resend.vue")
  },

  // Dashboard route
  {
    path: "/dashboard",
    name: "home",
    component: page("dashboard.vue")
  },
  // Dashboard stock alert
  {
    path: "/stock-alert-products",
    name: "stockAlertProducts",
    component: page("stock-alert-products.vue")
  },

  // Settings route
  {
    path: "/setup",
    name: "setup.index",
    component: page("setup/index.vue"),
    meta: {
      permissions: [
        "general-settings",
        "user-permission",
        "user-role",
        "units-management",
        "currencies-management",
        "vat-rate-management",
        "brands-management",
        "payment-method-management"
      ]
    }
  },

  // Update general settings route
  {
    path: "/setup/general",
    name: "setup.general",
    component: page("setup/general.vue"),
    meta: { permissions: ["general-settings"] }
  },

  // Update mail configuration route
  {
    path: "/setup/mail-configuration",
    name: "setup.mailConfiguration",
    component: page("setup/mail-configuration.vue"),
    meta: { permissions: ["mail-configuration"] }
  },

  // Update sms configuration route
  {
    path: "/setup/sms-configuration",
    name: "setup.smsConfiguration",
    component: page("setup/sms-configuration.vue"),
    meta: { permissions: ["sms-configuration"] }
  },

  // Permissions routes
  {
    path: "/setup/permissions",
    name: "permissions.index",
    component: page("setup/permissions/index.vue"),
    meta: { permissions: ["user-permission"] }
  },
  {
    path: "/setup/permissions/create",
    name: "permissions.create",
    component: page("setup/permissions/create.vue"),
    meta: { permissions: ["user-permission"] }
  },
  {
    path: "/setup/permissions/edit/:slug",
    name: "permissions.edit",
    component: page("setup/permissions/edit.vue"),
    meta: { permissions: ["user-permission"] }
  },

  // Role routes
  {
    path: "/setup/roles",
    name: "roles.index",
    component: page("setup/roles/index.vue"),
    meta: { permissions: ["user-role"] }
  },
  {
    path: "/setup/roles/create",
    name: "roles.create",
    component: page("setup/roles/create.vue"),
    meta: { permissions: ["user-role"] }
  },
  {
    path: "/setup/roles/edit/:slug",
    name: "roles.edit",
    component: page("setup/roles/edit.vue"),
    meta: { permissions: ["user-role"] }
  },

  // Unit routes
  {
    path: "/setup/units",
    name: "units.index",
    component: page("setup/units/index.vue"),
    meta: { permissions: ["units-management"] }
  },
  {
    path: "/setup/units/create",
    name: "units.create",
    component: page("setup/units/create.vue"),
    meta: { permissions: ["units-management"] }
  },
  {
    path: "/setup/units/edit/:slug",
    name: "units.edit",
    component: page("setup/units/edit.vue"),
    meta: { permissions: ["units-management"] }
  },

  // Currencies routes
  {
    path: "/setup/currencies",
    name: "currencies.index",
    component: page("setup/currencies/index.vue"),
    meta: { permissions: ["currencies-management"] }
  },
  {
    path: "/setup/currencies/create",
    name: "currencies.create",
    component: page("setup/currencies/create.vue"),
    meta: { permissions: ["currencies-management"] }
  },
  {
    path: "/setup/currencies/edit/:slug",
    name: "currencies.edit",
    component: page("setup/currencies/edit.vue"),
    meta: { permissions: ["currencies-management"] }
  },

  // Vat rate routes
  {
    path: "/setup/vat-rates",
    name: "vatRates.index",
    component: page("setup/vat-rates/index.vue"),
    meta: { permissions: ["vat-rate-management"] }
  },
  {
    path: "/setup/vat-rates/create",
    name: "vatRates.create",
    component: page("setup/vat-rates/create.vue"),
    meta: { permissions: ["vat-rate-management"] }
  },
  {
    path: "/setup/vat-rates/edit/:slug",
    name: "vatRates.edit",
    component: page("setup/vat-rates/edit.vue"),
    meta: { permissions: ["vat-rate-management"] }
  },

  // Brands routes
  {
    path: "/setup/brands",
    name: "brands.index",
    component: page("setup/brands/index.vue"),
    meta: { permissions: ["brands-management"] }
  },
  {
    path: "/setup/brands/create",
    name: "brands.create",
    component: page("setup/brands/create.vue"),
    meta: { permissions: ["brands-management"] }
  },
  {
    path: "/setup/brands/:slug",
    name: "brands.show",
    component: page("setup/brands/show.vue"),
    meta: { permissions: ["brands-management"] }
  },
  {
    path: "/setup/brands/edit/:slug",
    name: "brands.edit",
    component: page("setup/brands/edit.vue"),
    meta: { permissions: ["brands-management"] }
  },

  // Payment method routes
  {
    path: "/setup/payment-methods",
    name: "paymentMethods.index",
    component: page("setup/payment-methods/index.vue"),
    meta: { permissions: ["payment-method-management"] }
  },
  {
    path: "/setup/payment-methods/create",
    name: "paymentMethods.create",
    component: page("setup/payment-methods/create.vue"),
    meta: { permissions: ["payment-method-management"] }
  },
  {
    path: "/setup/payment-methods/edit/:slug",
    name: "paymentMethods.edit",
    component: page("setup/payment-methods/edit.vue"),
    meta: { permissions: ["payment-method-management"] }
  },

  // Expense categories routes
  {
    path: "/expense-categories",
    name: "expenseCats.index",
    component: page("expenses/categories/index.vue"),
    meta: { permissions: ["expense-category-list"] }
  },
  {
    path: "/expense-categories/create",
    name: "expenseCats.create",
    component: page("expenses/categories/create.vue"),
    meta: { permissions: ["expense-category-create"] }
  },
  {
    path: "/expense-categories/edit/:slug",
    name: "expenseCats.edit",
    component: page("expenses/categories/edit.vue"),
    meta: { permissions: ["expense-category-edit"] }
  },

  // Expense sub categories routes
  {
    path: "/expense-sub-categories",
    name: "expenseSubCats.index",
    component: page("expenses/sub-categories/index.vue"),
    meta: { permissions: ["expense-sub-category-list"] }
  },
  {
    path: "/expense-sub-categories/create",
    name: "expenseSubCats.create",
    component: page("expenses/sub-categories/create.vue"),
    meta: { permissions: ["expense-sub-category-create"] }
  },
  {
    path: "/expense-sub-categories/edit/:slug",
    name: "expenseSubCats.edit",
    component: page("expenses/sub-categories/edit.vue"),
    meta: { permissions: ["expense-sub-category-edit"] }
  },

  // Expense routes
  {
    path: "/expenses",
    name: "expenses.index",
    component: page("expenses/index.vue"),
    meta: { permissions: ["expense-list"] }
  },
  {
    path: "/expenses/create",
    name: "expenses.create",
    component: page("expenses/create.vue"),
    meta: { permissions: ["expense-create"] }
  },
  {
    path: "/expenses/:slug",
    name: "expenses.show",
    component: page("expenses/show.vue"),
    meta: { permissions: ["expense-view"] }
  },
  {
    path: "/expenses/edit/:slug",
    name: "expenses.edit",
    component: page("expenses/edit.vue"),
    meta: { permissions: ["expense-edit"] }
  },

  // Purchase routes
  {
    path: "/purchases",
    name: "purchases.index",
    component: page("purchases/index.vue"),
    meta: { permissions: ["purchase-list"] }
  },
  {
    path: "/purchases/create",
    name: "purchases.create",
    component: page("purchases/create.vue"),
    meta: { permissions: ["purchase-create"] }
  },
  {
    path: "/purchases/:slug",
    name: "purchases.show",
    component: page("purchases/show.vue"),
    meta: { permissions: ["purchase-view"] }
  },
  {
    path: "/purchases/edit/:slug",
    name: "purchases.edit",
    component: page("purchases/edit.vue"),
    meta: { permissions: ["purchase-edit"] }
  },

  // Purchase return routes
  {
    path: "/purchase-returns",
    name: "purchaseReturns.index",
    component: page("purchases/returns/index.vue"),
    meta: { permissions: ["purchase-return-list"] }
  },
  {
    path: "/purchase-returns/create",
    name: "purchaseReturns.create",
    component: page("purchases/returns/create.vue"),
    meta: { permissions: ["purchase-return-create"] }
  },
  {
    path: "/purchase-returns/:slug",
    name: "purchaseReturns.show",
    component: page("purchases/returns/show.vue"),
    meta: { permissions: ["purchase-return-view"] }
  },
  {
    path: "/purchase-returns/edit/:slug",
    name: "purchaseReturns.edit",
    component: page("purchases/returns/edit.vue"),
    meta: { permissions: ["purchase-return-edit"] }
  },

  // Quotation routes
  {
    path: "/quotations",
    name: "quotations.index",
    component: page("sales/quotations/index.vue"),
    meta: { permissions: ["quotation-list"]}
  },
  {
    path: "/quotations/create",
    name: "quotations.create",
    component: page("sales/quotations/create.vue"),
    meta: { permissions: ["quotation-create"] }
  },
  {
    path: "/quotations/:slug",
    name: "quotations.show",
    component: page("sales/quotations/show.vue"),
    meta: { permissions: ["quotation-view"] }
  },
  {
    path: "/quotations/:slug/create-invoice",
    name: "quotations.invoice",
    component: page("sales/quotations/invoice.vue"),
    meta: { permissions: ["quotation-to-invoice"] }
  },
  {
    path: "/quotations/edit/:slug",
    name: "quotations.edit",
    component: page("sales/quotations/edit.vue"),
    meta: { permissions: ["quotation-edit"] }
  },
  // POS routes
  {
    path: "/sales/pos",
    name: "pos.create",
    component: page("sales/pos/create.vue"),
    meta: { permissions: ["invoice-create"] }
  },
  {
    path: "/sales/invoice-test",
    name: "sales.pos.invoice",
    component: page("sales/pos/invoice-test.vue")
  },
  // Invoice routes
  {
    path: "/invoices",
    name: "invoices.index",
    component: page("sales/invoices/index.vue"),
    meta: { permissions: ["invoice-list"] }
  },
  {
    path: "/invoices/create",
    name: "invoices.create",
    component: page("sales/invoices/create.vue"),
    meta: { permissions: ["invoice-create"] }
  },
  {
    path: "/invoices/:slug",
    name: "invoices.show",
    component: page("sales/invoices/show.vue"),
    meta: { permissions: ["invoice-view"] }
  },
  {
    path: "/invoices/edit/:slug",
    name: "invoices.edit",
    component: page("sales/invoices/edit.vue"),
    meta: { permissions: ["invoice-edit"] }
  },

  // Invoice return routes
  {
    path: "/invoice-returns",
    name: "invoiceReturns.index",
    component: page("sales/returns/index.vue"),
    meta: { permissions: ["invoice-return-list"] }
  },
  {
    path: "/invoice-returns/create",
    name: "invoiceReturns.create",
    component: page("sales/returns/create.vue"),
    meta: { permissions: ["invoice-return-create"] }
  },
  {
    path: "/invoice-returns/:slug",
    name: "invoiceReturns.show",
    component: page("sales/returns/show.vue"),
    meta: { permissions: ["invoice-return-view"] }
  },
  {
    path: "/invoice-returns/edit/:slug",
    name: "invoiceReturns.edit",
    component: page("sales/returns/edit.vue"),
    meta: { permissions: ["invoice-return-edit"] }
  },

  // Account routes
  {
    path: "/cashbook/accounts",
    name: "accounts.index",
    component: page("cashbook/accounts/index.vue"),
    meta: { permissions: ["account-list"] }
  },
  {
    path: "/cashbook/accounts/create",
    name: "accounts.create",
    component: page("cashbook/accounts/create.vue"),
    meta: { permissions: ["account-create"] }
  },
  {
    path: "/cashbook/accounts/:slug",
    name: "accounts.show",
    component: page("cashbook/accounts/show.vue"),
    meta: { permissions: ["account-view"] }
  },
  {
    path: "/cashbook/accounts/edit/:slug",
    name: "accounts.edit",
    component: page("cashbook/accounts/edit.vue"),
    meta: { permissions: ["account-edit"] }
  },

  // Account balance routes
  {
    path: "/cashbook/balances",
    name: "balances.index",
    component: page("cashbook/balances/index.vue"),
    meta: { permissions: ["account-balance-list"] }
  },
  {
    path: "/cashbook/balances/create",
    name: "balances.create",
    component: page("cashbook/balances/create.vue"),
    meta: { permissions: ["account-balance-create"] }
  },
  {
    path: "/cashbook/balances/edit/:slug",
    name: "balances.edit",
    component: page("cashbook/balances/edit.vue"),
    meta: { permissions: ["account-balance-edit"] }
  },

  // Balance transfer routes
  {
    path: "/cashbook/transfer-balances",
    name: "transferBalances.index",
    component: page("cashbook/transfer-balances/index.vue"),
    meta: { permissions: ["account-transfer-balance-list"]}
  },
  {
    path: "/cashbook/transfer-balances/create",
    name: "transferBalances.create",
    component: page("cashbook/transfer-balances/create.vue"),
    meta: { permissions: ["account-transfer-balance-create"] }
  },
  {
    path: "/cashbook/transfer-balances/:slug",
    name: "transferBalances.show",
    component: page("cashbook/transfer-balances/show.vue"),
    meta: { permissions: ["account-transfer-balance-view"] }
  },
  {
    path: "/cashbook/transfer-balances/edit/:slug",
    name: "transferBalances.edit",
    component: page("cashbook/transfer-balances/edit.vue"),
    meta: { permissions: ["account-transfer-balance-edit"] }
  },

  // Transactions routes
  {
    path: "/cashbook/transactions",
    name: "transactions.index",
    component: page("cashbook/transactions/index.vue"),
    meta: { permissions: ["transaction-history"] }
  },

  // Invoice payment routes
  {
    path: "/payments/invoice",
    name: "invoicePayments.index",
    component: page("payments/clients/invoice/index.vue"),
    meta: { permissions: ["invoice-payment-list"] }
  },
  {
    path: "/payments/invoice/create",
    name: "invoicePayments.create",
    component: page("payments/clients/invoice/create.vue"),
    meta: { permissions: ["invoice-payment-create"] }
  },
  {
    path: "/payments/invoice/:slug",
    name: "invoicePayments.show",
    component: page("payments/clients/invoice/show.vue"),
    meta: { permissions: ["invoice-payment-view"] }
  },
  {
    path: "/payments/invoice/edit/:slug",
    name: "invoicePayments.edit",
    component: page("payments/clients/invoice/edit.vue"),
    meta: { permissions: ["invoice-payment-edit"] }
  },

  // Non invoice payment routes
  {
    path: "/payments/non-invoice",
    name: "nonInvoicePayments.index",
    component: page("payments/clients/non-invoice/index.vue"),
    meta: { permissions: ["non-invoice-payment-list"] }
  },
  {
    path: "/payments/non-invoice/create",
    name: "nonInvoicePayments.create",
    component: page("payments/clients/non-invoice/create.vue"),
    meta: { permissions: ["non-invoice-payment-create"] }
  },
  {
    path: "/payments/non-invoice/:slug",
    name: "nonInvoicePayments.show",
    component: page("payments/clients/non-invoice/show.vue"),
    meta: { permissions: ["non-invoice-payment-view"] }
  },
  {
    path: "/payments/non-invoice/edit/:slug",
    name: "nonInvoicePayments.edit",
    component: page("payments/clients/non-invoice/edit.vue"),
    meta: { permissions: ["non-invoice-payment-delete"] }
  },

  // Purchase payment routes
  {
    path: "/payments/purchase",
    name: "purchasePayments.index",
    component: page("payments/suppliers/purchase/index.vue"),
    meta: { permissions: ["purchase-payment-list"] }
  },
  {
    path: "/payments/purchase/create",
    name: "purchasePayments.create",
    component: page("payments/suppliers/purchase/create.vue"),
    meta: { permissions: ["purchase-payment-create"] }
  },
  {
    path: "/payments/purchase/:slug",
    name: "purchasePayments.show",
    component: page("payments/suppliers/purchase/show.vue"),
    meta: { permissions: ["purchase-payment-view"] }
  },
  {
    path: "/payments/purchase/edit/:slug",
    name: "purchasePayments.edit",
    component: page("payments/suppliers/purchase/edit.vue"),
    meta: { permissions: ["purchase-payment-edit"] }
  },

  // Non purchase payment routes
  {
    path: "/payments/non-purchase",
    name: "nonPurchasePayments.index",
    component: page("payments/suppliers/non-purchase/index.vue"),
    meta: { permissions: ["non-purchase-payment-list"] }
  },
  {
    path: "/payments/non-purchase/create",
    name: "nonPurchasePayments.create",
    component: page("payments/suppliers/non-purchase/create.vue"),
    meta: { permissions: ["non-purchase-payment-create"] }
  },
  {
    path: "/payments/non-purchase/:slug",
    name: "nonPurchasePayments.show",
    component: page("payments/suppliers/non-purchase/show.vue"),
    meta: { permissions: ["non-purchase-payment-show"] }
  },
  {
    path: "/payments/non-purchase/edit/:slug",
    name: "nonPurchasePayments.edit",
    component: page("payments/suppliers/non-purchase/edit.vue"),
    meta: { permissions: ["non-purchase-payment-edit"] }
  },

  // Loan authority routes
  {
    path: "/loan-authorities",
    name: "authorities.index",
    component: page("loans/authorities/index.vue"),
    meta: { permissions: ["loan-authority-list"] }
  },
  {
    path: "/loan-authorities/create",
    name: "authorities.create",
    component: page("loans/authorities/create.vue"),
    meta: { permissions: ["loan-authority-create"] }
  },
  {
    path: "/loan-authorities/:slug",
    name: "authorities.show",
    component: page("loans/authorities/show.vue"),
    meta: { permissions: ["loan-authority-view"] }
  },
  {
    path: "/loan-authorities/edit/:slug",
    name: "authorities.edit",
    component: page("loans/authorities/edit.vue"),
    meta: { permissions: ["loan-authority-edit"] }
  },

  // Loan routes
  {
    path: "/loans",
    name: "loans.index",
    component: page("loans/index.vue"),
    meta: { permissions: ["loan-list"] }
  },
  {
    path: "/loans/create",
    name: "loans.create",
    component: page("loans/create.vue"),
    meta: { permissions: ["loan-create"] }
  },
  {
    path: "/loans/:slug",
    name: "loans.show",
    component: page("loans/show.vue"),
    meta: { permissions: ["loan-view"] }
  },
  {
    path: "/loans/edit/:slug",
    name: "loans.edit",
    component: page("loans/edit.vue"),
    meta: { permissions: ["loan-edit"] }
  },

  // Loan payment routes
  {
    path: "/loan-payments",
    name: "loanPayments.index",
    component: page("loans/payments/index.vue"),
    meta: { permissions: ["loan-payment-list"] }
  },
  {
    path: "/loan-payments/create",
    name: "loanPayments.create",
    component: page("loans/payments/create.vue"),
    meta: { permissions: ["loan-payment-create"] }
  },
  {
    path: "/loan-payments/:slug",
    name: "loanPayments.show",
    component: page("loans/payments/show.vue"),
    meta: { permissions: ["loan-payment-view"] }
  },
  {
    path: "/loan-payments/edit/:slug",
    name: "loanPayments.edit",
    component: page("loans/payments/edit.vue"),
    meta: { permissions: ["loan-payment-edit"] }
  },

  // Asset types routes
  {
    path: "/asset-types",
    name: "assetTypes.index",
    component: page("assets/types/index.vue"),
    meta: { permissions: ["asset-type-list"] }
  },
  {
    path: "/asset-types/create",
    name: "assetTypes.create",
    component: page("assets/types/create.vue"),
    meta: { permissions: ["asset-type-create"] }
  },
  {
    path: "/asset-types/edit/:slug",
    name: "assetTypes.edit",
    component: page("assets/types/edit.vue"),
    meta: { permissions: ["asset-type-edit"] }
  },

  // Assets routes
  {
    path: "/assets",
    name: "assets.index",
    component: page("assets/index.vue"),
    meta: { permissions: ["asset-list"] }
  },
  {
    path: "/assets/create",
    name: "assets.create",
    component: page("assets/create.vue"),
    meta: { permissions: ["asset-create"] }
  },
  {
    path: "/assets/:slug",
    name: "assets.show",
    component: page("assets/show.vue"),
    meta: { permissions: ["asset-view"] }
  },
  {
    path: "/assets/edit/:slug",
    name: "assets.edit",
    component: page("assets/edit.vue"),
    meta: { permissions: ["asset-edit"] }
  },

  // Employee payroll routes
  {
    path: "/payroll",
    name: "payroll.index",
    component: page("payroll/index.vue"),
    meta: { permissions: ["payroll-list"] }
  },
  {
    path: "/payroll/create",
    name: "payroll.create",
    component: page("payroll/create.vue"),
    meta: { permissions: ["payroll-create"] }
  },
  {
    path: "/payroll/:slug",
    name: "payroll.show",
    component: page("payroll/show.vue"),
    meta: { permissions: ["payroll-view"] }
  },
  {
    path: "/payroll/edit/:slug",
    name: "payroll.edit",
    component: page("payroll/edit.vue"),
    meta: { permissions: ["payroll-edit"] }
  },

  // Client routes
  {
    path: "/clients",
    name: "clients.index",
    component: page("clients/index.vue"),
    meta: { permissions: ["client-list"] }
  },
  {
    path: "/clients/create",
    name: "clients.create",
    component: page("clients/create.vue"),
    meta: { permissions: ["client-create"] }
  },
  {
    path: "/clients/:slug",
    name: "clients.show",
    component: page("clients/show.vue"),
    meta: { permissions: ["client-view"] }
  },
  {
    path: "/clients/edit/:slug",
    name: "clients.edit",
    component: page("clients/edit.vue"),
    meta: { permissions: ["client-edit"] }
  },

  // Supplier routes
  {
    path: "/suppliers",
    name: "suppliers.index",
    component: page("suppliers/index.vue"),
    meta: { permissions: ["supplier-list"] }
  },
  {
    path: "/suppliers/create",
    name: "suppliers.create",
    component: page("suppliers/create.vue"),
    meta: { permissions: ["supplier-create"] }
  },
  {
    path: "/suppliers/:slug",
    name: "suppliers.show",
    component: page("suppliers/show.vue"),
    meta: { permissions: ["supplier-view"] }
  },
  {
    path: "/suppliers/edit/:slug",
    name: "suppliers.edit",
    component: page("suppliers/edit.vue"),
    meta: { permissions: ["supplier-edit"] }
  },

  // Department routes
  {
    path: "/departments",
    name: "departments.index",
    component: page("employees/departments/index.vue"),
    meta: { permissions: ["department-list"] }
  },
  {
    path: "/departments/create",
    name: "departments.create",
    component: page("employees/departments/create.vue"),
    meta: { permissions: ["department-create"] }
  },
  {
    path: "/departments/edit/:slug",
    name: "departments.edit",
    component: page("employees/departments/edit.vue"),
    meta: { permissions: ["department-edit"] }
  },

  // Employee routes
  {
    path: "/employees",
    name: "employees.index",
    component: page("employees/index.vue"),
    meta: { permissions: ["employee-list"] }
  },
  {
    path: "/employees/create",
    name: "employees.create",
    component: page("employees/create.vue"),
    meta: { permissions: ["employee-create"] }
  },
  {
    path: "/employees/:slug",
    name: "employees.show",
    component: page("employees/show.vue"),
    meta: { permissions: ["employee-view"] }
  },
  {
    path: "/employees/edit/:slug",
    name: "employees.edit",
    component: page("employees/edit.vue"),
    meta: { permissions: ["employee-edit"] }
  },

  // Employee salarey increment routes
  {
    path: "/increments",
    name: "increments.index",
    component: page("employees/increments/index.vue"),
    meta: { permissions: ["increment-list"] }
  },
  {
    path: "/increments/create",
    name: "increments.create",
    component: page("employees/increments/create.vue"),
    meta: { permissions: ["increment-create"] }
  },
  {
    path: "/increments/:slug",
    name: "increments.show",
    component: page("employees/increments/show.vue"),
    meta: { permissions: ["increment-view"] }
  },
  {
    path: "/increments/edit/:slug",
    name: "increments.edit",
    component: page("employees/increments/edit.vue"),
    meta: { permissions: ["increment-edit"] }
  },

  // Product categories routes
  {
    path: "/product-categories",
    name: "productCats.index",
    component: page("products/categories/index.vue"),
    meta: { permissions: ["product-category-list"] }
  },
  {
    path: "/product-categories/create",
    name: "productCats.create",
    component: page("products/categories/create.vue"),
    meta: { permissions: ["product-category-create"] }
  },
  {
    path: "/product-categories/edit/:slug",
    name: "productCats.edit",
    component: page("products/categories/edit.vue"),
    meta: { permissions: ["product-category-edit"] }
  },

  // Product sub categories routes
  {
    path: "/product-sub-categories",
    name: "productSubCats.index",
    component: page("products/sub-categories/index.vue"),
    meta: { permissions: ["product-sub-category-list"] }
  },
  {
    path: "/product-sub-categories/create",
    name: "productSubCats.create",
    component: page("products/sub-categories/create.vue"),
    meta: { permissions: ["product-sub-category-create"] }
  },
  {
    path: "/product-sub-categories/edit/:slug",
    name: "productSubCats.edit",
    component: page("products/sub-categories/edit.vue"),
    meta: { permissions: ["product-sub-category-edit"] }
  },

  // Product routes
  {
    path: "/products",
    name: "products.index",
    component: page("products/index.vue"),
    meta: { permissions: ["product-list"] }
  },
  {
    path: "/products/create",
    name: "products.create",
    component: page("products/create.vue"),
    meta: { permissions: ["product-create"] }
  },
  {
    path: "/products/:slug",
    name: "products.show",
    component: page("products/show.vue"),
    meta: { permissions: ["product-view"] }
  },
  {
    path: "/products/edit/:slug",
    name: "products.edit",
    component: page("products/edit.vue"),
    meta: { permissions: ["product-edit"] }
  },

  // Product barcode
  {
    path: "/barcode/print-barcode",
    name: "barcode.print",
    component: page("products/barcode.vue"),
    meta: { permissions: ["print-barcode"] }
  },

  // Product inventory route
  {
    path: "/inventory",
    name: "inventory.index",
    component: page("inventory/index.vue"),
    meta: { permissions: ["inventory-view"] }
  },
  {
    path: "/nonzero-inventory",
    name: "nonzeroInventory.index",
    component: page("inventory/non-zero-inventory.vue"),
    meta: { permissions: ["inventory-view"] }
  },
  {
    path: "/inventory-history/:slug",
    name: "inventory.history",
    component: page("inventory/history.vue"),
    meta: { permissions: ["inventory-history"] }
  },

  // Inventory adjustment routes
  {
    path: "/inventory-adjustments",
    name: "adjustments.index",
    component: page("inventory/adjustments/index.vue"),
    meta: { permissions: ["adjustment-list"] }
  },
  {
    path: "/inventory-adjustments/create",
    name: "adjustments.create",
    component: page("inventory/adjustments/create.vue"),
    meta: { permissions: ["adjustment-create"] }
  },
  {
    path: "/inventory-adjustments/:slug",
    name: "adjustments.show",
    component: page("inventory/adjustments/show.vue"),
    meta: { permissions: ["adjustment-view"] }
  },
  {
    path: "/inventory-adjustments/edit/:slug",
    name: "adjustments.edit",
    component: page("inventory/adjustments/edit.vue"),
    meta: { permissions: ["adjustment-edit"] }
  },

  // Report Routes
  {
    path: "/reports/balance-sheet",
    name: "reports.balanceSheet",
    component: page("reports/balance-sheet.vue"),
    meta: { permissions: ["balance-sheet"] }
  },
  {
    path: "/reports/summary",
    name: "reports.summary",
    component: page("reports/summary.vue"),
    meta: { permissions: ["summary-report"] }
  },
  {
    path: "/reports/profit-loss",
    name: "reports.profitLoss",
    component: page("reports/profit-loss.vue"),
    meta: { permissions: ["profit-loss"] }
  },
  {
    path: "/reports/expenses",
    name: "reports.expenses",
    component: page("reports/expenses.vue"),
    meta: { permissions: ["expense-report"] }
  },
  {
    path: "/reports/items",
    name: "reports.items",
    component: page("reports/items.vue"),
    meta: { permissions: ["item-report"] }
  },
  {
    path: "/reports/inventory",
    name: "reports.inventory",
    component: page("reports/inventory.vue"),
    meta: { permissions: ["inventory-report"] }
  },

  // Update profile route
  {
    path: "/profile",
    name: "profile",
    component: page("profile.vue"),
    meta: {
      permissions: ["update-profile"]
    }
  },
  // Database backup route
  {
    path: "/backup",
    name: "backup",
    component: page("backup.vue"),
    meta: {
      permissions: ["database-backup"]
    }
  },
  // Permission denied
  {
    path: "/permission-denied",
    name: "permission-denied",
    component: page("permission-denied.vue")
  },

  {
    path: "*",
    component: page("errors/404.vue")
  }
];
