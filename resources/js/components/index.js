import Vue from 'vue'
import Card from './Card'
import Child from './Child'
import Modal from './Modal'
import Search from './Search'
import VModal from './VModal'
import Button from './Button'
import Checkbox from './Checkbox'
import TopClients from './TopClients'
import StockAlert from './StockAlert'
import Pagination from './Pagination'
import EmptyTable from './EmptyTable'
import Breadcrumbs from './Breadcrumbs'
import CompanyInfo from './CompanyInfo'
import TableLoading from './TableLoading'
import SettingsSidebar from './SettingsSidebar'
import RecentActivities from './RecentActivities'
import ClientCreateModal from "./ClientCreateModal";
import ProductCreateModal from "./ProductCreateModal";
import SupplierCreateModal from "./SupplierCreateModal";


import {
  HasError,
  AlertError,
  AlertErrors,
  AlertSuccess
} from 'vform/src/components/bootstrap4'


// Components that are registered globally.
[
  Card,
  Child,
  Modal,
  Button,
  VModal,
  Search,
  Checkbox,
  HasError,
  StockAlert,
  EmptyTable,
  TopClients,
  Pagination,
  AlertError,
  AlertErrors,
  AlertSuccess,
  Breadcrumbs,
  CompanyInfo,
  TableLoading,
  SettingsSidebar,
  RecentActivities,
  ClientCreateModal,
  ProductCreateModal,
  SupplierCreateModal,

].forEach(Component => {
  Vue.component(Component.name, Component)
})
