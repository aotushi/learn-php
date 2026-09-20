---
name: teach-php-fullstack
description: Create, update, and review lightweight Chinese lessons and annotated PHP teaching examples in this repository. Use for beginner-facing Markdown, PHP exercise source, command and syntax explanations, glossaries, and staged learning tasks without overwhelming the learner.
---

# Teach PHP Full Stack

Create small, practical lessons for a returning beginner. Keep the first phase focused on PHP, MySQL, ThinkPHP, and the supplied Vue demo, with existing-admin adaptation optional; keep Magento as a separate second phase. Relearn only the Docker needed for the current task.

## Prepare

1. Read the root `README.md` and the relevant chapter of `docs/README.md`, including its prerequisites and Appendix A when needed. Stage READMEs are source maps only.
2. Read [references/tutorial-standard.md](references/tutorial-standard.md) completely before creating or reviewing material that contains commands, code, or exercises.
3. Preserve the learner's current level, the existing Chinese voice, the staged directory structure, and unrelated worktree changes.
4. Define one tangible result for the lesson. Include only the knowledge needed to reach it.
5. For curriculum or exercise changes, read the repository's `docs/README.md#learning-method`. Tie the task to a core idea rather than adding topics merely to fill a calendar day.
6. Read the source the learner is asked to open, not just the Markdown commands. Inventory new syntax, functions, constants, arguments, and failure branches against earlier lessons and `docs/README.md#php-reference`.

## Single handbook contract

- `docs/README.md` is the sole learner guide. Keep the root README short; stage READMEs only locate source files.
- Use the same stage-based learning unit IDs in the handbook, source pointers and stage indexes (for example, 02.3). Each unit maps its exact reading files, runnable entry, learner-owned output and expected results; directory numbers identify topics, and explicit next links define the learning sequence.
- Keep stable CLI references in each stage's `examples/`; keep learner work and earlier experiments in `exercises/` and their existing paths. Never overwrite an experiment to manufacture a clean reference. Class files and HTTP entry points retain their role-based locations.
- Add explanations, guided actions, independent work, and completion evidence to the relevant chapter. Put reusable lookup content in its appendices and link to explicit anchors.
- Keep one progress system in chapter-end records. Preserve recorded learner evidence; never mark independent work complete from supplied test results.
- Keep maintenance history and superseded guides outside `docs/`. An archive is historical material, not a second learning route.
- Keep standalone reference examples named with a contiguous local two-digit sequence and a descriptive topic (for example, `01-variables.php`). Restart at 01 in each directory and order by prerequisites: ordinary functions before callbacks. Do not use day-based names or links.
- Map handbook chapters to topic directories in the overview. Runtime entry points, class files, data fixtures and learner-owned `my-*.php` files keep their role-based names; do not number them as lessons.
- When renaming examples, update commands, source maps and source comments, preserve executable PHP tokens, and run the renamed files. Keep historical archives unchanged.
- For each exercise specify the file, command, starting service/login/data state, expected normal and failed results, and cleanup. Root `tests/` is the PHPUnit discovery location.
- Track state through multi-step examples: test missing-product 404 while still authenticated; log out only after data-dependent checks and cleanup.
- Keep learner edits intact. Do not turn an audit or documentation cleanup into solving the independent detail/filter tasks.
- Introduce cross-file loading before relying on it: distinguish namespace, use, require and the Composer entry point with a failing/restored experiment. Optional class groups still need an ordered reading path and a runnable caller.

## Write the lesson

1. Start with why the task matters and what the learner will make, run, or observe.
2. Introduce one small concept at a time, then let the learner use it immediately.
3. Keep Docker alongside PHP as a supporting tool. Do not require a separate Docker course before PHP practice.
4. Use the supplied small Vue demo to observe the HTTP contract. Keep adaptation of an external admin optional; do not make another repository a prerequisite.
5. Prefer short Markdown sections, copyable examples, and observable results over long theory.
6. Explain a technical term or acronym at its first meaningful appearance. Add reusable terms to `docs/README.md#commands` and use the same wording afterward.
7. End with a small retrieval or modification exercise and concrete completion checks.

## Explain commands

Apply these rules to PowerShell, Bash, shell, Docker, Composer, PHP CLI, ThinkPHP, MySQL, Node.js, and Magento commands:

