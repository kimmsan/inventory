<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row">
      <div class="col-md-12 col-lg-3">
        <div class="card">
          <div class="card-body box-profile">
            <div class="text-center mb-2">
              <a
                v-if="allData.image"
                href="#"
                id="show-modal"
                @click="previewModal(allData.image)"
              >
                <img
                  :src="allData.image"
                  class="profile-user-img img-fluid img-circle"
                  loading="lazy"
                />
              </a>
              <div v-else class="bg-secondary no-preview-lg">
                <small>{{ $t("common.no_preview") }}</small>
              </div>
            </div>
            <h3 class="profile-username text-center">{{ allData.name }}</h3>
            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <strong>{{ $t("common.supplier_id") }}</strong>
                <span class="float-right">{{
                  allData.supplierID | withPrefix(supplierPrefix)
                }}</span>
              </li>
              <li class="list-group-item">
                <strong>{{ $t("common.name") }}</strong>
                <span class="float-right">{{ allData.name }}</span>
              </li>
              <li class="list-group-item">
                <strong>{{ $t("common.email") }}</strong>
                <span class="float-right">{{ allData.email }}</span>
              </li>
              <li class="list-group-item">
                <strong>{{ $t("common.contact_number") }}</strong>
                <span class="float-right">{{ allData.phoneNumber }}</span>
              </li>
              <li class="list-group-item">
                <strong>{{ $t("common.company_name") }}</strong>
                <span class="float-right">{{ allData.companyName }}</span>
              </li>
              <li class="list-group-item">
                <strong>{{ $t("common.address") }}</strong>
                <span class="float-right">{{ allData.address }}</span>
              </li>
            </ul>
            <span
              v-if="allData.status === 1"
              class="btn-block btn bg-success"
              >{{ $t("common.active") }}</span
            >
            <span v-else class="badge bg-danger">{{
              $t("common.in_active")
            }}</span>
          </div>
        </div>
      </div>
      <!-- /.col -->

      <div class="col-md-12 col-lg-9">
        <div class="row">
          <div class="col-lg-6 col-md-6 col-12">
            <div class="card bg-info">
              <div class="card-content">
                <div class="card-body pb-1">
                  <div class="row">
                    <div class="col-6">
                      <h6 class="text-white">
                        {{ $t("purchases.list.common.purchase_total") }}
                      </h6>
                      <h6 class="text-white">
                        {{ $t("payments.common.non_purchase_total") }}
                      </h6>
                      <hr />
                      <h4 class="text-white mb-1">{{ $t("common.total") }}</h4>
                    </div>
                    <div class="col-6 text-right">
                      <h6 class="text-white">
                        {{ allData.purchaseTotal | withCurrency }}
                      </h6>
                      <h6 class="text-white">
                        {{ allData.nonPurchaseTotalDue | withCurrency }}
                      </h6>
                      <hr />
                      <h4 class="text-white mb-1">
                        {{
                          (allData.purchaseTotal + allData.nonPurchaseTotalDue)
                            | withCurrency
                        }}
                      </h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-12">
            <div class="card bg-danger">
              <div class="card-content">
                <div class="card-body pb-1">
                  <div class="row">
                    <div class="col-6">
                      <h6 class="text-white">
                        {{ $t("purchases.list.common.purchase_due") }}
                      </h6>
                      <h6 class="text-white">
                        {{ $t("payments.common.non_purchase_due") }}
                      </h6>
                      <hr />
                      <h4 class="text-white mb-1">
                        {{ $t("common.total_due") }}
                      </h4>
                    </div>
                    <div class="col-6 text-right">
                      <h6 class="text-white">
                        {{ allData.purchaseTotalDue | withCurrency }}
                      </h6>
                      <h6 class="text-white">
                        {{ allData.nonPurchaseCurrentDue | withCurrency }}
                      </h6>
                      <hr />
                      <h4 class="text-white mb-1">
                        {{
                          (allData.purchaseTotalDue +
                            allData.nonPurchaseCurrentDue)
                            | withCurrency
                        }}
                      </h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div
          v-if="
            $can('purchase-list') ||
            $can('purchase-return-list') ||
            $can('purchase-payment-list') ||
            $can('non-purchase-payment-list')
          "
          class="card"
        >
          <div class="card-header p-2">
            <div class="row">
              <div class="col-md-10">
                <ul class="nav nav-pills">
                  <li v-if="$can('purchase-list')" class="nav-item">
                    <a
                      class="nav-link active"
                      href="#purchases"
                      data-toggle="tab"
                      @click="activeTab = 'purchases'"
                    >
                      <i class="fas fa-shopping-basket"></i>
                      {{ $t("common.purchases") }}
                      <span v-if="pagination" class="badge badge-dark">{{
                        pagination.total
                      }}</span></a
                    >
                  </li>
                  <li v-if="$can('purchase-return-list')" class="nav-item">
                    <a
                      @click="getSupplierReturns"
                      class="nav-link"
                      href="#purchase-returns"
                      data-toggle="tab"
                    >
                      <i class="fas fa-undo-alt"></i>
                      {{ $t("purchases.returns.index.page_title") }}
                      <span v-if="returnPagination" class="badge badge-dark">{{
                        returnPagination.total
                      }}</span></a
                    >
                  </li>
                  <li v-if="$can('purchase-payment-list')" class="nav-item">
                    <a
                      @click="getSupplierPayments"
                      class="nav-link"
                      href="#purchase-payments"
                      data-toggle="tab"
                    >
                      <i class="fas fa-receipt"></i>
                      {{ $t("payments.suppliers.purchase.index.page_title") }}
                      <span v-if="paymentPagination" class="badge badge-dark">{{
                        paymentPagination.total
                      }}</span></a
                    >
                  </li>
                  <li v-if="$can('non-purchase-payment-list')" class="nav-item">
                    <a
                      @click="getNonPurchaseTransactions"
                      class="nav-link"
                      href="#non-purchase-transactions"
                      data-toggle="tab"
                    >
                      <i class="fas fa-money-bill"></i>
                      {{ $t("suppliers.common.non_purchase_transactions") }}
                      <span
                        v-if="transactionPagination"
                        class="badge badge-dark"
                        >{{ transactionPagination.total }}</span
                      ></a
                    >
                  </li>
                  <li v-if="$can('invoice-list')" class="nav-item">
                    <a
                      class="nav-link"
                      href="#ledger"
                      data-toggle="tab"
                      @click="getLedger"
                    >
                      <i class="fas fa-list-ul"></i>
                      {{ $t("common.ledger") }}
                    </a>
                  </li>
                </ul>
              </div>
              <div class="col-md-2 text-right">
                <div class="btn-group">
                  <a
                    @click="generatePDF()"
                    href="#"
                    class="btn btn-primary"
                    v-tooltip="$t('download')"
                  >
                    <i class="fas fa-download"></i>
                  </a>
                  <a
                    @click="print()"
                    href="#"
                    class="btn btn-secondary"
                    v-tooltip="$t('print')"
                  >
                    <i class="fas fa-print"></i>
                  </a>
                  <a
                    @click="refreshTable(activeTab)"
                    href="#"
                    v-tooltip="'Refresh'"
                    class="btn btn-success"
                  >
                    <i class="fas fa-sync"></i>
                  </a>
                  <router-link
                    :to="{ name: 'suppliers.index' }"
                    class="btn btn-dark float-right"
                    v-tooltip="$t('common.back')"
                  >
                    <i class="fas fa-long-arrow-alt-left" />
                  </router-link>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content" id="content-to-pdf">
              <div class="col-md-12" v-if="headerShow">
                <h4 class="text-capitalize">
                  {{ activeTab.replace(/-/g, " ") }}
                </h4>
                <strong> {{ $t("common.date") }}</strong> :
                {{ date | moment("Do MMM, YYYY") }}<br />
                <strong>{{ $t("common.name") }}</strong> : {{ allData.name
                }}<br />
                <strong>{{ $t("common.contact_number") }}</strong> :
                {{ allData.phoneNumber }}<br />
                <strong>{{ $t("common.email") }}</strong> : {{ allData.email
                }}<br />
                <hr />
              </div>
              <!-- Purchases -->
              <div
                v-if="$can('purchase-list')"
                class="tab-pane active"
                id="purchases"
              >
                <div>
                  <div class="card-body p-0 position-relative">
                    <div
                      class="row no-print"
                      id="element-to-hide"
                      data-html2canvas-ignore="true"
                    >
                      <div class="col-12 col-md-9 mb-2">
                        <search
                          v-model="query"
                          @reset-pagination="resetPagination"
                          @reload="reload"
                        />
                      </div>
                      <div class="col-12 col-md-3 pull-right text-right">
                        <date-range-picker
                          ref="picker"
                          opens="left"
                          :locale-data="locale"
                          :minDate="minDate"
                          :maxDate="maxDate"
                          :singleDatePicker="false"
                          :showWeekNumbers="false"
                          :showDropdowns="true"
                          :autoApply="true"
                          v-model="dateRange"
                          @update="updateValues('purchases')"
                          :linkedCalendars="true"
                          class="c-w-100"
                        >
                          <template
                            v-slot:input="picker"
                            style="min-width: 350px"
                          >
                            {{ picker.startDate | startDate }} -
                            {{ picker.endDate | endDate }}
                          </template>
                        </date-range-picker>
                      </div>
                    </div>
                    <table-loading v-show="loading" />
                    <div class="table-responsive table-custom mt-3">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>{{ $t("common.s_no") }}</th>
                            <th>
                              {{ $t("purchases.list.common.purchase_no") }}
                            </th>
                            <th>{{ $t("common.date") }}</th>
                            <th>{{ $t("common.subtotal") }}</th>
                            <th>{{ $t("common.transport") }}</th>
                            <th>{{ $t("common.discount") }}</th>
                            <th>{{ $t("common.net_total") }}</th>
                            <th>{{ $t("common.total_paid") }}</th>
                            <th>{{ $t("common.total_due") }}</th>
                            <th>{{ $t("common.status") }}</th>
                            <th
                              v-if="
                                $can('purchase-edit') ||
                                $can('purchase-view') ||
                                $can('purchase-delete')
                              "
                              class="text-right no-print"
                              id="element-to-hide"
                              data-html2canvas-ignore="true"
                            >
                              {{ $t("common.action") }}
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-show="items && items.length"
                            v-for="(data, i) in items"
                            :key="i"
                          >
                            <td>
                              <span
                                v-if="pagination && pagination.current_page > 1"
                              >
                                {{
                                  pagination.per_page *
                                    (pagination.current_page - 1) +
                                  (i + 1)
                                }}
                              </span>
                              <span v-else>{{ i + 1 }}</span>
                            </td>
                            <td>
                              <router-link
                                v-if="$can('purchase-view')"
                                :to="{
                                  name: 'purchases.show',
                                  params: { slug: data.slug },
                                }"
                              >
                                {{ data.code | withPrefix(purchasePrefix) }}
                              </router-link>
                              <span v-else
                                >{{ data.code | withPrefix(purchasePrefix) }}
                              </span>
                            </td>
                            <td>
                              <span v-if="data.purchaseDate">{{
                                data.purchaseDate | moment("Do MMM, YYYY")
                              }}</span>
                            </td>
                            <td>{{ data.subTotal | withCurrency }}</td>
                            <td>{{ data.transport | withCurrency }}</td>
                            <td>{{ data.totalDiscount | withCurrency }}</td>
                            <td>{{ data.purchaseTotal | withCurrency }}</td>
                            <td>{{ data.totalPaid | withCurrency }}</td>
                            <td>{{ data.due | withCurrency }}</td>
                            <td>
                              <span
                                v-if="data.status === 1"
                                class="badge bg-success"
                                >{{ $t("common.active") }}</span
                              >
                              <span v-else class="badge bg-danger">{{
                                $t("common.in_active")
                              }}</span>
                            </td>
                            <td
                              v-if="
                                $can('purchase-edit') ||
                                $can('purchase-view') ||
                                $can('purchase-delete')
                              "
                              class="text-right no-print"
                              id="element-to-hide"
                              data-html2canvas-ignore="true"
                            >
                              <div class="btn-group">
                                <router-link
                                  v-if="$can('purchase-view')"
                                  v-tooltip="$t('common.view')"
                                  :to="{
                                    name: 'purchases.show',
                                    params: { slug: data.slug },
                                  }"
                                  class="btn btn-primary btn-sm"
                                >
                                  <i class="fas fa-eye" />
                                </router-link>
                                <router-link
                                  v-if="$can('purchase-edit')"
                                  v-tooltip="$t('common.edit')"
                                  :to="{
                                    name: 'purchases.edit',
                                    params: { slug: data.slug },
                                  }"
                                  class="btn btn-info btn-sm"
                                >
                                  <i class="fas fa-edit" />
                                </router-link>
                                <a
                                  v-if="$can('purchase-delete')"
                                  v-tooltip="$t('common.delete')"
                                  href="#"
                                  class="btn btn-danger btn-sm"
                                  @click="deletePurchaseData(data.slug)"
                                >
                                  <i class="fas fa-trash" />
                                </a>
                              </div>
                            </td>
                          </tr>
                          <tr v-show="!loading && items && !items.length">
                            <td colspan="11">
                              <EmptyTable />
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- NEW PAGINATION -->
                  <div
                    v-if="pagination && pagination.total > 0"
                    class="dtable-footer no-print"
                    id="element-to-hide"
                    data-html2canvas-ignore="true"
                  >
                    <div class="form-group row display-per-page">
                      <label>{{ $t("per_page") }} </label>
                      <div>
                        <select
                          @change="updatePerPager('purchases')"
                          v-model="perPage"
                          class="form-control form-control-sm ml-1"
                        >
                          <!-- options component -->
                          <option
                            v-for="(option, i) in options"
                            :value="option.value"
                            :key="i"
                          >
                            {{ option.text }}
                          </option>
                        </select>
                      </div>
                    </div>
                    <!-- pagination-start -->
                    <pagination
                      v-if="pagination && pagination.last_page > 1"
                      :pagination="pagination"
                      :offset="5"
                      class="justify-flex-end"
                      @paginate="paginate"
                    />
                    <!-- pagination-end -->
                  </div>
                  <!-- NEW PAGINATION END -->
                </div>
              </div>

              <!-- purchase-returns -->
              <div
                v-if="$can('purchase-return-list')"
                class="tab-pane"
                id="purchase-returns"
              >
                <div>
                  <div class="card-body p-0 position-relative">
                    <div
                      class="row no-print"
                      id="element-to-hide"
                      data-html2canvas-ignore="true"
                    >
                      <div class="col-12 col-md-9 mb-2">
                        <search
                          v-model="returnsQuery"
                          @reset-pagination="resetReturnPagination"
                          @reload="returnsReload"
                        />
                      </div>
                      <div class="col-12 col-md-3 pull-right text-right">
                        <date-range-picker
                          ref="picker"
                          opens="left"
                          :locale-data="locale"
                          :minDate="minDate"
                          :maxDate="maxDate"
                          :singleDatePicker="false"
                          :showWeekNumbers="false"
                          :showDropdowns="true"
                          :autoApply="true"
                          v-model="dateRange"
                          @update="updateValues('purchase-returns')"
                          :linkedCalendars="true"
                          class="c-w-100"
                        >
                          <template
                            v-slot:input="picker"
                            style="min-width: 350px"
                          >
                            {{ picker.startDate | startDate }} -
                            {{ picker.endDate | endDate }}
                          </template>
                        </date-range-picker>
                      </div>
                    </div>
                    <table-loading v-show="returnLoading" />
                    <div class="table-responsive table-custom mt-3">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>{{ $t("common.s_no") }}</th>
                            <th>{{ $t("purchases.list.common.return_no") }}</th>
                            <th>
                              {{ $t("purchases.list.common.purchase_no") }}
                            </th>
                            <th>
                              {{ $t("purchases.returns.common.return_reason") }}
                            </th>
                            <th>{{ $t("common.return_cost") }}</th>
                            <th>{{ $t("common.date") }}</th>
                            <th>{{ $t("common.status") }}</th>
                            <th
                              v-if="
                                $can('purchase-return-edit') ||
                                $can('purchase-return-view') ||
                                $can('purchase-return-delete')
                              "
                              class="text-right no-print"
                              id="element-to-hide"
                              data-html2canvas-ignore="true"
                            >
                              {{ $t("common.action") }}
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-show="allReturns && allReturns.length"
                            v-for="(data, i) in allReturns"
                            :key="i"
                          >
                            <td>
                              <span
                                v-if="
                                  returnPagination &&
                                  returnPagination.current_page > 1
                                "
                              >
                                {{
                                  returnPagination.per_page *
                                    (returnPagination.current_page - 1) +
                                  (i + 1)
                                }}
                              </span>
                              <span v-else>{{ i + 1 }}</span>
                            </td>
                            <td>
                              <router-link
                                v-if="$can('purchase-return-view')"
                                :to="{
                                  name: 'purchaseReturns.show',
                                  params: { slug: data.slug },
                                }"
                              >
                                {{
                                  data.purReturnNo
                                    | withPrefix(purchaseReturnPrefix)
                                }}
                              </router-link>
                              <span v-else>{{
                                data.purReturnNo
                                  | withPrefix(purchaseReturnPrefix)
                              }}</span>
                            </td>
                            <td>
                              {{ data.purchaseNo | withPrefix(purchasePrefix) }}
                            </td>
                            <td>{{ data.reason }}</td>
                            <td>{{ data.totalReturn | withCurrency }}</td>
                            <td>
                              <span v-if="data.returnDate">{{
                                data.returnDate | moment("Do MMM, YYYY")
                              }}</span>
                            </td>
                            <td>
                              <span
                                v-if="data.status === 1"
                                class="badge bg-success"
                                >{{ $t("common.active") }}</span
                              >
                              <span v-else class="badge bg-danger">{{
                                $t("common.in_active")
                              }}</span>
                            </td>
                            <td
                              v-if="
                                $can('purchase-return-edit') ||
                                $can('purchase-return-view') ||
                                $can('purchase-return-delete')
                              "
                              class="text-right no-print"
                              id="element-to-hide"
                              data-html2canvas-ignore="true"
                            >
                              <div class="btn-group">
                                <router-link
                                  v-if="$can('purchase-return-view')"
                                  v-tooltip="$t('common.view')"
                                  :to="{
                                    name: 'purchaseReturns.show',
                                    params: { slug: data.slug },
                                  }"
                                  class="btn btn-primary btn-sm"
                                >
                                  <i class="fas fa-eye" />
                                </router-link>
                                <router-link
                                  v-if="$can('purchase-return-edit')"
                                  v-tooltip="$t('common.edit')"
                                  :to="{
                                    name: 'purchaseReturns.edit',
                                    params: { slug: data.slug },
                                  }"
                                  class="btn btn-info btn-sm"
                                >
                                  <i class="fas fa-edit" />
                                </router-link>
                                <a
                                  v-if="$can('purchase-return-delete')"
                                  v-tooltip="$t('common.delete')"
                                  href="#"
                                  class="btn btn-danger btn-sm"
                                  @click="deletePurchaseReturnData(data.slug)"
                                >
                                  <i class="fas fa-trash" />
                                </a>
                              </div>
                            </td>
                          </tr>
                          <tr v-show="!loading && !allReturns.length">
                            <td colspan="8">
                              <EmptyTable />
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- NEW PAGINATION -->
                  <div
                    v-if="returnPagination && returnPagination.total > 0"
                    class="dtable-footer no-print"
                    id="element-to-hide"
                    data-html2canvas-ignore="true"
                  >
                    <div class="form-group row display-per-page">
                      <label>{{ $t("per_page") }} </label>
                      <div>
                        <select
                          @change="updatePerPager('purchase-returns')"
                          v-model="perPage"
                          class="form-control form-control-sm ml-1"
                        >
                          <!-- options component -->
                          <option
                            v-for="(option, i) in options"
                            :value="option.value"
                            :key="i"
                          >
                            {{ option.text }}
                          </option>
                        </select>
                      </div>
                    </div>
                    <!-- pagination-start -->
                    <pagination
                      v-if="returnPagination && returnPagination.last_page > 1"
                      :pagination="returnPagination"
                      :offset="5"
                      class="justify-flex-end"
                      @paginate="returnsPaginate"
                    />
                    <!-- pagination-end -->
                  </div>
                  <!-- NEW PAGINATION END -->
                </div>
              </div>

              <!-- purchase-payments -->
              <div
                v-if="$can('purchase-payment-list')"
                class="tab-pane"
                id="purchase-payments"
              >
                <div>
                  <div class="card-body p-0 position-relative">
                    <div
                      class="row no-print"
                      id="element-to-hide"
                      data-html2canvas-ignore="true"
                    >
                      <div class="col-12 col-md-9 mb-2">
                        <search
                          v-model="paymentsQuery"
                          @reset-pagination="resetPaymetnsPagination"
                          @reload="paymentsReload"
                        />
                      </div>
                      <div class="col-12 col-md-3 pull-right text-right">
                        <date-range-picker
                          ref="picker"
                          opens="left"
                          :locale-data="locale"
                          :minDate="minDate"
                          :maxDate="maxDate"
                          :singleDatePicker="false"
                          :showWeekNumbers="false"
                          :showDropdowns="true"
                          :autoApply="true"
                          v-model="dateRange"
                          @update="updateValues('purchase-payments')"
                          :linkedCalendars="true"
                          class="c-w-100"
                        >
                          <template
                            v-slot:input="picker"
                            style="min-width: 350px"
                          >
                            {{ picker.startDate | startDate }} -
                            {{ picker.endDate | endDate }}
                          </template>
                        </date-range-picker>
                      </div>
                    </div>
                    <table-loading v-show="paymentsLoading" />
                    <div class="table-responsive table-custom mt-3">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>{{ $t("common.s_no") }}</th>
                            <th>
                              {{ $t("purchases.list.common.purchase_no") }}
                            </th>
                            <th>{{ $t("common.total") }}</th>
                            <th>{{ $t("common.paid_amount") }}</th>
                            <th>{{ $t("common.account") }}</th>
                            <th>{{ $t("common.payment_date") }}</th>
                            <th>{{ $t("common.status") }}</th>
                            <th
                              v-if="
                                $can('purchase-payment-edit') ||
                                $can('purchase-payment-view') ||
                                $can('purchase-payment-delete')
                              "
                              class="text-right no-print"
                              id="element-to-hide"
                              data-html2canvas-ignore="true"
                            >
                              {{ $t("common.action") }}
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-show="allPayments && allPayments.length"
                            v-for="(data, i) in allPayments"
                            :key="i"
                          >
                            <td>
                              <span
                                v-if="
                                  paymentPagination &&
                                  paymentPagination.current_page > 1
                                "
                              >
                                {{
                                  paymentPagination.per_page *
                                    (paymentPagination.current_page - 1) +
                                  (i + 1)
                                }}
                              </span>
                              <span v-else>{{ i + 1 }}</span>
                            </td>
                            <td v-if="data.purchase">
                              <router-link
                                v-if="$can('purchase-view')"
                                :to="{
                                  name: 'purchases.show',
                                  params: { slug: data.purchase.slug },
                                }"
                              >
                                {{ data.purchase.purchaseNo }}
                              </router-link>
                              <span v-else>{{ data.purchase.purchaseNo }}</span>
                            </td>

                            <td v-if="data.purchase">
                              {{ data.purchase.purchaseTotal | withCurrency }}
                            </td>
                            <td>{{ data.amount | withCurrency }}</td>
                            <td>
                              <span v-if="data.account">{{
                                data.account.label
                              }}</span>
                            </td>
                            <td>
                              <span v-if="data.date">{{
                                data.date | moment("Do MMM, YYYY")
                              }}</span>
                            </td>
                            <td>
                              <span
                                v-if="data.status === 1"
                                class="badge bg-success"
                                >{{ $t("common.active") }}</span
                              >
                              <span v-else class="badge bg-danger">{{
                                $t("common.in_active")
                              }}</span>
                            </td>
                            <td
                              v-if="
                                $can('purchase-payment-edit') ||
                                $can('purchase-payment-view') ||
                                $can('purchase-payment-delete')
                              "
                              class="text-right no-print"
                              id="element-to-hide"
                              data-html2canvas-ignore="true"
                            >
                              <div class="btn-group">
                                <router-link
                                  v-if="$can('purchase-payment-view')"
                                  v-tooltip="$t('common.view')"
                                  :to="{
                                    name: 'purchasePayments.show',
                                    params: { slug: data.slug },
                                  }"
                                  class="btn btn-primary btn-sm"
                                >
                                  <i class="fas fa-eye" />
                                </router-link>
                                <router-link
                                  v-if="$can('purchase-payment-edit')"
                                  v-tooltip="$t('common.edit')"
                                  :to="{
                                    name: 'purchasePayments.edit',
                                    params: { slug: data.slug },
                                  }"
                                  class="btn btn-info btn-sm"
                                >
                                  <i class="fas fa-edit" />
                                </router-link>
                                <a
                                  v-if="$can('purchase-payment-delete')"
                                  v-tooltip="$t('common.delete')"
                                  href="#"
                                  class="btn btn-danger btn-sm"
                                  @click="deletePaymentData(data.slug)"
                                >
                                  <i class="fas fa-trash" />
                                </a>
                              </div>
                            </td>
                          </tr>
                          <tr v-show="!loading && !allPayments.length">
                            <td colspan="8">
                              <EmptyTable />
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- NEW PAGINATION -->
                  <div
                    v-if="paymentPagination && paymentPagination.total > 0"
                    class="dtable-footer no-print"
                    id="element-to-hide"
                    data-html2canvas-ignore="true"
                  >
                    <div class="form-group row display-per-page">
                      <label>{{ $t("per_page") }} </label>
                      <div>
                        <select
                          @change="updatePerPager('purchases-payments')"
                          v-model="perPage"
                          class="form-control form-control-sm ml-1"
                        >
                          <!-- options component -->
                          <option
                            v-for="(option, i) in options"
                            :value="option.value"
                            :key="i"
                          >
                            {{ option.text }}
                          </option>
                        </select>
                      </div>
                    </div>
                    <!-- pagination-start -->
                    <pagination
                      v-if="
                        paymentPagination && paymentPagination.last_page > 1
                      "
                      :pagination="
                        allPayments ? paymentPagination : { current_page: 1 }
                      "
                      :offset="5"
                      class="justify-flex-end"
                      @paginate="paymentsPaginate"
                    />
                    <!-- pagination-end -->
                  </div>
                  <!-- NEW PAGINATION END -->
                </div>
              </div>

              <!-- non-purchase-transactions -->
              <div
                v-if="$can('non-purchase-payment-list')"
                class="tab-pane"
                id="non-purchase-transactions"
              >
                <div>
                  <div class="card-body p-0 position-relative">
                    <div
                      class="row no-print"
                      id="element-to-hide"
                      data-html2canvas-ignore="true"
                    >
                      <div class="col-12 col-md-9 mb-2">
                        <search
                          v-model="transactionsQuery"
                          @reset-pagination="resetTransactionsPagination"
                          @reload="transactionsReload"
                        />
                      </div>
                      <div class="col-12 col-md-3 pull-right text-right">
                        <date-range-picker
                          ref="picker"
                          opens="left"
                          :locale-data="locale"
                          :minDate="minDate"
                          :maxDate="maxDate"
                          :singleDatePicker="false"
                          :showWeekNumbers="false"
                          :showDropdowns="true"
                          :autoApply="true"
                          v-model="dateRange"
                          @update="updateValues('non-purchase-transactions')"
                          :linkedCalendars="true"
                          class="c-w-100"
                        >
                          <template
                            v-slot:input="picker"
                            style="min-width: 350px"
                          >
                            {{ picker.startDate | startDate }} -
                            {{ picker.endDate | endDate }}
                          </template>
                        </date-range-picker>
                      </div>
                    </div>
                    <table-loading v-show="transactionLoading" />
                    <div class="table-responsive table-custom mt-3">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>{{ $t("common.s_no") }}</th>
                            <th>{{ $t("common.payment_type") }}</th>
                            <th>{{ $t("common.paid_amount") }}</th>
                            <th>{{ $t("common.account") }}</th>
                            <th>{{ $t("common.payment_date") }}</th>
                            <th>{{ $t("common.status") }}</th>
                            <th
                              v-if="
                                $can('non-purchase-payment-view') ||
                                $can('non-purchase-payment-edit') ||
                                $can('non-purchase-payment-delete')
                              "
                              class="text-right no-print"
                              id="element-to-hide"
                              data-html2canvas-ignore="true"
                            >
                              {{ $t("common.action") }}
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-show="allTransactions && allTransactions.length"
                            v-for="(data, i) in allTransactions"
                            :key="i"
                          >
                            <td>
                              <span
                                v-if="
                                  transactionPagination &&
                                  transactionPagination.current_page > 1
                                "
                              >
                                {{
                                  transactionPagination.per_page *
                                    (transactionPagination.current_page - 1) +
                                  (i + 1)
                                }}
                              </span>
                              <span v-else>{{ i + 1 }}</span>
                            </td>
                            <td>
                              <span
                                v-if="data.type === 1"
                                class="badge bg-primary"
                                >{{ $t("payments.common.due_paid") }}</span
                              >
                              <span v-else class="badge bg-danger">{{
                                $t("payments.common.due_added")
                              }}</span>
                            </td>
                            <td>{{ data.amount | withCurrency }}</td>
                            <td>
                              <span v-if="data.account">{{
                                data.account.label
                              }}</span>
                            </td>
                            <td>
                              <span v-if="data.date">{{
                                data.date | moment("Do MMM, YYYY")
                              }}</span>
                            </td>
                            <td>
                              <span
                                v-if="data.status === 1"
                                class="badge bg-success"
                                >{{ $t("common.active") }}</span
                              >
                              <span v-else class="badge bg-danger">{{
                                $t("common.in_active")
                              }}</span>
                            </td>
                            <td
                              v-if="
                                $can('non-purchase-payment-view') ||
                                $can('non-purchase-payment-edit') ||
                                $can('non-purchase-payment-delete')
                              "
                              class="text-right no-print"
                              id="element-to-hide"
                              data-html2canvas-ignore="true"
                            >
                              <div class="btn-group">
                                <router-link
                                  v-if="$can('non-purchase-payment-edit')"
                                  v-tooltip="$t('common.edit')"
                                  :to="{
                                    name: 'nonPurchasePayments.edit',
                                    params: { slug: data.slug },
                                  }"
                                  class="btn btn-info btn-sm"
                                >
                                  <i class="fas fa-edit" />
                                </router-link>
                                <a
                                  v-if="$can('non-purchase-payment-delete')"
                                  v-tooltip="$t('common.delete')"
                                  href="#"
                                  class="btn btn-danger btn-sm"
                                  @click="deleteTransactionData(data.slug)"
                                >
                                  <i class="fas fa-trash" />
                                </a>
                              </div>
                            </td>
                          </tr>
                          <tr v-show="!loading && !allTransactions.length">
                            <td colspan="7">
                              <EmptyTable />
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- NEW PAGINATION -->
                  <div
                    v-if="
                      transactionPagination && transactionPagination.total > 0
                    "
                    class="dtable-footer no-print"
                    id="element-to-hide"
                    data-html2canvas-ignore="true"
                  >
                    <div class="form-group row display-per-page">
                      <label>{{ $t("per_page") }} </label>
                      <div>
                        <select
                          @change="updatePerPager('non-purchase-transactions')"
                          v-model="perPage"
                          class="form-control form-control-sm ml-1"
                        >
                          <!-- options component -->
                          <option
                            v-for="(option, i) in options"
                            :value="option.value"
                            :key="i"
                          >
                            {{ option.text }}
                          </option>
                        </select>
                      </div>
                    </div>
                    <!-- pagination-start -->
                    <pagination
                      v-if="
                        transactionPagination &&
                        transactionPagination.last_page > 1
                      "
                      :pagination="transactionPagination"
                      :offset="5"
                      class="justify-flex-end"
                      @paginate="transactionsPaginate"
                    />
                    <!-- pagination-end -->
                  </div>
                  <!-- NEW PAGINATION END -->
                </div>
              </div>

              <!--ledger-->
              <div class="tab-pane print-area" id="ledger">
                <table-loading v-show="loading" />
                <div class="table-responsive table-custom mt-3">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>{{ $t("common.s_no") }}</th>
                        <th>{{ $t("common.date") }}</th>
                        <th>{{ $t("common.particular") }}</th>
                        <th>{{ $t("common.credit") }}</th>
                        <th>{{ $t("common.debit") }}</th>
                        <th>{{ $t("common.discount") }}</th>
                        <th>{{ $t("common.balance") }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(data, i) in ledgerItems" :key="i">
                        <td>{{ i + 1 }}</td>
                        <td>
                          {{ data.original_date | moment("Do MMM, YYYY") }}
                        </td>
                        <td>
                          <router-link
                            v-if="
                              $can('purchase-view') &&
                              data.action_type == 'purchase'
                            "
                            :to="{
                              name: 'purchases.show',
                              params: { slug: data.slug },
                            }"
                          >
                            {{ data.particulars }}
                          </router-link>
                          <router-link
                            v-if="data.action_type == 'purchase-payment'"
                            :to="{
                              name: 'purchasePayments.show',
                              params: { slug: data.slug },
                            }"
                          >
                            {{ data.particulars }}
                          </router-link>
                          <router-link
                            v-if="
                              $can('purchase-return-view') &&
                              data.action_type == 'purchase-return'
                            "
                            :to="{
                              name: 'purchaseReturns.show',
                              params: { slug: data.slug },
                            }"
                          >
                            {{ data.particulars }}
                          </router-link>
                        </td>
                        <td>{{ data.credit | withCurrency }}</td>
                        <td>{{ data.debit | withCurrency }}</td>
                        <td>{{ data.discount | withCurrency }}</td>
                        <td>{{ data.balance | withCurrency }}</td>
                      </tr>
                      <tr v-if="ledgerItems[ledgerItems.length - 1]">
                        <td>{{ ledgerItems.length + 1 }}</td>
                        <td>{{ date | moment("Do MMM, YYYY") }}</td>
                        <td>{{ $t("payments.common.non_purchase_due") }}</td>
                        <td>{{ 0 | withCurrency }}</td>
                        <td>
                          {{ allData.nonPurchaseCurrentDue | withCurrency }}
                        </td>
                        <td>{{ 0 | withCurrency }}</td>
                        <td>
                          {{
                            (ledgerItems[ledgerItems.length - 1].balance +
                              allData.nonPurchaseCurrentDue)
                              | withCurrency
                          }}
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr v-if="ledgerItems[ledgerItems.length - 1]">
                        <td colspan="3">{{ $t("common.summery") }}</td>
                        <td>{{ ledgerTotalCredit | withCurrency }}</td>
                        <td>
                          {{
                            (ledgerTotalDebit + allData.nonPurchaseCurrentDue)
                              | withCurrency
                          }}
                        </td>
                        <td>{{ ledgerTotalDiscount | withCurrency }}</td>
                        <td>
                          {{
                            (ledgerItems[ledgerItems.length - 1].balance +
                              allData.nonPurchaseCurrentDue)
                              | withCurrency
                          }}
                          [{{ $t("common.total_due") }}]
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- use the modal component, pass in the prop -->
    <Modal v-if="showModal" @close="previewModal()">
      <h5 slot="header">{{ $t("common.modal_header") }}</h5>
      <div class="w-100" slot="body">
        <img :src="allData.image" class="img-fluid" loading="lazy" />
      </div>
    </Modal>
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import i18n from "~/plugins/i18n";
import { mapGetters } from "vuex";
import html2pdf from "html2pdf.js";
import DateRangePicker from "vue2-daterange-picker";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("suppliers.view.page_title") };
  },
  components: {
    DateRangePicker,
  },
  data: () => ({
    breadcrumbsCurrent: "suppliers.view.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "suppliers.view.breadcrumbs_first",
        url: "home",
      },
      {
        name: "suppliers.view.breadcrumbs_second",
        url: "suppliers.index",
      },
      {
        name: "suppliers.view.breadcrumbs_active",
        url: "",
      },
    ],
    url: null,
    query: "",
    allData: "",
    showModal: false,
    allReturns: "",
    returnsQuery: "",
    returnPagination: "",
    returnLoading: false,
    allPayments: "",
    paymentsQuery: "",
    paymentPagination: "",
    paymentsLoading: false,
    allTransactions: "",
    transactionsQuery: "",
    transactionPagination: "",
    transactionLoading: false,
    supplierPrefix: "",
    purchasePrefix: "",
    purchaseReturnPrefix: "",
    perPage: 10,
    options: [
      { value: "10", text: "10" },
      { value: "25", text: "25" },
      { value: "50", text: "50" },
      { value: "100", text: "100" },
    ],
    minDate: moment(new Date("01-01-2021")).format("YYYY-MM-DD"),
    maxDate: moment().add(1, "days").format("YYYY-MM-DD"),
    dateRange: {
      startDate: "",
      endDate: "",
    },
    locale: {
      direction: "ltr",
      format: "YYYY-MM-DD",
      separator: " - ",
      applyLabel: "Apply",
      cancelLabel: "Cancel",
      weekLabel: "W",
      customRangeLabel: "Custom Range",
      daysOfWeek: moment.weekdaysMin(),
      monthNames: moment.monthsShort(),
      firstDay: 1,
    },

    ledgerItems: [],
    ledgerTotalDiscount: 0,
    ledgerTotalDebit: 0,
    ledgerTotalCredit: 0,
    finalBalance: 0,
    headerShow: false,
    date: new Date().toISOString().slice(0, 10),
    activeTab: "purchases",
  }),
  filters: {
    startDate(val) {
      return val ? moment(val).format("YYYY-MM-DD") : i18n.t("common.from");
    },
    endDate(val) {
      return val ? moment(val).format("YYYY-MM-DD") : i18n.t("common.to");
    },
  },
  // Map Getters
  computed: {
    ...mapGetters("operations", ["items", "loading", "pagination", "appInfo"]),
  },
  watch: {
    // watch purchase search data
    query: function (newQ, oldQ) {
      if (newQ === "") {
        if (this.dateRange.startDate && this.dateRange.endDate) {
          this.searchPurchaseData();
        } else {
          this.getSupplierPurchases();
        }
      } else {
        this.searchPurchaseData();
      }
    },
    // watch return search data
    returnsQuery: function (newQ, oldQ) {
      if (newQ === "") {
        this.getSupplierReturns();
      } else {
        this.searchReturnData();
      }
    },
    // watch payment search data
    paymentsQuery: function (newQ, oldQ) {
      if (newQ === "") {
        this.getSupplierPayments();
      } else {
        this.searchPaymentData();
      }
    },
    // watch non purchase search data
    transactionsQuery: function (newQ, oldQ) {
      if (newQ === "") {
        this.getNonPurchaseTransactions();
      } else {
        this.searchNonPurchaseTransactionData();
      }
    },
  },
  created() {
    this.getSupplier();
    this.getSupplierPurchases();
    this.supplierPrefix = this.appInfo.supplierPrefix;
    this.purchasePrefix = this.appInfo.purchasePrefix;
    this.purchaseReturnPrefix = this.appInfo.purchaseReturnPrefix;
    Fire.$on("AfterDelete", () => {
      this.getSupplierPurchases();
      this.getSupplierReturns();
      this.getSupplierPayments();
      this.getNonPurchaseTransactions();
    });
  },
  methods: {
    switchTab(tabName) {
      switch (tabName) {
        case "purchases":
          this.searchPurchaseData();
          break;
        case "purchase-returns":
          this.searchReturnData();
          break;
        case "purchase-payments":
          this.searchPaymentData();
          break;
        case "non-purchase-transactions":
          this.searchNonPurchaseTransactionData();
          break;
      }
    },

    // filter data for selected date range
    async updateValues(tabName) {
      this.dateRange.startDate = moment(this.dateRange.startDate).format(
        "YYYY-MM-DD"
      );
      this.dateRange.endDate = moment(this.dateRange.endDate).format(
        "YYYY-MM-DD"
      );
      await this.switchTab(tabName);
    },

    // refresh table
    refreshTable(tabName) {
      this.query = "";
      this.dateRange.startDate = null;
      this.dateRange.endDate = null;
      setTimeout(
        function () {
          this.dateRange.startDate = "";
          this.dateRange.endDate = "";
          this.switchTab(tabName);
        }.bind(this),
        1000
      );
    },

    // get the supplier
    async getSupplier() {
      const { data } = await axios.get(
        window.location.origin + "/api/suppliers/" + this.$route.params.slug
      );
      this.allData = data.data;
    },

    // update per page count
    updatePerPager(tabName) {
      this.pagination.current_page = 1;
      this.returnPagination.hasOwnProperty("current_page")
        ? (this.returnPagination.current_page = 1)
        : "";
      this.paymentPagination.hasOwnProperty("current_page")
        ? (this.paymentPagination.current_page = 1)
        : "";
      this.transactionPagination.hasOwnProperty("current_page")
        ? (this.transactionPagination.current_page = 1)
        : "";

      this.switchTab(tabName);
    },

    // get the supplier purchases
    async getSupplierPurchases() {
      this.activeTab = "purchases";
      let currentPage = this.pagination ? this.pagination.current_page : 1;
      this.$store.state.operations.loading = true;
      await this.$store.dispatch("operations/fetchData", {
        path: "/api/purchases/supplier/" + this.$route.params.slug + "?page=",
        currentPage: currentPage + "&perPage=" + this.perPage,
      });
    },

    // search purchases
    async searchPurchaseData() {
      this.$store.state.operations.loading = true;
      let currentPage = this.pagination ? this.pagination.current_page : 1;
      await this.$store.dispatch("operations/searchData", {
        path: "/api/purchases/supplier/" + this.$route.params.slug + "/search",
        term: this.query,
        currentPage: currentPage + "&perPage=" + this.perPage,
        startDate: this.dateRange.startDate,
        endDate: this.dateRange.endDate,
      });
    },

    // Pagination
    async paginate() {
      this.query === ""
        ? this.getSupplierPurchases()
        : this.searchPurchaseData();
    },

    // Reset purchase pagination
    async resetPagination() {
      this.pagination.current_page = 1;
    },

    //Reload purchases after search
    async reload() {
      this.query = "";
    },

    // Get the supplier returns
    async getSupplierReturns() {
      this.activeTab = "purchase-returns";
      this.returnLoading = true;
      let currentPage = this.allReturns
        ? this.returnPagination.current_page
        : 1;
      const { data } = await axios.get(
        window.location.origin +
          "/api/purchase-returns/supplier/" +
          this.$route.params.slug +
          "?page=" +
          currentPage +
          "&perPage=" +
          this.perPage
      );
      this.allReturns = data.data;
      this.returnPagination = data.meta;
      this.returnLoading = false;
    },

    // search returns
    async searchReturnData() {
      this.returnLoading = true;
      let currentPage = this.allReturns
        ? this.returnPagination.current_page
        : 1;
      const { data } = await axios.get(
        window.location.origin +
          "/api/purchase-returns/supplier/" +
          this.$route.params.slug +
          "/search" +
          "?term=" +
          this.returnsQuery +
          "&page=" +
          currentPage +
          "&perPage=" +
          this.perPage +
          "&startDate=" +
          this.dateRange.startDate +
          "&endDate=" +
          this.dateRange.endDate
      );
      this.allReturns = data.data;
      this.returnPagination = data.meta;
      this.returnLoading = false;
    },

    // Returns pagination
    async returnsPaginate() {
      this.returnsQuery === ""
        ? this.getSupplierReturns()
        : this.searchReturnData();
    },

    // Reset return pagination
    async resetReturnPagination() {
      this.returnPagination.current_page = 1;
    },

    // Reload returns after search
    async returnsReload() {
      this.returnsQuery = "";
      await this.searchReturnData();
    },

    // Get the supplier payments
    async getSupplierPayments() {
      this.activeTab = "purchase-payments";
      this.paymentsLoading = true;
      let currentPage = this.allPayments
        ? this.paymentPagination.current_page
        : 1;
      const { data } = await axios.get(
        window.location.origin +
          "/api/payments/supplier/" +
          this.$route.params.slug +
          "?page=" +
          currentPage +
          "&perPage=" +
          this.perPage
      );
      this.allPayments = data.data;
      this.paymentPagination = data.meta;
      this.paymentsLoading = false;
    },

    // search payments
    async searchPaymentData() {
      this.paymentsLoading = true;
      let currentPage = this.allPayments
        ? this.paymentPagination.current_page
        : 1;
      const { data } = await axios.get(
        window.location.origin +
          "/api/payments/supplier/" +
          this.$route.params.slug +
          "/search" +
          "?term=" +
          this.paymentsQuery +
          "&page=" +
          currentPage +
          "&perPage=" +
          this.perPage +
          "&startDate=" +
          this.dateRange.startDate +
          "&endDate=" +
          this.dateRange.endDate
      );
      this.allPayments = data.data;
      this.paymentPagination = data.meta;
      this.paymentsLoading = false;
    },

    // Payments pagination
    async paymentsPaginate() {
      this.paymentsQuery === ""
        ? this.getSupplierPayments()
        : this.searchPaymentData();
    },

    // Reset payments pagination
    async resetPaymetnsPagination() {
      this.paymentPagination.current_page = 1;
    },

    // Reload payments after search
    async paymentsReload() {
      this.paymentsQuery = "";
      await this.searchPaymentData();
    },

    // Get the supplier non purchase transactions
    async getNonPurchaseTransactions() {
      this.activeTab = "non-purchase-transactions";
      this.transactionLoading = true;
      let currentPage = this.allTransactions
        ? this.transactionPagination.current_page
        : 1;
      const { data } = await axios.get(
        window.location.origin +
          "/api/non-purchases/supplier/" +
          this.$route.params.slug +
          "?page=" +
          currentPage +
          "&perPage=" +
          this.perPage
      );
      this.allTransactions = data.data;
      this.transactionPagination = data.meta;
      this.transactionLoading = false;
    },

    // search non purchase transactions
    async searchNonPurchaseTransactionData() {
      this.transactionLoading = true;
      let currentPage = this.allTransactions
        ? this.transactionPagination.current_page
        : 1;
      const { data } = await axios.get(
        window.location.origin +
          "/api/non-purchases/supplier/" +
          this.$route.params.slug +
          "/search" +
          "?term=" +
          this.transactionsQuery +
          "&page=" +
          currentPage +
          "&perPage=" +
          this.perPage +
          "&startDate=" +
          this.dateRange.startDate +
          "&endDate=" +
          this.dateRange.endDate
      );
      this.allTransactions = data.data;
      this.transactionPagination = data.meta;
      this.transactionLoading = false;
    },

    // Non purchase transactions pagination
    async transactionsPaginate() {
      this.query === ""
        ? this.getNonPurchaseTransactions()
        : this.searchNonPurchaseTransactionData();
    },

    // Reset non purchase transactions pagination
    async resetTransactionsPagination() {
      this.transactionPagination.current_page = 1;
    },

    // Reload on purchase transactions after search
    async transactionsReload() {
      this.transactionsQuery = "";
      await this.searchNonPurchaseTransactionData();
    },

    // display modal
    previewModal(image) {
      this.imagePath = image;
      if (this.showModal) {
        return (this.showModal = false);
      }
      return (this.showModal = true);
    },

    // get ledger
    async getLedger() {
      this.ledgerLoading = true;
      this.activeTab = "ledger";
      const { data } = await axios.get(
        window.location.origin +
          "/api/supplier/" +
          this.$route.params.slug +
          "/ledger"
      );
      this.ledgerItems = data.items;
      this.ledgerTotalDiscount = data.totalDiscount;
      this.ledgerTotalDebit = data.totalDebit;
      this.ledgerTotalCredit = data.totalCredit;
      this.finalBalance = data.finalBalance;
      this.ledgerLoading = false;
    },

    // generate pdf
    async generatePDF() {
      // Get the HTML content to be converted
      this.headerShow = true;
      const element = document.getElementById("content-to-pdf");
      setTimeout(async () => {
        // Options for PDF generation
        const options = {
          margin: 5,
          filename: this.activeTab + ".pdf",
          image: { type: "jpeg", quality: 0.98 },
          html2canvas: { scale: 2 },
          jsPDF: { unit: "mm", format: "a4", orientation: "landscape" },
        };

        // Generate PDF from HTML content
        html2pdf().from(element).set(options).save();
        this.headerShow = false;
      }, 2000);
    },

    // print table
    async print() {
      this.headerShow = true;
      await this.$htmlToPaper(this.activeTab);
      setTimeout(async () => {
        this.headerShow = false;
      }, 2000);
    },

    // delete purchase data
    async deletePurchaseData(slug) {
      Swal.fire({
        title: this.$t("common.delete_title"),
        text: this.$t("purchases.list.index.delete_warning"),
        type: "warning",
        showCancelButton: true,
        confirmButtonText: this.$t("common.delete_confirm_text"),
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.$store
            .dispatch("operations/deleteData", {
              path: "/api/purchases/",
              slug: slug,
            })
            .then((response) => {
              if (response === true) {
                Swal.fire(
                  this.$t("common.deleted"),
                  this.$t("common.delete_success"),
                  "success"
                );
                Fire.$emit("AfterDelete");
              } else {
                Swal.fire(
                  this.$t("common.failed"),
                  this.$t("common.delete_failed"),
                  "warning"
                );
              }
            });
        }
      });
    },

    // delete purchase return data
    async deletePurchaseReturnData(slug) {
      Swal.fire({
        title: this.$t("common.delete_title"),
        text: this.$t("purchases.returns.index.delete_warning"),
        type: "warning",
        showCancelButton: true,
        confirmButtonText: this.$t("common.delete_confirm_text"),
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.$store
            .dispatch("operations/deleteData", {
              path: "/api/purchase-returns/",
              slug: slug,
            })
            .then((response) => {
              if (response === true) {
                Swal.fire(
                  this.$t("common.deleted"),
                  this.$t("common.delete_success"),
                  "success"
                );
                Fire.$emit("AfterDelete");
              } else {
                Swal.fire(
                  this.$t("common.failed"),
                  this.$t("common.delete_failed"),
                  "warning"
                );
              }
            });
        }
      });
    },

    // delete purchase payment data
    async deletePaymentData(slug) {
      Swal.fire({
        title: this.$t("common.delete_title"),
        text: this.$t("payments.suppliers.purchase.index.delete_warning"),
        type: "warning",
        showCancelButton: true,
        confirmButtonText: this.$t("common.delete_confirm_text"),
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.$store
            .dispatch("operations/deleteData", {
              path: "/api/payments/purchase/",
              slug: slug,
            })
            .then((response) => {
              if (response === true) {
                Swal.fire(
                  this.$t("common.deleted"),
                  this.$t("common.delete_success"),
                  "success"
                );
                Fire.$emit("AfterDelete");
              } else {
                Swal.fire(
                  this.$t("common.failed"),
                  this.$t("payments.suppliers.purchase.index.delete_failed"),
                  "warning"
                );
              }
            });
        }
      });
    },

    // delete non purchase payment data
    async deleteTransactionData(slug) {
      Swal.fire({
        title: this.$t("common.delete_title"),
        text: this.$t("payments.suppliers.non_purchase.index.delete_warning"),
        type: "warning",
        showCancelButton: true,
        confirmButtonText: this.$t("common.delete_confirm_text"),
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.$store
            .dispatch("operations/deleteData", {
              path: "/api/payments/non-purchase/",
              slug: slug,
            })
            .then((response) => {
              if (response === true) {
                Swal.fire(
                  this.$t("common.deleted"),
                  this.$t("common.delete_success"),
                  "success"
                );
                Fire.$emit("AfterDelete");
              } else {
                Swal.fire(
                  this.$t("common.failed"),
                  this.$t(
                    "payments.suppliers.non_purchase.index.delete_failed"
                  ),
                  "warning"
                );
              }
            });
        }
      });
    },
  },
};
</script>

<style scoped>
tfoot {
  font-weight: 700;
}
.nav-pills .nav-item {
  background: #ddd;
  margin: 2px;
  border-radius: 0.25rem;
}
</style>
