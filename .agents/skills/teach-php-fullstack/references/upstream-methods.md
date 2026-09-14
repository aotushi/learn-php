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
- Use the existing `stages/` curriculum rather than creating a parallel lesson workspace.
- Teach Docker concurrently with PHP instead of treating Docker as an independent prerequisite.
- Use ThinkPHP and an existing Vue admin for the 30-day first phase; keep Magento as a separate second phase.
- Reuse the Vue frontend through a small HTTP contract instead of copying its implementation or advanced authentication.
- Require a plain-language comment before every executable shell command.
- Expand new options and acronyms on first use, then centralize reusable meanings in `docs/command-glossary.md`.
- Verify copyable commands with the smallest relevant Docker profile.

When upstream Skills change, compare their teaching principles with this list. Adopt improvements individually and preserve local differences unless the learner explicitly changes the project strategy.