- Put a plain-language `#` comment immediately before each executable command line. Treat continuation lines as part of the same command.
- State the command's purpose before asking the learner to run it.
- Expand a short option or acronym on first use, such as `-d` → `--detach` and `exec` → `execute`.
- Do not invent expansions. Say that a word is not an abbreviation when that distinction prevents confusion.
- Explain repeated-looking fields by role, such as the PHP service name versus the PHP executable.
- Describe the expected signal rather than promising exact output that changes by version or machine.
- Warn immediately before commands that delete data, overwrite files, expose secrets, or make hard-to-reverse changes. State the affected scope and a safer alternative when one exists.
- Avoid repeating a full field table after the first explanation. Link to `docs/README.md#commands` and explain only new or context-sensitive fields.

## Explain teaching code

- Explain first-use syntax at its use site: meaning, relevant inputs/arguments, result or side effect, and failure behavior. A comment saying only “run the example” or “validate input” is not a syntax explanation.
- Distinguish PHP language constructs, built-in functions/constants, framework helpers, and repository-defined names. Explain symbols by context, especially `=>`, `::`, `->`, `?:`, `??`, and array versus numeric operations.
- In early short exercises annotate each newly introduced statement or coherent expression. In later files explain new idioms and point to prior explanations for repeated ones; do not repeat a paragraph on every assignment or brace.
- Keep explanations next to code, with reusable lookup content in `docs/README.md#php-reference`. Explain the actual arguments used, including flags/defaults, without requiring memorization of every API option.
- Preserve behavior when adding teaching comments. Compare non-comment PHP tokens and run relevant examples; do not repair unrelated learner edits or supply answers to independent assignments.

## Build practice and feedback

- Allocate most practice to request/state, input/output, data integrity, server-side identity/permissions, and evidence-based debugging. Treat command spellings and framework API names as lookup knowledge, not memorization goals.
- Separate a guided run from independent mastery. At core checkpoints require explanation, a small implementation without copying, failure diagnosis, and a later variation; allow documentation lookup and incremental hints.
- Reuse known frontend skills to exercise the PHP boundary. Do not spend a PHP capstone mainly rebuilding familiar Vue behavior.
- Mark learner assignments as unimplemented when the reference app does not supply them. Existing test passes prove the reference behavior, not the learner's new feature or understanding.
- Give each independent function an immediate caller or focused test. State the current contract separately from later extensions. Teach focused checks first; keep optional examples and maintainer-wide regressions out of early lesson commands.
- Reusable HTTP teaching helpers must only define functions/classes; loading them must not run a test suite. Teach one read-only assertion, then session cleanup, before assigning full authenticated checks.
- Use a tight loop: predict, run, observe, change one thing, and explain the result.
- Give hints before solutions when the learner is expected to solve something.
- Reuse earlier ideas in later exercises so knowledge is retrieved instead of merely reread.
- Keep each exercise runnable with the services required by that stage. Use the default main Compose project for the first phase. Magento uses compose.magento.yaml and its own project; do not start it before stage 10. Keep first-time dependency/data preparation distinct from everyday startup.
- Offer a simpler re-explanation when wording does not land; add context and an example instead of merely paraphrasing the same sentence.

## Verify before finishing

1. Run `python .agents/skills/teach-php-fullstack/scripts/check_tutorial_docs.py <changed-markdown-paths>`.
2. Run every safe command that was added or changed, using the relevant Compose project and services.
3. Run `node scripts/check-docs.mjs` to check local links, all fragments and duplicate anchors; confirm referenced exercise files are explicitly marked as learner-created.
4. Confirm every new acronym, option, and command field is explained locally or in the glossary.
5. Confirm the lesson remains lightweight: one result, short theory, immediate practice, and no duplicated reference material.
6. Report what was verified and identify any command that could not be safely run.
7. Separately audit explanation coverage: each new construct in the day's reading has a local explanation or a specific earlier reference, and one prediction/change exercise checks the meaning. The shell-comment script checks command formatting only; passing it, lint, or tests does not establish teaching completeness.

## Maintain the skill

Read [references/upstream-methods.md](references/upstream-methods.md) only when changing this Skill's teaching strategy or comparing it with upstream Skills. Keep local beginner-command rules deliberate instead of blindly replacing them with upstream changes.
