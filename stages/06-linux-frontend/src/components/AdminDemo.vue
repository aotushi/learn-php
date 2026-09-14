<script setup>
import { ref, shallowRef } from 'vue'
import { useAdmin } from '../composables/useAdmin'
import LoginForm from './LoginForm.vue'
import ProductForm from './ProductForm.vue'
import ProductTable from './ProductTable.vue'
const { user, products, loading, error, canEdit, login, logout, refresh, save, remove } = useAdmin()
const editing = ref(null)
const showForm = shallowRef(false)
const formKey = shallowRef(0)
function edit(product = null) {
  editing.value = product
  formKey.value++
  showForm.value = true
}
async function saveProduct(data) {
  if (await save(editing.value?.id, data)) showForm.value = false
}
async function deleteProduct(product) {
  if (window.confirm('仅删除这件练习商品及其网址：' + product.name + '？')) {
    if (await remove(product.id)) showForm.value = false
  }
}
async function signOut() { showForm.value = false; await logout() }
</script>

<template>
  <p v-if="error" role="alert" class="error">{{ error }}</p>
  <p v-if="loading" role="status">请求处理中…</p>
  <LoginForm v-if="!user" :busy="loading" @login="login" />
  <section v-else>
    <div class="toolbar">
      <p>{{ user.username }} · {{ canEdit ? '可编辑' : '只读' }}</p>
      <button :disabled="loading" @click="refresh">刷新列表</button>
      <button v-if="canEdit" :disabled="loading" @click="edit()">新增商品</button>
      <button :disabled="loading" @click="signOut">退出登录</button>
    </div>
    <p v-if="!canEdit">只读账户可以查看商品，服务器会拒绝它的写入请求。</p>
    <ProductForm v-if="showForm && canEdit" :key="formKey" :product="editing" :busy="loading" @save="saveProduct" @cancel="showForm = false" />
    <ProductTable :products="products" :can-edit="canEdit" :busy="loading" @edit="edit" @remove="deleteProduct" />
  </section>
</template>
