import { computed, readonly, ref, shallowRef } from 'vue'
import { request } from '../api'

export function useAdmin() {
  const user = ref(null)
  const products = ref([])
  const loading = shallowRef(false)
  const error = shallowRef('')
  const canEdit = computed(() => user.value?.role === 'admin')
  // 教学示例只在内存保存令牌；刷新整个页面后重新登录。
  let token = ''
  const api = (path, options = {}) => request(path, { ...options, token })
  const load = async () => { products.value = await api('/products/list') }
  const clear = () => { token = ''; user.value = null; products.value = [] }

  async function perform(action) {
    if (loading.value) return false
    loading.value = true
    error.value = ''
    try {
      await action()
      return true
    } catch (cause) {
      if (cause.status === 401) clear()
      error.value = cause.name === 'TimeoutError' ? '请求超时，请重试。' : cause.message
      return false
    } finally {
      loading.value = false
    }
  }

  const login = credentials => perform(async () => {
    const data = await api('/auth/login', { method: 'POST', body: credentials })
    token = data.accessToken
    user.value = data.user
    await load()
  })
  const logout = () => perform(async () => {
    try { await api('/auth/logout', { method: 'POST' }) } finally { clear() }
  })
  const refresh = () => perform(load)
  const save = (id, data) => perform(async () => {
    await api(id ? '/products/' + id : '/products/create', {
      method: id ? 'PUT' : 'POST', body: data,
    })
    await load()
  })
  const remove = id => perform(async () => {
    await api('/products/' + id, { method: 'DELETE' })
    await load()
  })
  return { user: readonly(user), products: readonly(products), loading: readonly(loading),
    error: readonly(error), canEdit, login, logout, refresh, save, remove }
}
