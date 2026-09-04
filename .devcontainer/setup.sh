#!/usr/bin/env bash
# Codespaces 起動後に1回だけ走る初期設定。
# ここで失敗しても Codespace は起動するので、当日は setup.sh のログを確認してもらう。
set -euo pipefail

# Xdebug は当日使わない。有効なままだと php コマンドのたびに
# 「Could not connect to debugging client」が出て初学者を混乱させる。
sudo tee /usr/local/etc/php/conf.d/zz-handson.ini >/dev/null <<'INI'
xdebug.mode=off
memory_limit=512M
INI

# 04.md で使う laravel コマンド。PATH は devcontainer.json の remoteEnv で通している。
composer global require laravel/installer --no-interaction

# Codespaces が入れる GITHUB_TOKEN は、この1リポジトリだけに絞られた鍵。
# gh がこれを優先して使うため、06.md の gh repo create（別リポジトリの作成）が
# 権限不足で失敗する。ターミナルを開くたびに外し、06.md の gh auth login に任せる。
if ! grep -q "unset GITHUB_TOKEN" ~/.bashrc; then
  printf '\n# ハンズオン: 06.md の gh auth login を効かせるため\nunset GITHUB_TOKEN\n' >> ~/.bashrc
fi

php --version
composer --version
node --version
git --version
gh --version
laravel --version
