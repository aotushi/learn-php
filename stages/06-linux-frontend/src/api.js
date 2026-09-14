// 请求层：检查 HTTP 状态，也检查接口约定的 success。
export async function request(path, { token = '', method = 'GET', body } = {}) {
  const response = await fetch('/api' + path, {
    method,
    headers: {
      Accept: 'application/json',
      ...(body === undefined ? {} : { 'Content-Type': 'application/json' }),
      ...(token ? { Authorization: 'Bearer ' + token } : {}),
    },
    body: body === undefined ? undefined : JSON.stringify(body),
    signal: AbortSignal.timeout(10000),
  })
  const payload = await response.json().catch(() => null)
  if (!response.ok || !payload?.success) {
    const error = new Error(payload?.error || '服务不可用，请检查 ThinkPHP 状态。')
    error.status = response.status
    throw error
  }
  return payload.data
}
