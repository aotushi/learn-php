# Upstream teaching methods

Use this file only when maintaining the Skill's teaching strategy.

## Matt Pocock

- [`teach`](https://github.com/mattpocock/skills/blob/main/skills/productivity/teach/SKILL.md): retain a learner mission, short lessons with one tangible win, glossary consistency, trusted sources, retrieval practice, spacing, interleaving, immediate feedback, and learning records.
- [`scaffold-exercises`](https://github.com/mattpocock/skills/blob/main/skills/misc/scaffold-exercises/SKILL.md): borrow the separation between explanation, problem, and reference solution when a stage needs formal exercises.
- [`wait-what`](https://github.com/mattpocock/skills/blob/main/skills/productivity/wait-what/SKILL.md): re-pitch unclear explanations with added context and simpler language.

## BaoYu

- [`baoyu-format-markdown`](https://github.com/JimLiu/baoyu-skills/blob/main/skills/baoyu-format-markdown/SKILL.md): use formatting ideas for readability, but do not rely on formatting to supply missing teaching content.
- [`baoyu-diagram`](https://github.com/JimLiu/baoyu-skills/blob/main/skills/baoyu-diagram/SKILL.md) and [`baoyu-infographic`](https://github.com/JimLiu/baoyu-skills/blob/main/skills/baoyu-infographic/SKILL.md): use only when a visual materially clarifies a system relationship or process.

## Deliberate local differences

- Produce lightweight Markdown rather than HTML lessons and browser widgets.
- Keep one learner handbook at `docs/README.md`; use `stages/` for source material and small file maps. Do not add parallel routes, mandatory guides, or progress lists.
- Teach Docker concurrently with PHP instead of treating Docker as an independent prerequisite.
- Use ThinkPHP and the supplied Vue demo for the first phase, with learning units mapped to source stages; keep existing-admin adaptation optional and Magento in a separate second phase.
- Reuse the Vue frontend through a small HTTP contract instead of copying its implementation or advanced authentication.
- Require a plain-language comment before every executable shell command.
- Review the PHP source as teaching material too: explain new statements, symbols, actual arguments and failure paths at their first use, not only the command that launches the file. Keep semantic review separate from automated formatting checks.
- Expand new options and acronyms on first use, then centralize reusable meanings in `docs/README.md#commands`.
- Verify copyable commands with the relevant Compose project and services.
- Concentrate recurring practice on a few transferable backend models. Track guided runs separately from independent implementation and later transfer, using the repository's `docs/README.md#learning-method` as the current learner-specific priority map.

When upstream Skills change, compare their teaching principles with this list. Adopt improvements individually and preserve local differences unless the learner explicitly changes the project strategy.
