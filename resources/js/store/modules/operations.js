import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
  loading: true,
  items: null,
  appInfo: null,
}

// getters
export const getters = {
  loading: state => state.loading,
  items: state => state.items ? state.items.data : '',
  pagination: state => state.items ? state.items.meta : { current_page: 1 },
  appInfo: state => state.appInfo
}

// mutations
export const mutations = {
  // Items Mutations
  [types.FETCH_DATA] (state, { items, loading }) {
    state.items = items
    state.loading = loading || false
  },
  [types.DELETE_DATA] (state, { slug }) {
    state.items.data = state.items.data.filter(data => data.slug !== slug)
  },

  [types.FETCH_APPINFO] (state, { appInfo  }) {
    state.appInfo = appInfo
  },
}

// Actions
export const actions = {
  // Fetch Data
  async fetchData ({ commit }, { path, currentPage }) {
    const { data } = await axios.get(window.location.origin + path + currentPage)
    commit(types.FETCH_DATA, { items: data, loading: false })
  },

  // APPINFO Data
  async fetchSettingData ({ commit }) {
    const { data } = await axios.get(window.location.origin + '/api/general-settings')
    commit(types.FETCH_APPINFO, { appInfo: data })
  },

  // Search Data
  async searchData ({ commit }, { query, path, currentPage, term = '', startDate = '', endDate = '' }) {
    const { data } = await axios.get(window.location.origin + path + '?term=' + term + '&page=' + currentPage + '&startDate=' + startDate + '&endDate=' + endDate)
    commit(types.FETCH_DATA, { items: data })
  },
  // Get All Data
  async allData ({ commit }, { path }) {
    const { data } = await axios.get(window.location.origin + path)
    commit(types.FETCH_DATA, { items: data })
  },
  // Delete Data
  async deleteData ({ commit }, { path, slug }) {
    try {
      const { data } = await axios.delete(window.location.origin + path + slug)
      commit(types.DELETE_DATA, { slug: slug })
      return data.success
    } catch (error) {
      return error
    }
  }
}
