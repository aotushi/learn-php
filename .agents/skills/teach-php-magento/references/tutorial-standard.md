# Lightweight tutorial standard

Use this standard for learner-facing Markdown in `README.md`, `docs/`, and `stages/`.

## Lesson shape

Adapt the existing document instead of forcing headings that do not fit. A new lesson normally contains:

1. **Context**: why this matters to PHP, Magento, or daily maintenance.
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

Do not turn every repeated command into another large table. Fully explain the first occurrence, explain new fields later, and link to `docs/command-glossary.md`.

## Language rules

- Use direct, concrete Chinese and short paragraphs.
- Introduce the Chinese meaning together with the English term when the English appears in commands or job descriptions.
- Distinguish a name, a command, an option, a service, and a file path.
- Avoid unexplained phrases such as “just run”, “obviously”, “simply”, and “as usual”.
- Avoid fake etymology. Some command words are names rather than abbreviations.
- Use approximate example output when versions vary, and label it as an example.

## Cognitive load rules

- Teach Docker only when it unlocks the current PHP or Magento action.
- Introduce at most a few new command fields in one step.
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

## Review checklist

- [ ] The lesson produces one tangible result.
- [ ] Every executable shell command has a preceding plain-language comment.
- [ ] New options and acronyms are expanded at first use.
- [ ] The expected result and first diagnostic step are stated.
- [ ] Destructive or secret-handling commands have an immediate warning.
- [ ] New reusable terms are synchronized with the glossary.
- [ ] Exercises require action or recall, not only reading.
- [ ] Safe commands and local links were verified.
