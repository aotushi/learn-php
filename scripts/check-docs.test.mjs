import test from 'node:test'
import assert from 'node:assert/strict'
import { mkdtempSync, writeFileSync, rmSync } from 'node:fs'
import os from 'node:os'
import path from 'node:path'
import { checkDocuments } from './check-docs.mjs'
function fixture(t, files) {
  const dir = mkdtempSync(path.join(os.tmpdir(), 'learn-php-docs-'))
  t.after(() => {
    assert.equal(path.dirname(path.resolve(dir)), path.resolve(os.tmpdir()))
    assert.ok(path.basename(dir).startsWith('learn-php-docs-'))
    rmSync(dir, { recursive: true, force: true })
  })
  for (const [name, content] of Object.entries(files)) writeFileSync(path.join(dir, name), content)
  return Object.keys(files).map(name => path.join(dir, name))
}
test('valid navigation: same-page, cross-page, encoded path and heading fragments', t => {
  const files = fixture(t, {
    'README.md': '# Start\n<a id="intro"></a>\n[Here](#intro)\n[There](guide%20one.md#%E4%BB%B7%E6%A0%BC)\n[Repeat](guide%20one.md#repeat-1)',
    'guide one.md': '# 价格\n## Repeat\n## Repeat\n',
  })
  assert.deepEqual(checkDocuments(files), { failures: [], count: 3 })
})
test('rejects ordinary missing fragments, not just day/checkpoint fragments', t => {
  const files = fixture(t, {
    'README.md': '# Start\n[Bad](#product-api)\n[Missing](absent.md)\n[Cross](other.md#wrong)\n',
    'other.md': '<a id="correct"></a>',
  })
  const result = checkDocuments(files)
  assert.equal(result.failures.length, 3)
  assert.ok(result.failures.some(message => message.includes('missing anchor #product-api')))
  assert.ok(result.failures.some(message => message.includes('missing absent.md')))
  assert.ok(result.failures.some(message => message.includes('missing anchor other.md#wrong')))
})
test('rejects duplicate IDs and explicit IDs colliding with heading navigation', t => {
  const files = fixture(t, {
    'README.md': '<a id="same"></a>\n<a id=\'same\'></a>\n<a id="title"></a>\n# Title\n',
  })
  const result = checkDocuments(files)
  assert.equal(result.failures.length, 2)
  assert.ok(result.failures.some(message => message.includes('duplicate anchor #same')))
  assert.ok(result.failures.some(message => message.includes('duplicate anchor #title')))
})
test('ignores examples and comments but checks collapsed appendix links', t => {
  const files = fixture(t, {
    'README.md': ['<a id="real"></a>', '`[example](missing.md)`', '`<a id="real"></a>`',
      '```md', '<a id="real"></a>', '[example](missing.md)', '```',
      '~~~md', '[example](missing.md)', '~~~', '<!-- [old](missing.md) -->',
      '<details>', '<summary>Reference</summary>', '[Appendix](#real)', '</details>',
      '[External](https://example.org/missing#anchor)'].join('\n'),
  })
  assert.deepEqual(checkDocuments(files), { failures: [], count: 1 })
})
test('reports malformed percent encoding without aborting other checks', t => {
  const files = fixture(t, { 'README.md': '[Bad](bad%ZZ.md)\n[Also bad](missing.md)\n' })
  const result = checkDocuments(files)
  assert.equal(result.failures.length, 2)
  assert.match(result.failures[0], /invalid link/)
  assert.match(result.failures[1], /missing missing.md/)
})
