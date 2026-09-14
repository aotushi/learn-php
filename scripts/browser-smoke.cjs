// 可选的维护者端到端检查：需要本机可解析 playwright 和已安装的 Chrome。
// 使用实际页面操作；只有“空列表”一项以拦截响应单独验证 UI 分支。
const { chromium } = require('playwright')
const assert = require('node:assert/strict')

;(async () => {
  const browser = await chromium.launch({ channel: 'chrome', headless: true })
  const context = await browser.newContext()
  const page = await context.newPage()
  page.setDefaultTimeout(15000)
  const base = process.env.FRONTEND_URL || 'http://localhost:15173'
  const sku = 'BROWSER-' + Date.now()
  let adminToken = ''
  let currentToken = ''
  let createdId = null
  const errors = []
  page.on('pageerror', error => errors.push(error.message))
  const api = (path, method, data, token = currentToken) =>
    context.request.fetch(base + '/api' + path, {
      method, data, headers: { Authorization: 'Bearer ' + token },
    })

  async function login(username, password = 'LearnPhp123!') {
    await page.getByLabel('用户名').fill(username)
    await page.getByLabel('密码', { exact: true }).fill(password)
    const response = page.waitForResponse(r => r.url().endsWith('/api/auth/login'))
    await page.getByRole('button', { name: '登录', exact: true }).click()
    const data = await (await response).json()
    if (data.success) currentToken = data.data.accessToken
    return data
  }

  try {
    await page.goto(base)
    await page.getByRole('heading', { name: '登录本机练习' }).waitFor()
    assert.equal((await login('admin', 'wrong')).success, false)
    await page.getByRole('alert').filter({ hasText: '用户名或密码错误' }).waitFor()
    assert.equal((await login('admin')).success, true)
    adminToken = currentToken
    await page.getByRole('table').waitFor()
    await page.getByRole('status').waitFor({ state: 'hidden' })
    assert.ok(await page.getByRole('row').count() > 1)
    console.log('PASS: rendered login, wrong password, real product list')

    await page.getByRole('button', { name: '新增商品', exact: true }).click()
    await page.getByLabel('商品名称').fill('Browser check')
    await page.getByLabel('SKU（留空自动生成）').fill(sku)
    await page.getByLabel('价格（分）').fill('1234')
    const created = page.waitForResponse(r => r.url().endsWith('/api/products/create'))
    await page.getByRole('button', { name: '保存商品', exact: true }).click()
    createdId = (await (await created).json()).data.id
    let row = page.getByRole('row').filter({ hasText: sku })
    await row.waitFor()
    assert.match(await row.innerText(), /12\.34/)
    await row.getByRole('button', { name: '编辑', exact: true }).click()
    await page.getByLabel('价格（分）').fill('4567')
    await page.getByRole('button', { name: '保存商品', exact: true }).click()
    await page.getByRole('heading', { name: '编辑商品' }).waitFor({ state: 'hidden' })
    await page.getByRole('button', { name: '刷新列表' }).click()
    await row.getByText('CNY 45.67', { exact: true }).waitFor()
    console.log('PASS: create, edit and reload persisted data')

    await page.getByRole('button', { name: '新增商品', exact: true }).click()
    await page.getByLabel('商品名称').fill('Duplicate')
    await page.getByLabel('SKU（留空自动生成）').fill(sku)
    await page.getByRole('button', { name: '保存商品', exact: true }).click()
    await page.getByRole('alert').filter({ hasText: 'SKU 已存在' }).waitFor()
    await page.getByRole('button', { name: '取消', exact: true }).click()
    page.once('dialog', dialog => dialog.accept())
    await row.getByRole('button', { name: '删除', exact: true }).click()
    await row.waitFor({ state: 'hidden' })
    createdId = null
    console.log('PASS: validation message and confirmed delete')

    await page.getByRole('button', { name: '退出登录' }).click()
    await page.getByRole('heading', { name: '登录本机练习' }).waitFor()
    assert.equal((await login('viewer')).success, true)
    await page.getByRole('table').waitFor()
    assert.equal(await page.getByRole('button', { name: '新增商品', exact: true }).count(), 0)
    assert.equal((await api('/products/create', 'POST', { name: 'Forbidden' })).status(), 403)
    await context.setOffline(true)
    await page.getByRole('button', { name: '刷新列表' }).click()
    await page.getByRole('alert').waitFor()
    await context.setOffline(false)
    await page.getByRole('button', { name: '刷新列表' }).click()
    await page.getByRole('alert').waitFor({ state: 'hidden' })
    console.log('PASS: read-only UI, server 403, offline failure and recovery')

    await page.route('**/api/products/list', route => route.fulfill({
      json: { success: true, data: [] },
    }))
    await page.getByRole('button', { name: '刷新列表' }).click()
    await page.getByText('暂无商品。管理员可以新增第一件商品。').waitFor()
    await page.unroute('**/api/products/list')
    await page.getByRole('button', { name: '刷新列表' }).click()
    await page.getByRole('table').waitFor()
    await page.getByRole('status').waitFor({ state: 'hidden' })
    assert.ok(await page.getByRole('row').count() > 1)
    if (process.env.SCREENSHOT_PATH) await page.screenshot({ path: process.env.SCREENSHOT_PATH, fullPage: true })
    console.log('PASS: empty-state UI (mocked response), restored real list')

    await api('/auth/logout', 'POST')
    await page.getByRole('button', { name: '刷新列表' }).click()
    await page.getByRole('heading', { name: '登录本机练习' }).waitFor()
    assert.deepEqual(errors, [])
    console.log('PASS: revoked session returns to login; no page JavaScript errors')
  } finally {
    if (createdId) await api('/products/' + createdId, 'DELETE', undefined, adminToken).catch(() => {})
    for (const token of new Set([currentToken, adminToken])) {
      if (token) await api('/auth/logout', 'POST', undefined, token).catch(() => {})
    }
    await browser.close()
  }
})().catch(error => { console.error(error); process.exitCode = 1 })
