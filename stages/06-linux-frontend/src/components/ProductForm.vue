<script setup>
import { reactive, watch } from 'vue'
const props = defineProps({ product: { type: Object, default: null }, busy: Boolean })
const emit = defineEmits(['save', 'cancel'])
const blank = () => ({ name: '', sku: '', price_minor: 0, stock_qty: 0, currency: 'CNY', status: 'enabled' })
const form = reactive(blank())
watch(() => props.product, value => {
  Object.assign(form, blank(), value || {})
}, { immediate: true })
function submit() {
  const data = {
    name: form.name, price_minor: Number(form.price_minor), stock_qty: Number(form.stock_qty),
    currency: form.currency, status: form.status,
  }
  if (form.sku.trim()) data.sku = form.sku.trim()
  emit('save', data)
}
</script>

<template>
  <form class="editor" @submit.prevent="submit">
    <h2>{{ product ? '编辑商品' : '新增商品' }}</h2>
    <label>商品名称 <input v-model="form.name" name="name" maxlength="255" required></label>
    <label>SKU（留空自动生成）<input v-model="form.sku" name="sku" maxlength="64"></label>
    <label>价格（分）<input v-model="form.price_minor" name="price_minor" type="number" min="0" max="2147483647" step="1" required></label>
    <label>库存（演示字段）<input v-model="form.stock_qty" name="stock_qty" type="number" min="0" max="2147483647" step="1" required></label>
    <label>币种 <select v-model="form.currency"><option>CNY</option><option>USD</option><option>EUR</option></select></label>
    <label>状态 <select v-model="form.status"><option value="enabled">启用</option><option value="disabled">停用</option></select></label>
    <button :disabled="busy">{{ busy ? '保存中…' : '保存商品' }}</button>
    <button type="button" :disabled="busy" @click="emit('cancel')">取消</button>
  </form>
</template>
