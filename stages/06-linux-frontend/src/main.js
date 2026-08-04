import { createApp, ref } from 'vue'
import './style.css'

createApp({
  setup() {
    const products = ref([
      { sku: 'PHP-BOOK', name: 'PHP 入门练习商品', price: '¥99.00' },
      { sku: 'MAGE-MODULE', name: 'Magento 模块练习商品', price: '¥299.00' },
    ])

    return { products }
  },
  template: `
    <main>
      <h1>商品列表</h1>
      <article v-for="product in products" :key="product.sku">
        <div><strong>{{ product.name }}</strong><small>{{ product.sku }}</small></div>
        <span>{{ product.price }}</span>
      </article>
    </main>
  `,
}).mount('#app')
