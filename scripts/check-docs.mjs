// Check active Markdown navigation; archived snapshots keep historical links.
import { readFileSync, readdirSync, existsSync, statSync } from 'node:fs'
import path from 'node:path'
import { pathToFileURL } from 'node:url'
const root = path.resolve(import.meta.dirname, '..')
const excluded = new Set(['vendor', 'node_modules', 'runtime', 'dist', '.git', 'archive'])
function walk(dir) {
  return readdirSync(dir, { withFileTypes: true }).flatMap(entry => {
    const target = path.join(dir, entry.name)
    return entry.isDirectory() ? (excluded.has(entry.name) ? [] : walk(target))
      : entry.name.endsWith('.md') ? [target] : []
  })
}
// Fenced examples and HTML comments are not rendered navigation.
function prose(source) {
  let fence
  return source.replace(/<!--[\s\S]*?-->/g, '').split(/\r?\n/).map(line => {
    const marker = line.match(/^ {0,3}(`{3,}|~{3,})(.*)$/)
    if (!fence && marker) { fence = marker[1]; return '' }
    if (fence) {
      if (marker && marker[1][0] === fence[0] && marker[1].length >= fence.length
        && marker[2].trim() === '') fence = undefined
      return ''
    }
    return line
  }).join('\n')
}
function slug(heading) {
  return heading.replace(/\[([^\]]+)\]\([^)]*\)/g, '$1').replace(/<[^>]+>/g, '')
    .replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>')
    .replace(/&quot;/g, '"').replace(/&#39;/g, "'").replace(/`/g, '').toLowerCase()
    .replace(/[^\p{L}\p{M}\p{N}\s_-]/gu, '').replace(/\s/g, '-')
}
function inspect(source) {
  const text = prose(source)
  // Inline code examples are not links or explicit HTML anchors.
  const navigation = text.replace(/(`+)([\s\S]*?)\1/g, '')
  const anchors = new Set()
  const duplicates = []
  const add = id => { if (anchors.has(id)) duplicates.push(id); anchors.add(id) }
  for (const match of navigation.matchAll(/<[^>]+\s+id\s*=\s*["']([^"']+)["'][^>]*>/g)) add(match[1])
  const headingIds = new Set()
  for (const match of text.matchAll(/^ {0,3}#{1,6}\s+(.+?)(?:\s+#+)?\s*$/gm)) {
    const base = slug(match[1])
    let id = base
    let suffix = 0
    while (headingIds.has(id)) id = base + '-' + ++suffix
    headingIds.add(id)
    add(id)
  }
  const links = []
  const inline = /!?\[[^\]\n]*\]\(\s*(?:<([^>]+)>|([^\s)]+))(?:\s+["'][^"']*["'])?\s*\)/g
  for (const match of navigation.matchAll(inline)) links.push(match[1] ?? match[2])
  for (const match of navigation.matchAll(/<a\b[^>]*\bhref=["']([^"']+)["'][^>]*>/g)) links.push(match[1])
  return { anchors, duplicates, links }
}
export function checkDocuments(files) {
  const failures = []
  const cache = new Map()
  const read = file => {
    if (!cache.has(file)) {
      const document = inspect(readFileSync(file, 'utf8'))
      cache.set(file, document)
      for (const id of document.duplicates) failures.push(file + ': duplicate anchor #' + id)
    }
    return cache.get(file)
  }
  let count = 0
  for (const file of files.map(file => path.resolve(file))) {
    for (const link of read(file).links) {
      if (/^(?:[a-z][a-z\d+.-]*:|\/\/)/i.test(link)) continue
      count++
      try {
        const hash = link.indexOf('#')
        const target = hash < 0 ? link : link.slice(0, hash)
        const anchor = hash < 0 ? '' : decodeURIComponent(link.slice(hash + 1))
        const resolved = target ? path.resolve(path.dirname(file), decodeURIComponent(target)) : file
        if (!existsSync(resolved)) failures.push(file + ': missing ' + link)
        else if (anchor) {
          if (!statSync(resolved).isFile() || path.extname(resolved).toLowerCase() !== '.md') {
            failures.push(file + ': cannot resolve Markdown anchor ' + link)
          } else if (!read(resolved).anchors.has(anchor)) failures.push(file + ': missing anchor ' + link)
        }
      } catch (error) {
        failures.push(file + ': invalid link ' + link + ' (' + error.message + ')')
      }
    }
  }
  return { failures, count }
}
if (process.argv[1] && import.meta.url === pathToFileURL(path.resolve(process.argv[1])).href) {
  const files = [path.join(root, 'README.md'), ...walk(path.join(root, 'docs')),
    ...walk(path.join(root, 'stages')), ...walk(path.join(root, '.agents/skills/teach-php-fullstack'))]
  const { failures, count } = checkDocuments(files)
  if (failures.length) { console.error(failures.join('\n')); process.exitCode = 1 }
  else console.log('OK: ' + files.length + ' Markdown files, ' + count + ' local links; anchors unique and resolved')
}
