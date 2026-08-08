#!/usr/bin/env python3
"""Check that shell commands in learner Markdown have explanatory comments."""

from __future__ import annotations

import argparse
import re
import sys
from pathlib import Path


SHELL_LANGUAGES = {"bash", "console", "powershell", "pwsh", "sh", "shell"}
SKIPPED_DIRECTORIES = {".agents", ".git", "node_modules", "vendor"}
FENCE_START = re.compile(r"^\s*```\s*([A-Za-z0-9_-]*)\s*$")
FENCE_END = re.compile(r"^\s*```\s*$")


def find_project_root() -> Path:
    current = Path(__file__).resolve().parent
    for candidate in (current, *current.parents):
        if (candidate / ".git").exists():
            return candidate
    raise RuntimeError("Could not find the project root containing .git")


def markdown_files(paths: list[Path]) -> list[Path]:
    files: set[Path] = set()
    for path in paths:
        if path.is_file() and path.suffix.lower() == ".md":
            files.add(path.resolve())
            continue
        if not path.is_dir():
            raise FileNotFoundError(f"Path does not exist: {path}")
        for candidate in path.rglob("*.md"):
            if not any(part in SKIPPED_DIRECTORIES for part in candidate.parts):
                files.add(candidate.resolve())
    return sorted(files)


def command_violations(path: Path) -> list[tuple[int, str]]:
    violations: list[tuple[int, str]] = []
    active_shell_block = False
    previous_content = ""
    previous_was_continuation = False

    for line_number, raw_line in enumerate(
        path.read_text(encoding="utf-8-sig").splitlines(), start=1
    ):
        fence = FENCE_START.match(raw_line)
        if not active_shell_block and fence:
            active_shell_block = fence.group(1).lower() in SHELL_LANGUAGES
            previous_content = ""
            previous_was_continuation = False
            continue

        if active_shell_block and FENCE_END.match(raw_line):
            active_shell_block = False
            previous_content = ""
            previous_was_continuation = False
            continue

        if not active_shell_block:
            continue

        stripped = raw_line.strip()
        if not stripped:
            continue

        if stripped.startswith("#"):
            previous_content = stripped
            previous_was_continuation = False
            continue

        is_continuation = previous_was_continuation or stripped.startswith(("|", "&&", "||"))
        if not is_continuation and not previous_content.startswith("#"):
            violations.append((line_number, stripped))

        previous_content = stripped
        previous_was_continuation = stripped.endswith(("`", "\\"))

    return violations


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(
        description=(
            "Check powershell/bash/sh/shell/console Markdown blocks and require a "
            "comment before each command."
        )
    )
    parser.add_argument(
        "paths",
        nargs="*",
        help="Markdown files or directories; defaults to README.md, docs, and stages.",
    )
    return parser.parse_args()


def main() -> int:
    args = parse_args()
    project_root = find_project_root()
    requested = (
        [Path(item) if Path(item).is_absolute() else project_root / item for item in args.paths]
        if args.paths
        else [project_root / "README.md", project_root / "docs", project_root / "stages"]
    )

    try:
        files = markdown_files(requested)
    except FileNotFoundError as error:
        print(error, file=sys.stderr)
        return 2

    all_violations: list[tuple[Path, int, str]] = []
    for path in files:
        for line_number, command in command_violations(path):
            all_violations.append((path, line_number, command))

    if all_violations:
        print("Tutorial command comments are missing:")
        for path, line_number, command in all_violations:
            display_path = path.relative_to(project_root)
            print(f"- {display_path}:{line_number}: {command}")
        print("Add a plain-language # comment immediately before each command.")
        return 1

    print(f"OK: checked {len(files)} Markdown files; every shell command has a comment.")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
