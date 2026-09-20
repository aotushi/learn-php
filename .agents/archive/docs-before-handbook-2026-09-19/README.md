# Documentation archive — 2026-09-19

Historical maintenance material, outside the learner route. The active guide is [docs/README.md](../../../docs/README.md).

The 19 original documents were copied byte for byte before consolidation, including uncommitted and untracked learner material. `manifest.json` records their original SHA-256 hashes; `migration.json` maps every original document to its current section. Original relative links inside the snapshot are historical and are not active navigation.

The original progress checklist contained no checked items; new chapter records remain blank. The old verification record describes earlier maintenance work, not learner achievement.

| Original | Active location / disposition |
| --- | --- |
| `docs/learning-path.md` | `docs/README.md#start` |
| `docs/core-knowledge.md` | `docs/README.md#learning-method` |
| `docs/command-glossary.md` | `docs/README.md#commands` |
| `docs/php-reading-guide.md` | `docs/README.md#php-reference` |
| `docs/practice-checkpoints.md` | `docs/README.md#learning-method` |
| `docs/progress-checklist.md` | `docs/README.md#learning-method` |
| `docs/docker-light-guide.md` | `docs/README.md#start` |
| `docs/docker-environment.md` | `docs/README.md#environment` |
| `docs/api-workshop.md` | `docs/README.md#api-login` |
| `docs/vue-admin-integration.md` | `docs/README.md#existing-vue` |
| `docs/php7-compatibility.md` | `docs/README.md#php7` |
| `docs/job-skill-map.md` | `docs/README.md#magento` |
| `docs/exercises/server-filter.md` | `docs/README.md#server-filter` |
| `docs/lessons/01-php.md` | `docs/README.md#basics` |
| `docs/lessons/02-foundations.md` | `docs/README.md#http-data` |
| `docs/lessons/03-thinkphp.md` | `docs/README.md#product-api` |
| `docs/lessons/04-integration.md` | `docs/README.md#vue` |
| `docs/lessons/05-delivery.md` | `docs/README.md#delivery` |
| `docs/verification.md` | `.agents/archive/docs-before-handbook-2026-09-19/docs/verification.md` |

## Consolidation verification

The active `docs` tree now contains one file: the 74,786-byte Chinese handbook. It has an overview, six sequential chapters, six chapter completion records, and three appendices. The 30 old day IDs remain as in-page bookmarks; old filenames are recorded in the migration map, not kept as competing guides.

The root README and first-phase stage READMEs are short entry points or source maps. The second-phase Magento directories remain explicitly unverified planning material. The teaching skill now requires one handbook, explicit exercise locations and starting states, and chapter-based progress records.

Verified on 2026-09-19:

- All 19 original document hashes match the archive; all 19 migration destinations are accounted for.
- The navigation checker passed for 21 active Markdown files and 119 local links, including ordinary section fragments and duplicate-ID detection. Its five regression cases passed.
- All 30 handbook PowerShell blocks parsed in Windows PowerShell 5.1. The actual login/create/update/permission/delete/logout sequence ran: 23 statements, create 201, expected failures 422/403/401/404/401, with rejected writes leaving the stored price unchanged.
- The new PHP examples ran. The query scaffold created three temporary rows and rolled them back. The PHPUnit scaffold passed two assertions against an isolated test double and failed when its expected value was intentionally changed. The test double was removed; no independent assignment solution was added to the learner source.
- Existing unit tests passed: 7 tests / 24 assertions. PHP syntax checking passed, including two temporary verification files subsequently removed. Existing API checks passed all 59 assertions. Vue production build passed.
- A temporary GFM rendering was inspected at desktop and mobile widths. Chapter links, appendix expansion, rendered fragment targets and mobile page width passed. This checks local rendering, not GitHub's hosted UI.
- All four learning-table row fingerprints matched before and after validation. Temporary products and tokens were cleaned up. Auto-increment counters may advance during tests; existing rows were preserved.
- Of 146 pre-existing project files, 106 were byte-identical, 19 were archived documents, and 21 changed as intended. The only PHP change was a documentation link inside a comment, verified by reversing that replacement and matching the original hash. Existing learner edits, including day05/day06 experiments, remain intact.

The new guide was checked against the actual scripts and configuration. Existing setup/reinstall instructions were not repeated; stopping the running environment was not performed during this documentation task. Learner-to-build detail/filter commands and Magento installation remain explicitly outside the completed verification. Default demonstration credentials worked in this local environment.

Automatic approval rejected deletion of the two empty former directories, `docs/lessons` and `docs/exercises`, with the reason “blocked by policy.” They contain no files and are not tracked by Git. No approval bypass was attempted.

Detailed logs and screenshots are kept in `.scratch/docs-handbook-2026-09-19/` outside the learner documentation.
