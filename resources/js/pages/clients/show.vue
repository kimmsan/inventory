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
                <strong>{{ $t("common.client_id") }}</strong>
                <span class="float-right">{{
                  allData.clientID | withPrefix(clientPrefix)
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
            <span v-else class="btn-block btn bg-danger">{{
              $t("common.in_active")
            }}</span>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
      <!-- /.col -->
      <div class="col-md-12 col-lg-9">
        <div class="row">
          <div class="col-lg-6 col-md-4 col-12">
            <div class="card bg-info">
              <div class="card-content">
                <div class="card-body pb-1">
                  <div class="row">
                    <div class="col-6">
                      <h6 class="text-white">
                        {{ $t("common.invoice_total") }}
                      </h6>
                      <h6 class="text-white">
                        {{ $t("payments.common.non_invoice_total") }}
                      </h6>
                      <hr />
                      <h4 class="text-white mb-1">{{ $t("common.total") }}</h4>
                    </div>
                    <div class="col-6 text-right">
                      <h6 class="text-white">
                        {{ allData.clientInvoiceTotal | withCurrency }}
                      </h6>
                      <h6 class="text-white">
                        {{ allData.nonInvoiceDue | withCurrency }}
                      </h6>
                      <hr />
                      <h4 class="text-white mb-1">
                        {{
                          (allData.clientInvoiceTotal + allData.nonInvoiceDue)
                            | withCurrency
                        }}
                      </h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-4 col-12">
            <div class="card bg-danger">
              <div class="card-content">
                <div class="card-body pb-1">
                  <div class="row">
                    <div class="col-6">
                      <h6 class="text-white">{{ $t("common.invoice_due") }}</h6>
                      <h6 class="text-white">
                        {{ $t("common.non_invoice_due") }}
                      </h6>
                      <hr />
                      <h4 class="text-white mb-1">
                        {{ $t("common.total_due") }}
                      </h4>
                    </div>
                    <div class="col-6 text-right">
                      <h6 class="text-white">
                        {{ allData.clientDue | withCurrency }}
                      </h6>
                      <h6 class="text-white">
                        {{ allData.nonInvoiceCurrentDue | withCurrency }}
                      </h6>
                      <hr />
                      <h4 class="text-white mb-1">
                        {{
                          (allData.clientDue + allData.nonInvoiceCurrentDue)
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
            $can('invoice-list') ||
            $can('invoice-return-list') ||
            $can('invoice-payment-list') ||
            $can('non-invoice-payment-list')
          "
          class="card"
        >
          <div class="card-header p-2">
            <div class="row">
              <div class="col-md-10">
                <ul class="nav nav-pills">
                  <li v-if="$can('invoice-list')" class="nav-item">
                    <a
                      class="nav-link active"
                      href="#invoices"
                      data-toggle="tab"
                      @click="activeTab = 'invoices'"
                    >
                      <i class="fas fa-file-invoice"></i>
                      {{ $t("sales.invoices.index.page_title") }}
                      <span v-if="pagination" class="badge badge-dark">{{
                        pagination.total
                      }}</span>
                    </a>
                  </li>
                  <li v-if="$can('invoice-return-list')" class="nav-item">
                    <a
                      class="nav-link"
                      href="#invoice-returns"
                      data-toggle="tab"
                      @click="getInvoiceReturns"
                    >
                      <i class="fas fa-undo-alt"></i>
                      {{ $t("sales.returns.index.page_title") }}
                      <span
                        v-if="invoiceReturnPagination"
                        class="badge badge-dark"
                        >{{ invoiceReturnPagination.total }}</span
                      >
                    </a>
                  </li>
                  <li v-if="$can('invoice-payment-list')" class="nav-item">
                    <a
                      class="nav-link"
                      href="#invoice-payments"
                      data-toggle="tab"
                      @click="getInvoicePayments"
                    >
                      <i class="fas fa-receipt"></i>
                      {{ $t("payments.clients.invoice.index.page_title") }}
                      <span v-if="paymentPagination" class="badge badge-dark">{{
                        paymentPagination.total
                      }}</span>
                    </a>
                  </li>
                  <li v-if="$can('non-invoice-payment-list')" class="nav-item">
                    <a
                      class="nav-link"
                      href="#non-invoice-transactions"
                      data-toggle="tab"
                      @click="nonInvoiceTransactions"
                    >
                      <i class="fas fa-money-bill"></i>
                      {{ $t("clients.common.non_invoice_transactions") }}
                      <span
                        v-if="nonInvoicePagination"
                        class="badge badge-dark"
                        >{{ nonInvoicePagination.total }}</span
                      >
                    </a>
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
                    :to="{ name: 'clients.index' }"
                    class="btn btn-dark float-right"
                    title="Back"
                    v-tooltip="$t('common.back')"
                  >
                    <i class="fas fa-long-arrow-alt-left" />
                  </router-link>
                </div>
              </div>
            </div>
          </div>
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
              <!-- Invoices -->
              <div class="tab-pane active" id="invoices">
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
                  <div class="col-12 col-md-3 text-right pull-right">
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
                      @update="updateValues('invoice')"
                      :linkedCalendars="true"
                      class="c-w-100"
                    >
                      <template v-slot:input="picker" style="min-width: 350px">
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
                        <th>{{ $t("common.invoice_no") }}</th>
                        <th>{{ $t("common.invoice_date") }}</th>
                        <th>{{ $t("common.net_total") }}</th>
                        <th>{{ $t("common.total_paid") }}</th>
                        <th>{{ $t("common.total_due") }}</th>
                        <th>{{ $t("common.status") }}</th>
                        <th
                          v-if="
                            $can('invoice-view') ||
                            $can('invoice-edit') ||
                            $can('invoice-delete')
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
                            v-if="$can('invoice-view')"
                            :to="{
                              name: 'invoices.show',
                              params: { slug: data.slug },
                            }"
                          >
                            {{ data.invoiceNo | withPrefix(invoicePrefix) }}
                          </router-link>
                          <span v-else>{{
                            data.invoiceNo | withPrefix(invoicePrefix)
                          }}</span>
                        </td>
                        <td>
                          <span v-if="data.invoiceDate">{{
                            data.invoiceDate | moment("Do MMM, YYYY")
                          }}</span>
                        </td>

                        <td>{{ data.invoiceTotal | withCurrency }}</td>
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
                            $can('invoice-view') ||
                            $can('invoice-edit') ||
                            $can('invoice-delete')
                          "
                          class="text-right no-print"
                          id="element-to-hide"
                          data-html2canvas-ignore="true"
                        >
                          <div class="btn-group">
                            <router-link
                              v-if="$can('invoice-view')"
                              v-tooltip="$t('common.view')"
                              :to="{
                                name: 'invoices.show',
                                params: { slug: data.slug },
                              }"
                              class="btn btn-primary btn-sm"
                            >
                              <i class="fas fa-eye" />
                            </router-link>
                            <router-link
                              v-if="$can('invoice-edit')"
                              v-tooltip="$t('common.edit')"
                              :to="{
                                name: 'invoices.edit',
                                params: { slug: data.slug },
                              }"
                              class="btn btn-info btn-sm"
                            >
                              <i class="fas fa-edit" />
                            </router-link>
                            <a
                              v-if="$can('invoice-delete')"
                              v-tooltip="$t('common.delete')"
                              href="#"
                              class="btn btn-danger btn-sm"
                              @click="deleteInvoiceData(data.slug)"
                            >
                              <i class="fas fa-trash" />
                            </a>
                          </div>
                        </td>
                      </tr>
                      <tr v-show="!loading && items && !items.length">
                        <td colspan="8">
                          <EmptyTable />
                        </td>
                      </tr>
                    </tbody>
                  </table>
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
                        @change="updatePerPager('invoice')"
                        v-model="perPage"
                        class="form-control form-control-sm ml-1"
                      >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
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
              <!-- Invoices Returns -->
              <div class="tab-pane" id="invoice-returns">
                <div
                  class="row no-print"
                  id="element-to-hide"
                  data-html2canvas-ignore="true"
                >
                  <div class="col-12 col-md-9 mb-2">
                    <search
                      v-model="invoiceReturnQuery"
                      @reset-pagination="resetReturnPagination"
                      @reload="returnReload"
                    />
                  </div>
                  <div class="col-12 col-md-3 text-right pull-right">
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
                      @update="updateValues('invoice-returns')"
                      :linkedCalendars="true"
                      class="c-w-100"
                    >
                      <template v-slot:input="picker" style="min-width: 350px">
                        {{ picker.startDate | startDate }} -
                        {{ picker.endDate | endDate }}
                      </template>
                    </date-range-picker>
                  </div>
                </div>
                <table-loading v-show="invoiceReturnLoading" />
                <div class="table-responsive table-custom mt-3">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>{{ $t("common.s_no") }}</th>
                        <th>{{ $t("common.return_no") }}</th>
                        <th>{{ $t("common.invoice_no") }}</th>
                        <th>{{ $t("common.return_reason") }}</th>
                        <th>{{ $t("common.return_cost") }}</th>
                        <th>{{ $t("common.date") }}</th>
                        <th>{{ $t("common.status") }}</th>
                        <th
                          v-if="
                            $can('invoice-return-edit') ||
                            $can('invoice-return-view') ||
                            $can('invoice-return-delete')
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
                        v-show="allReturns.length"
                        v-for="(data, i) in allReturns"
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
                            v-if="$can('invoice-return-view')"
                            :to="{
                              name: 'invoiceReturns.show',
                              params: { slug: data.invoiceSlug },
                            }"
                          >
                            {{
                              data.returnNo | withPrefix(invoiceReturnPrefix)
                            }}
                          </router-link>
                          <span v-else>{{
                            data.returnNo | withPrefix(invoiceReturnPrefix)
                          }}</span>
                        </td>
                        <td>
                          {{ data.invoiceNo | withPrefix(invoicePrefix) }}
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
                            $can('invoice-return-edit') ||
                            $can('invoice-return-view') ||
                            $can('invoice-return-delete')
                          "
                          class="text-right no-print"
                          id="element-to-hide"
                          data-html2canvas-ignore="true"
                        >
                          <div class="btn-group">
                            <router-link
                              v-if="$can('invoice-return-view')"
                              v-tooltip="$t('common.view')"
                              :to="{
                                name: 'invoiceReturns.show',
                                params: { slug: data.slug },
                              }"
                              class="btn btn-primary btn-sm"
                            >
                              <i class="fas fa-eye" />
                            </router-link>
                            <router-link
                              v-if="$can('invoice-return-edit')"
                              v-tooltip="$t('common.edit')"
                              :to="{
                                name: 'invoiceReturns.edit',
                                params: { slug: data.slug },
                              }"
                              class="btn btn-info btn-sm"
                            >
                              <i class="fas fa-edit" />
                            </router-link>
                            <a
                              v-if="$can('invoice-return-delete')"
                              v-tooltip="$t('common.delete')"
                              href="#"
                              class="btn btn-danger btn-sm"
                              @click="deleteInvoiceReturnData(data.slug)"
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
                <!-- NEW PAGINATION -->
                <div
                  v-if="
                    invoiceReturnPagination && invoiceReturnPagination.total > 0
                  "
                  class="dtable-footer no-print"
                  id="element-to-hide"
                  data-html2canvas-ignore="true"
                >
                  <div class="form-group row display-per-page">
                    <label>{{ $t("per_page") }} </label>
                    <div>
                      <select
                        @change="updatePerPager('invoice-returns')"
                        v-model="perPage"
                        class="form-control form-control-sm ml-1"
                      >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                      </select>
                    </div>
                  </div>
                  <!-- pagination-start -->
                  <pagination
                    v-if="
                      invoiceReturnPagination &&
                      invoiceReturnPagination.last_page > 1
                    "
                    :pagination="
                      allReturns ? invoiceReturnPagination : { current_page: 1 }
                    "
                    :offset="5"
                    class="justify-flex-end"
                    @paginate="invoiceReturnPaginate"
                  />
                  <!-- pagination-end -->
                </div>
                <!-- NEW PAGINATION END -->
              </div>
              <!-- Invoices Payments -->
              <div class="tab-pane" id="invoice-payments">
                <div
                  class="row no-print"
                  id="element-to-hide"
                  data-html2canvas-ignore="true"
                >
                  <div class="col-12 col-md-9 mb-2">
                    <search
                      v-model="paymentsQuery"
                      @reset-pagination="resetPaymentsPagination"
                      @reload="paymentsReload"
                    />
                  </div>
                  <div class="col-12 col-md-3 text-right pull-right">
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
                      @update="updateValues('invoice-payments')"
                      :linkedCalendars="true"
                      class="c-w-100"
                    >
                      <template v-slot:input="picker" style="min-width: 350px">
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
                        <th>{{ $t("common.invoice_no") }}</th>
                        <th>{{ $t("common.total") }}</th>
                        <th>{{ $t("common.paid_amount") }}</th>
                        <th>{{ $t("common.account") }}</th>
                        <th>{{ $t("common.payment_date") }}</th>
                        <th>{{ $t("common.status") }}</th>
                        <th
                          v-if="
                            $can('invoice-payment-edit') ||
                            $can('invoice-payment-view') ||
                            $can('invoice-payment-delete')
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
                        v-show="allPayments.length"
                        v-for="(data, i) in allPayments"
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
                        <td v-if="data.invoice && invoicePrefix">
                          <router-link
                            v-if="$can('invoice-view')"
                            :to="{
                              name: 'invoices.show',
                              params: { slug: data.invoice.slug },
                            }"
                          >
                            {{
                              data.invoice.invoiceNo | withPrefix(invoicePrefix)
                            }}
                          </router-link>
                          <span v-else>{{
                            data.invoice.invoiceNo | withPrefix(invoicePrefix)
                          }}</span>
                        </td>
                        <td v-if="data.invoice">
                          {{ data.invoice.invoiceTotal | withCurrency }}
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
                            $can('invoice-payment-edit') ||
                            $can('invoice-payment-view') ||
                            $can('invoice-payment-delete')
                          "
                          class="text-right no-print"
                          id="element-to-hide"
                          data-html2canvas-ignore="true"
                        >
                          <div class="btn-group">
                            <router-link
                              v-if="$can('invoice-payment-view')"
                              v-tooltip="$t('common.view')"
                              :to="{
                                name: 'invoicePayments.show',
                                params: { slug: data.slug },
                              }"
                              class="btn btn-primary btn-sm"
                            >
                              <i class="fas fa-eye" />
                            </router-link>
                            <router-link
                              v-if="$can('invoice-payment-edit')"
                              v-tooltip="$t('common.edit')"
                              :to="{
                                name: 'invoicePayments.edit',
                                params: { slug: data.slug },
                              }"
                              class="btn btn-info btn-sm"
                            >
                              <i class="fas fa-edit" />
                            </router-link>
                            <a
                              v-if="$can('invoice-payment-delete')"
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
                        <td colspan="9">
                          <EmptyTable />
                        </td>
                      </tr>
                    </tbody>
                  </table>
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
                        @change="updatePerPager('invoice-payments')"
                        v-model="perPage"
                        class="form-control form-control-sm ml-1"
                      >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                      </select>
                    </div>
                  </div>
                  <!-- pagination-start -->
                  <pagination
                    v-if="paymentPagination && paymentPagination.last_page > 1"
                    :pagination="paymentPagination"
                    :offset="5"
                    class="justify-flex-end"
                    @paginate="paymentsPaginate"
                  />
                  <!-- pagination-end -->
                </div>
                <!-- NEW PAGINATION END -->
              </div>
              <!-- Invoices Transactions -->
              <div class="tab-pane" id="non-invoice-transactions">
                <div
                  class="row no-print"
                  id="element-to-hide"
                  data-html2canvas-ignore="true"
                >
                  <div class="col-12 col-md-9 mb-2">
                    <search
                      v-model="nonInvoiceQuery"
                      @reset-pagination="resetNonInvoiceTransPagination"
                      @reload="nonInvoiceTransReload"
                    />
                  </div>
                  <div class="col-12 col-md-3 text-right pull-right">
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
                      @update="updateValues('non-invoice-transactions')"
                      :linkedCalendars="true"
                      class="c-w-100"
                    >
                      <template v-slot:input="picker" style="min-width: 350px">
                        {{ picker.startDate | startDate }} -
                        {{ picker.endDate | endDate }}
                      </template>
                    </date-range-picker>
                  </div>
                </div>
                <table-loading v-show="nonInvoiceTransLoading" />
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
                            $can('non-invoice-payment-edit') ||
                            $can('non-invoice-payment-view') ||
                            $can('non-invoice-payment-delete')
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
                        v-show="allNonInvoiceTrans.length"
                        v-for="(data, i) in allNonInvoiceTrans"
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
                            $can('non-invoice-payment-edit') ||
                            $can('non-invoice-payment-view') ||
                            $can('non-invoice-payment-delete')
                          "
                          class="text-right no-print"
                          id="element-to-hide"
                          data-html2canvas-ignore="true"
                        >
                          <div class="btn-group">
                            <router-link
                              v-if="$can('non-invoice-payment-edit')"
                              v-tooltip="$t('common.edit')"
                              :to="{
                                name: 'nonInvoicePayments.edit',
                                params: { slug: data.slug },
                              }"
                              class="btn btn-info btn-sm"
                            >
                              <i class="fas fa-edit" />
                            </router-link>
                            <a
                              v-if="$can('non-invoice-payment-delete')"
                              v-tooltip="$t('common.delete')"
                              href="#"
                              class="btn btn-danger btn-sm"
                              @click="deleteNonInvoicePayment(data.slug)"
                            >
                              <i class="fas fa-trash" />
                            </a>
                          </div>
                        </td>
                      </tr>
                      <tr v-show="!loading && !allNonInvoiceTrans.length">
                        <td colspan="7">
                          <EmptyTable />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- NEW PAGINATION -->
                <div
                  v-if="nonInvoicePagination && nonInvoicePagination.total > 0"
                  class="dtable-footer"
                  id="element-to-hide"
                  data-html2canvas-ignore="true"
                >
                  <div class="form-group row display-per-page">
                    <label>{{ $t("per_page") }} </label>
                    <div>
                      <select
                        @change="updatePerPager('non-invoice-transactions')"
                        v-model="perPage"
                        class="form-control form-control-sm ml-1"
                      >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                      </select>
                    </div>
                  </div>
                  <!-- pagination-start -->
                  <pagination
                    v-if="
                      nonInvoicePagination && nonInvoicePagination.last_page > 1
                    "
                    :pagination="nonInvoicePagination"
                    :offset="5"
                    class="justify-flex-end"
                    @paginate="nonInvoiceTransPaginate"
                  />
                  <!-- pagination-end -->
                </div>
                <!-- NEW PAGINATION END -->
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
                              $can('invoice-view') &&
                              data.action_type == 'invoice'
                            "
                            :to="{
                              name: 'invoices.show',
                              params: { slug: data.slug },
                            }"
                          >
                            {{ data.particulars }}
                          </router-link>
                          <router-link
                            v-if="data.action_type == 'invoice-payment'"
                            :to="{
                              name: 'invoicePayments.show',
                              params: { slug: data.slug },
                            }"
                          >
                            {{ data.particulars }}
                          </router-link>
                          <router-link
                            v-if="
                              $can('invoice-return-view') &&
                              data.action_type == 'invoice-return'
                            "
                            :to="{
                              name: 'invoiceReturns.show',
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
                        <td>{{ $t("common.non_invoice_due") }}</td>
                        <td>{{ 0 | withCurrency }}</td>
                        <td>
                          {{ allData.nonInvoiceCurrentDue | withCurrency }}
                        </td>
                        <td>{{ 0 | withCurrency }}</td>
                        <td>
                          {{
                            (ledgerItems[ledgerItems.length - 1].balance +
                              allData.nonInvoiceCurrentDue)
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
                            (ledgerTotalDebit + allData.nonInvoiceCurrentDue)
                              | withCurrency
                          }}
                        </td>
                        <td>{{ ledgerTotalDiscount | withCurrency }}</td>
                        <td>
                          {{
                            (ledgerItems[ledgerItems.length - 1].balance +
                              allData.nonInvoiceCurrentDue)
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
    return { title: this.$t("clients.view.page_title") };
  },
  components: {
    DateRangePicker,
  },
  data: () => ({
    breadcrumbsCurrent: "clients.view.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "clients.view.breadcrumbs_first",
        url: "home",
      },
      {
        name: "clients.view.breadcrumbs_second",
        url: "clients.index",
      },
      {
        name: "clients.view.breadcrumbs_active",
        url: "",
      },
    ],
    paymentsLoading: false,
    invoiceReturnLoading: false,
    creditsLoading: false,
    debitsLoading: false,
    nonInvoiceTransLoading: false,
    url: null,
    showModal: false,
    allData: "",
    allPayments: "",
    allReturns: "",
    allNonInvoiceTrans: "",
    paymentPagination: "",
    invoiceReturnPagination: "",
    nonInvoicePagination: "",
    query: "",
    invoiceReturnQuery: "",
    paymentsQuery: "",
    nonInvoiceQuery: "",
    clientPrefix: "",
    invoicePrefix: "",
    invoiceReturnPrefix: "",
    perPage: 10,
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
    activeTab: "invoices",
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
    // watch invoice search data
    query: function (newQ, oldQ) {
      if (newQ === "") {
        if (this.dateRange.startDate && this.dateRange.endDate) {
          this.searchInvoicesData();
        } else {
          this.getInvoices();
        }
      } else {
        this.searchInvoicesData();
      }
    },
    // watch invoice return search data
    invoiceReturnQuery: function (newQ, oldQ) {
      if (newQ === "") {
        if (this.dateRange.startDate && this.dateRange.endDate) {
          this.searchReturnData();
        } else {
          this.getInvoiceReturns();
        }
      } else {
        this.searchReturnData();
      }
    },
    // watch payment search data
    paymentsQuery: function (newQ, oldQ) {
      if (newQ === "") {
        if (this.dateRange.startDate && this.dateRange.endDate) {
          this.searchPaymentData();
        } else {
          this.getInvoicePayments();
        }
      } else {
        this.searchPaymentData();
      }
    },

    // watch non invoice transaction search data
    nonInvoiceQuery: function (newQ, oldQ) {
      if (newQ === "") {
        if (this.dateRange.startDate && this.dateRange.endDate) {
          this.searchNonInvoiceTransactions();
        } else {
          this.nonInvoiceTransactions();
        }
      } else {
        this.searchNonInvoiceTransactions();
      }
    },
  },
  created() {
    this.getClient();
    this.getInvoices();
    this.clientPrefix = this.appInfo.clientPrefix;
    this.invoicePrefix = this.appInfo.invoicePrefix;
    this.invoiceReturnPrefix = this.appInfo.invoiceReturnPrefix;

    Fire.$on("AfterDelete", () => {
      this.getInvoices();
      this.getInvoiceReturns();
      this.getInvoicePayments();
      this.nonInvoiceTransactions();
    });
  },
  methods: {
    switchTab(tabName) {
      switch (tabName) {
        case "invoice":
          this.searchInvoicesData();
          break;
        case "invoice-returns":
          this.searchReturnData();
          break;
        case "invoice-payments":
          this.searchPaymentData();
          break;
        case "non-invoice-transactions":
          this.searchNonInvoiceTransactions();
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

    // get the client
    async getClient() {
      const { data } = await axios.get(
        window.location.origin + "/api/clients/" + this.$route.params.slug
      );
      this.allData = data.data;
    },

    // update per page count
    updatePerPager(tabName) {
      this.pagination.current_page = 1;
      this.invoiceReturnPagination.hasOwnProperty("current_page")
        ? (this.invoiceReturnPagination.current_page = 1)
        : "";
      this.paymentPagination.hasOwnProperty("current_page")
        ? (this.paymentPagination.current_page = 1)
        : "";
      this.nonInvoicePagination.hasOwnProperty("current_page")
        ? (this.nonInvoicePagination.current_page = 1)
        : "";

      this.switchTab(tabName);
    },

    // get the client invoices
    async getInvoices() {
      this.activeTab = "invoices";
      this.$store.state.operations.loading = true;
      let currentPage = this.pagination ? this.pagination.current_page : 1;
      await this.$store.dispatch("operations/fetchData", {
        path: "/api/client/" + this.$route.params.slug + "/all-invoices/?page=",
        currentPage: currentPage + "&perPage=" + this.perPage,
      });
    },

    // search invoices
    async searchInvoicesData() {
      this.$store.state.operations.loading = true;
      let currentPage = this.pagination ? this.pagination.current_page : 1;
      await this.$store.dispatch("operations/searchData", {
        path: "/api/client/" + this.$route.params.slug + "/all-invoices/search",
        term: this.query,
        currentPage: currentPage + "&perPage=" + this.perPage,
        startDate: this.dateRange.startDate,
        endDate: this.dateRange.endDate,
      });
    },

    // pagination
    async paginate() {
      this.query === "" ? this.getInvoices() : this.searchInvoicesData();
    },

    // reset purchase pagination
    async resetPagination() {
      this.pagination.current_page = 1;
    },

    // reload purchases after search
    async reload() {
      this.query = "";
      await this.searchInvoicesData();
    },

    // get client invoice returns
    async getInvoiceReturns() {
      this.activeTab = "invoice-returns";
      this.invoiceReturnLoading = true;
      let currentPage = this.allReturns
        ? this.invoiceReturnPagination.current_page
        : 1;
      const { data } = await axios.get(
        window.location.origin +
          "/api/client/" +
          this.$route.params.slug +
          "/invoice-returns?page=" +
          currentPage +
          "&perPage=" +
          this.perPage
      );
      this.allReturns = data.data;
      this.invoiceReturnPagination = data.meta;
      this.invoiceReturnLoading = false;
    },

    // search invoice returns
    async searchReturnData() {
      this.invoiceReturnLoading = true;
      let currentPage = this.allReturns
        ? this.invoiceReturnPagination.current_page
        : 1;
      const { data } = await axios.get(
        window.location.origin +
          "/api/client/" +
          this.$route.params.slug +
          "/invoice-returns/search" +
          "?term=" +
          this.invoiceReturnQuery +
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
      this.invoiceReturnPagination = data.meta;
      this.invoiceReturnLoading = false;
    },

    // invoice return pagination
    async invoiceReturnPaginate() {
      this.query === "" ? this.getInvoiceReturns() : this.searchReturnData();
    },

    // reset invoice return pagination
    async resetReturnPagination() {
      this.invoiceReturnPagination.current_page = 1;
    },

    // Reload purchases after search
    async returnReload() {
      this.invoiceReturnQuery = "";
      await this.searchReturnData();
    },

    // Get the invoice payments
    async getInvoicePayments() {
      this.activeTab = "invoice-payments";
      this.paymentsLoading = true;
      let currentPage = this.allPayments
        ? this.paymentPagination.current_page
        : 1;
      const { data } = await axios.get(
        window.location.origin +
          "/api/client/" +
          this.$route.params.slug +
          "/invoice-payments?page=" +
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
          "/api/client/" +
          this.$route.params.slug +
          "/invoice-payments/search" +
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
      this.query === this.getInvoicePayments() ? this.searchPaymentData() : "";
    },

    // Reset payments pagination
    async resetPaymentsPagination() {
      this.paymentPagination.current_page = 1;
    },

    // Reload payments after search
    async paymentsReload() {
      this.paymentsQuery = "";
      await this.searchPaymentData();
    },

    // Get the non invoice transactions
    async nonInvoiceTransactions() {
      this.activeTab = "non-invoice-transactions";
      this.nonInvoiceTransLoading = true;
      let currentPage = this.allNonInvoiceTrans
        ? this.nonInvoicePagination.current_page
        : 1;
      const { data } = await axios.get(
        window.location.origin +
          "/api/client/" +
          this.$route.params.slug +
          "/non-invoice-payments?page=" +
          currentPage +
          "&perPage=" +
          this.perPage
      );
      this.allNonInvoiceTrans = data.data;
      this.nonInvoicePagination = data.meta;
      this.nonInvoiceTransLoading = false;
    },

    // search non invoice transactions
    async searchNonInvoiceTransactions() {
      this.nonInvoiceTransLoading = true;
      let currentPage = this.allNonInvoiceTrans
        ? this.nonInvoicePagination.current_page
        : 1;
      const { data } = await axios.get(
        window.location.origin +
          "/api/client/" +
          this.$route.params.slug +
          "/non-invoice-payments/search" +
          "?term=" +
          this.nonInvoiceQuery +
          "&page=" +
          currentPage +
          "&perPage=" +
          this.perPage +
          "&startDate=" +
          this.dateRange.startDate +
          "&endDate=" +
          this.dateRange.endDate
      );
      this.allNonInvoiceTrans = data.data;
      this.nonInvoicePagination = data.meta;
      this.nonInvoiceTransLoading = false;
    },

    // non invoice transactions pagination
    async nonInvoiceTransPaginate() {
      this.query === this.nonInvoiceTransactions()
        ? this.searchNonInvoiceTransactions()
        : "";
    },

    // Reset non invoice transactions pagination
    async resetNonInvoiceTransPagination() {
      this.nonInvoicePagination.current_page = 1;
    },

    // Reload non invoice transactions after search
    async nonInvoiceTransReload() {
      this.nonInvoiceQuery = "";
      await this.searchNonInvoiceTransactions();
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
          "/api/client/" +
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

    // delete invoice data
    async deleteInvoiceData(slug) {
      Swal.fire({
        title: this.$t("common.delete_title"),
        text: this.$t("sales.invoices.index.delete_warning"),
        type: "warning",
        showCancelButton: true,
        confirmButtonText: this.$t("common.delete_confirm_text"),
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.$store
            .dispatch("operations/deleteData", {
              path: "/api/invoices/",
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
                  this.$t("sales.invoices.index.delete_failed"),
                  "warning"
                );
              }
            });
        }
      });
    },

    // delete invoice return data
    async deleteInvoiceReturnData(slug) {
      Swal.fire({
        title: this.$t("common.delete_title"),
        text: this.$t("sales.returns.index.delete_warning"),
        type: "warning",
        showCancelButton: true,
        confirmButtonText: this.$t("common.delete_confirm_text"),
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.$store
            .dispatch("operations/deleteData", {
              path: "/api/invoice-returns/",
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
                  this.$t("sales.returns.index.delete_failed"),
                  "warning"
                );
              }
            });
        }
      });
    },

    // delete invoice payment data
    async deletePaymentData(slug) {
      Swal.fire({
        title: this.$t("common.delete_title"),
        text: this.$t("payments.clients.invoice.index.delete_warning"),
        type: "warning",
        showCancelButton: true,
        confirmButtonText: this.$t("common.delete_confirm_text"),
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.$store
            .dispatch("operations/deleteData", {
              path: "/api/payments/invoice/",
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
                  this.$t("payments.clients.invoice.index.delete_failed"),
                  "warning"
                );
              }
            });
        }
      });
    },

    // delete non invoice payment data
    async deleteNonInvoicePayment(slug) {
      Swal.fire({
        title: this.$t("common.delete_title"),
        text: this.$t("payments.clients.non_invoice.index.delete_warning"),
        type: "warning",
        showCancelButton: true,
        confirmButtonText: this.$t("common.delete_confirm_text"),
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.$store
            .dispatch("operations/deleteData", {
              path: "/api/payments/non-invoice/",
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
                  this.$t("payments.clients.non_invoice.index.delete_failed"),
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
