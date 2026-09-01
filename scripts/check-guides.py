#!/usr/bin/env python3
"""guides/ の記述規約チェック。

使い方:
    python3 scripts/check-guides.py

規約は CLAUDE.md の「ガイド執筆ルール」を参照。
違反があれば内容を表示して終了コード 1 を返す。
"""
import re
import sys
from pathlib import Path

GUIDES = Path(__file__).resolve().parent.parent / "guides"


def outside_code(path):
    """コードブロックの外側の行だけを (行番号, 本文) で返す。"""
    in_fence = False
    for lineno, line in enumerate(path.read_text().splitlines(), 1):
        if line.startswith("```"):
            in_fence = not in_fence
            continue
        if not in_fence:
            yield lineno, line


def check(path, errors):
    text = path.read_text()
    name = path.name

    # 太字は禁止（コードブロック内のサンプルは対象外）
    for lineno, line in outside_code(path):
        if "**" in line:
            errors.append(f"{name}:{lineno}: 太字は使用禁止 -> {line.strip()}")
        if re.search(r"<(b|strong)\b", line, re.I):
            errors.append(f"{name}:{lineno}: 太字タグは使用禁止 -> {line.strip()}")

    # H1 は1ファイルにつき1つ
    h1 = [n for n, l in outside_code(path) if re.match(r"^# ", l)]
    if len(h1) != 1:
        errors.append(f"{name}: H1 は1つにしてください（現在 {len(h1)} 個 / 行 {h1}）")

    # コードフェンスの開閉
    if text.count("\n```") % 2 != (1 if text.startswith("```") else 0):
        errors.append(f"{name}: コードブロックの ``` が閉じていません")

    # 相対リンク先の存在
    for lineno, line in outside_code(path):
        for target in re.findall(r"\]\((\.?/?[\w.-]+\.md)\)", line):
            if not (GUIDES / target.lstrip("./")).exists():
                errors.append(f"{name}:{lineno}: リンク切れ -> {target}")


def main():
    files = sorted(GUIDES.glob("*.md"))
    if not files:
        print(f"guides/ に .md が見つかりません: {GUIDES}", file=sys.stderr)
        return 1

    errors = []
    for path in files:
        check(path, errors)

    if errors:
        print(f"規約違反 {len(errors)} 件:\n")
        for e in errors:
            print(f"  {e}")
        return 1

    print(f"OK: {len(files)} ファイルすべて規約を満たしています")
    return 0


if __name__ == "__main__":
    sys.exit(main())
