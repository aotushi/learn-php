<script setup>
import { reactive } from 'vue'
defineProps({ busy: Boolean })
const emit = defineEmits(['login'])
const form = reactive({ username: 'admin', password: '' })
</script>

<template>
  <form class="login" @submit.prevent="emit('login', { ...form })">
    <h2>登录本机练习</h2>
    <p>编辑账户：admin；只读账户：viewer。初始密码均为 LearnPhp123!</p>
    <label>用户名 <input v-model="form.username" name="username" autocomplete="username" required></label>
    <label>密码 <input v-model="form.password" name="password" type="password" autocomplete="current-password" required></label>
    <button :disabled="busy">{{ busy ? '登录中…' : '登录' }}</button>
  </form>
</template>
