<script setup>
defineProps({ products: { type: Array, required: true }, canEdit: Boolean, busy: Boolean })
defineEmits(['edit', 'remove'])
</script>

<template>
  <p v-if="!products.length && !busy">暂无商品。管理员可以新增第一件商品。</p>
  <div v-else-if="products.length" class="table-scroll">
    <table>
      <caption>MySQL 中的商品（{{ products.length }} 件）</caption>
      <thead><tr><th>名称 / SKU</th><th>价格</th><th>状态</th><th v-if="canEdit">操作</th></tr></thead>
      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td>{{ product.name }}<small>{{ product.sku }}</small></td>
          <td>{{ product.currency }} {{ (product.price_minor / 100).toFixed(2) }}</td>
          <td>{{ product.status === 'enabled' ? '启用' : '停用' }}</td>
          <td v-if="canEdit">
            <button :disabled="busy" @click="$emit('edit', product)">编辑</button>
            <button :disabled="busy" @click="$emit('remove', product)">删除</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
