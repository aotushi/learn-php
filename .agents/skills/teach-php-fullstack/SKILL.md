---
name: teach-php-fullstack
description: Create, update, and review lightweight beginner-facing Markdown lessons for this repository's PHP 8.4, MySQL 8.4, ThinkPHP 8.1, Vue admin, Docker, and later Magento curriculum. Use whenever editing README.md, docs/*.md, or stages/**/*.md to add tutorials, commands, exercises, daily learning paths, glossaries, framework transitions, or explanations, especially when shell commands and technical acronyms must be explained without overwhelming the learner.
---

# Teach PHP Full Stack

Create small, practical lessons for a returning beginner. Keep the first 30-day phase focused on PHP, MySQL, ThinkPHP, and an existing Vue admin; keep Magento as a separate second phase. Relearn only the Docker needed for the current task.

## Prepare

1. Read the root `README.md`, `docs/learning-path.md`, `docs/command-glossary.md`, and the target stage document.
2. Read [references/tutorial-standard.md](references/tutorial-standard.md) completely before creating or reviewing material that contains commands, code, or exercises.
3. Preserve the learner's current level, the existing Chinese voice, the staged directory structure, and unrelated worktree changes.
4. Define one tangible result for the lesson. Include only the knowledge needed to reach it.

## Write the lesson

1. Start with why the task matters and what the learner will make, run, or observe.
2. Introduce one small concept at a time, then let the learner use it immediately.
3. Keep Docker alongside PHP as a supporting tool. Do not require a separate Docker course before PHP practice.
4. Reuse the existing Vue admin through an HTTP contract. Do not rebuild its UI or import advanced authentication into the first phase.
5. Prefer short Markdown sections, copyable examples, and observable results over long theory.
6. Explain a technical term or acronym at its first meaningful appearance. Add reusable terms to `docs/command-glossary.md` and use the same wording afterward.
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
- Avoid repeating a full field table after the first explanation. Link to `docs/command-glossary.md` and explain only new or context-sensitive fields.

## Build practice and feedback

- Use a tight loop: predict, run, observe, change one thing, and explain the result.
- Give hints before solutions when the learner is expected to solve something.
- Reuse earlier ideas in later exercises so knowledge is retrieved instead of merely reread.
- Keep each exercise runnable with the services required by that stage. Use the `thinkphp` profile in the first phase and do not start the full Magento stack before stage 10.
- Offer a simpler re-explanation when wording does not land; add context and an example instead of merely paraphrasing the same sentence.

## Verify before finishing

1. Run `python .agents/skills/teach-php-fullstack/scripts/check_tutorial_docs.py <changed-markdown-paths>`.
2. Run every safe command that was added or changed, using the smallest relevant Docker profile.
3. Check relative links and confirm referenced files exist.
4. Confirm every new acronym, option, and command field is explained locally or in the glossary.
5. Confirm the lesson remains lightweight: one result, short theory, immediate practice, and no duplicated reference material.
6. Report what was verified and identify any command that could not be safely run.

## Maintain the skill

Read [references/upstream-methods.md](references/upstream-methods.md) only when changing this Skill's teaching strategy or comparing it with upstream Skills. Keep local beginner-command rules deliberate instead of blindly replacing them with upstream changes.
