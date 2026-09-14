// 本地文档链接与显式锚点检查；不请求外部网址。
import { readFileSync, readdirSync, existsSync, statSync } from 'node:fs'
import path from 'node:path'
const root = path.resolve(import.meta.dirname, '..')
const excluded = new Set(['vendor', 'node_modules', 'runtime', 'dist', '.git'])
function walk(dir) {
  return readdirSync(dir, { withFileTypes: true }).flatMap(entry => {
    const target = path.join(dir, entry.name)
    return entry.isDirectory() ? (excluded.has(entry.name) ? [] : walk(target))
      : entry.name.endsWith('.md') ? [target] : []
  })
}
const files = [path.join(root, 'README.md'), ...walk(path.join(root, 'docs')), ...walk(path.join(root, 'stages'))]
const failures = []
let count = 0
for (const file of files) {
  const source = readFileSync(file, 'utf8')
  for (const match of source.matchAll(/\[[^\]]*\]\(([^)]+)\)/g)) {
    const link = match[1]
    if (/^(https?:|mailto:)/.test(link)) continue
    const [target, anchor] = link.split('#')
    const resolved = target ? path.resolve(path.dirname(file), decodeURIComponent(target)) : file
    count++
    if (!existsSync(resolved)) failures.push(file + ': missing ' + link)
    else if (anchor?.startsWith('day-') && statSync(resolved).isFile()
      && !readFileSync(resolved, 'utf8').includes('id="' + anchor + '"')) {
      failures.push(file + ': missing anchor ' + link)
    }
  }
}
if (failures.length) { console.error(failures.join('\n')); process.exitCode = 1 }
else console.log('OK: ' + files.length + ' Markdown files, ' + count + ' local links/anchors')
