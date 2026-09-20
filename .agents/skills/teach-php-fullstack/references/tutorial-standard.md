# Lightweight tutorial standard

Use this standard for learner-facing Markdown in `README.md`, `docs/`, and `stages/`.

## Information structure

Use one handbook: `docs/README.md`. Root and stage READMEs point into it rather than repeat instructions. Each chapter has one completion record; appendices hold optional lookup content. Do not introduce another daily/checkpoint/capstone progress list.

Every assignment names its file location and execution command. State its prerequisites and data/login lifecycle. Put PHPUnit files in root `tests/`, and demonstrate a failing assertion before treating a new check as evidence. Separate supplied behavior from work the learner must implement.

Standalone reference examples use a local sequence plus a topic name; numbering restarts at 01 in each directory. State where the sequence applies and where material resumes in a later chapter. Keep operational entry points, data and class files named by role. Use semantic chapter anchors rather than old day numbers.

Use stage-based learning unit IDs consistently (02.3 in both handbook and source index). Stable CLI references belong in examples/; learner work and existing experiments retain their own paths. Map each unit to exact reading files, its runnable entry, the learner's file and observable success/failure. Optional reading needs its own ordered caller-to-classes path. Do not add a second curriculum document or leave a function assignment without an immediate caller/check.

## Lesson shape

Adapt the existing document instead of forcing headings that do not fit. A new lesson normally contains:

1. **Context**: why this matters to PHP, ThinkPHP, Vue admin integration, Magento, or daily maintenance.
2. **Goal**: one observable result and no more than three supporting objectives.
3. **Mental model**: the smallest explanation needed before action.
4. **Guided action**: annotated, copyable commands or code.
5. **Observation**: what changed and how to tell that it worked.
6. **Practice**: predict, run, modify, and explain.
7. **Completion checks**: behavior the learner can demonstrate.
8. **Next step**: one natural continuation, not a large reading list.

## Command block contract

Write shell blocks so the learner can copy the entire block. Put explanations inside the block as comments and follow it with only the detail needed for understanding.

```powershell
# Create and start the php service in the background; -d = --detach
docker compose up -d php

# Display the PHP version inside the running php service; exec = execute
docker compose exec php php --version
```

After the block, explain:

- what the learner should observe;
- new fields whose roles are not obvious;
- whether the command changes files, containers, networks, or stored data;
- the first safe diagnostic command if it fails.

Do not turn every repeated command into another large table. Fully explain the first occurrence, explain new fields later, and link to `docs/README.md#commands`.

## Language rules

- Use direct, concrete Chinese and short paragraphs.
- Introduce the Chinese meaning together with the English term when the English appears in commands or job descriptions.
- Distinguish a name, a command, an option, a service, and a file path.
- Avoid unexplained phrases such as “just run”, “obviously”, “simply”, and “as usual”.
- Avoid fake etymology. Some command words are names rather than abbreviations.
- Use approximate example output when versions vary, and label it as an example.

## Teaching-source contract

Review the PHP file a lesson asks the learner to read. For each first-use statement or unfamiliar expression, identify what is language syntax, a built-in, a framework method, or a local name. Explain relevant arguments, return value/output, control flow and failure at the point of use; point to an earlier explanation for familiar parts. Explain meaningful flag values and positional arguments rather than merely translating a function's name.

A standalone beginner example should not depend on the learner guessing `fn`, `: bool`, `=>`, `JSON_THROW_ON_ERROR`, `512`, or `STDERR`. These are examples of the review category, not a fixed keyword checklist. Keep the general lookup in `docs/README.md#php-reference` and the action-specific explanation beside the source. Do not add filler comments to already-understood punctuation or copy a full manual into each exercise.

For an annotation-only edit, preserve executable behavior and check relevant outputs. Machine checks for shell comments, links and PHP syntax cannot establish that the explanations are correct or complete; manually trace a representative normal input and a boundary/failure through the explained statements.

## Cognitive load rules

- Teach Docker only when it unlocks the current PHP, ThinkPHP, Vue integration, or Magento action.
- Keep the first phase on the small ThinkPHP and MySQL stack. Move Magento, search, queues, and advanced authentication to their explicit later stages.
- Introduce at most a few new command fields in one step.
- Keep recurring core ideas deep even when the page stays short. Refer to `docs/README.md#learning-method` at the repository root for this curriculum's priorities; defer peripheral detail instead of compressing essential practice.
- Put reusable definitions in the glossary instead of repeating them in every lesson.
- Use diagrams only when they clarify a relationship involving at least three parts, such as host → container → PHP process → database.
- Keep visual generation optional; the Markdown lesson must remain complete without generated images.

## Practice patterns

Prefer at least two of these:

- Predict the output before running a command.
- Change one argument and compare the result.
- Diagnose a small, reversible failure.
- Explain a command from left to right without looking at the glossary.
- Reuse a concept from an earlier stage in the current exercise.
- Complete a small problem before revealing the reference solution.
- At core checkpoints, distinguish guided, independent, and later-transfer evidence. Running supplied tests alone is not a mastery check.

## Review checklist

- [ ] The lesson produces one tangible result.
- [ ] Every executable shell command has a preceding plain-language comment.
- [ ] Teaching source was read; new statements, symbols, constants and relevant parameters have local explanations or specific earlier references.
- [ ] Comments distinguish inputs, outputs/returns and side effects; a prediction or small change checks understanding.
- [ ] New options and acronyms are expanded at first use.
- [ ] The expected result and first diagnostic step are stated.
- [ ] Destructive or secret-handling commands have an immediate warning.
- [ ] New reusable terms are synchronized with the glossary.
- [ ] Exercises require action or recall, not only reading.
- [ ] Core checkpoints include an independent variation and a later revisit; supplied functionality and learner-to-build functionality are clearly distinguished.
- [ ] Safe commands, all local fragments, and duplicate anchors were checked.
- [ ] The exercise can be followed from the documented starting state; cleanup does not remove data required by later steps.
- [ ] There is still only one learner handbook and one chapter-based progress system.
