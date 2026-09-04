# 補足 インストールがうまくいかないとき（Codespaces）

このページは、[03. インストール・レシピ](./03.md) がどうしても通らなかった方のための代わりの道です。
自分から選ぶ必要はありません。当日メンターから案内されたら開いてください。

## これは何？

GitHub Codespaces（コードスペース）という仕組みを使って、開発環境をブラウザの中に用意します。

自分のパソコンには何もインストールしません。ブラウザの中に VS Code とターミナルが開き、
PHP も Node.js も最初から入った状態で始められます。

| | 03（通常） | このページ（Codespaces） |
|---|---|---|
| インストール | 自分のパソコンに入れる | 不要 |
| 必要なもの | パソコンの管理者権限 | GitHubアカウントとネット接続 |
| 使う場所 | パソコンの中 | ブラウザの中 |
| ネットが切れたら | 作業は続けられる | 作業が止まる |

会社から支給されたパソコンで管理者権限がない場合や、セキュリティソフトにインストールを止められる場合など、
03 の手順ではどうにもならないことがあります。そういうときの逃げ道です。

> ⚠️ 04 以降のガイドは、そのまま使えます。
> 読み替えが必要なのは、このページの最後に書いてある3か所だけです。

## 用意されているもの

このハンズオン用に、以下が入った環境を用意してあります。バージョンは 03 と同じです。

| ツール | バージョン |
|--------|-----------|
| PHP | 8.4 |
| Composer | 2 |
| Laravel インストーラー | 5 |
| Node.js | 22 (LTS) |
| Git | 2 |
| GitHub CLI（`gh`） | 2 |

## Step 1 GitHubアカウントを作る

すでに持っている方は Step 2 へ進んでください。

1. [https://github.com/signup](https://github.com/signup) を開く
2. メールアドレス・パスワード・ユーザー名を入力する
3. 届いた確認コードを入力する

ユーザー名は後から変えられます。迷ったら好きな名前で大丈夫です。

> 💡 ここで作ったアカウントは、06 でコードを保管するときにもそのまま使います。

## Step 2 Codespace を立ち上げる

1. [https://github.com/kou-tech/seb-hands-on](https://github.com/kou-tech/seb-hands-on) を開く
2. 緑色の「Code」ボタンをクリック
3. 「Codespaces」タブを選ぶ
4. 「Create codespace on main」をクリック

しばらく待つと、ブラウザの中に VS Code が開きます。初回は2〜4分ほどかかります。

> ⚠️ 画面が開いても、下のターミナルで準備作業が続いています。
> `laravel --version` のような表示が流れて止まるまで待ってください。ここで先に進むとコマンドが見つかりません。

## Step 3 準備できたか確認する

画面の下半分がターミナルです。見当たらないときは、上のメニューから
「Terminal」→「New Terminal」を選ぶと開きます。

以下を1つずつ実行してください。

```bash
php --version
```

```bash
composer --version
```

```bash
node --version
```

```bash
git --version
```

```bash
laravel --version
```

| コマンド | 期待する表示 |
|---------|------------|
| `php --version` | `PHP 8.4.x` |
| `composer --version` | `Composer version 2.x.x` |
| `node --version` | `v22.x.x` |
| `git --version` | `git version 2.x.x` |
| `laravel --version` | `Laravel Installer 5.x.x` |

すべて表示されたら準備完了です。[04. ブログを作ろう](./04.md) に進んでください。

> ⚠️ 04 の Step 1 が終わったら、このページの「04 以降の読み替え ①」に戻ってきてください。
> Codespaces では1回だけURLの設定が必要です。これを飛ばすと、デザインが当たらずリンクも動きません。

> メンター視点
> - `command not found` が出たら、Step 2 の準備作業がまだ終わっていない可能性が高いです。1分ほど待ってターミナルを開き直してください
> - それでも出ない場合は、画面左下の緑色の部分をクリック →「Rebuild Container」でやり直せます

## 04 以降の読み替え

4か所だけ、ガイドの書き方と画面が違います。
①は必ず必要な作業です。②〜④は読み替えるだけです。

### ① プロジェクトを作った直後に、URLの設定をする

`04.md` の Step 1 で `laravel new blog-app` が終わったら、続けてこの作業をしてください。

Codespaces では、ブラウザとアプリの間に GitHub の転送がはさまります。
そのままだと Laravel は自分のURLを `http://localhost:8000` だと思い込み、
CSS が読み込まれず、リンクもすべて自分のパソコンの中を指してしまいます。

#### 1-1. 自分のURLを .env に書く

`blog-app` フォルダの中で、以下をコピー＆ペーストしてください。

```bash
sed -i "s|^APP_URL=.*|APP_URL=https://$CODESPACE_NAME-8000.$GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN|" .env
```

自分のURLは Codespaces が教えてくれるので、手で打つ必要はありません。
確認したいときは以下を実行すると、書き込まれた値が表示されます。

```bash
grep APP_URL .env
```

#### 1-2. app/Providers/AppServiceProvider.php を書き換える

`app/Providers/AppServiceProvider.php` を開いて、2か所を書き換えます。

変更前

```php
use Illuminate\Support\ServiceProvider;
```

変更後

```php
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
```

変更前

```php
    public function boot(): void
    {
        //
    }
```

変更後

```php
    public function boot(): void
    {
        // Codespaces から開いたとき、リンクとCSSのURLを転送URLに合わせる
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceRootUrl(config('app.url'));
            URL::forceScheme('https');
        }
    }
```

これで、CSS もリンクも正しいURLで組み立てられるようになります。

> 💡 07 で公開するときは、この設定は自動的に働かなくなります。
> 公開サーバー側では `07.md` の `trustProxies` が同じ役割をします。そのまま進めて大丈夫です。

> メンター視点
> - この作業を飛ばすと、画面は出るのにデザインが当たらず、リンクを押すと「接続できません」になります
> - ブラウザの検証ツール（Console）に `localhost:8000` へのエラーが並んでいたら、ここの作業漏れです
> - `sed` の行は blog-app フォルダの中で実行してください。`pwd` で確認できます

### ② ブログを開くとき

ガイドには `http://localhost:8000` を開くと書いてあります。

Codespaces では、`php artisan serve` を実行すると、
画面の右下に「ポート 8000 で実行中のアプリケーションが利用できます」という案内が出ます。
その「ブラウザーで開く」を押してください。自分専用のURLで新しいタブが開きます。

案内が消えてしまったときは、画面下の「ポート」タブを開いて、8000 番の地球アイコンをクリックします。

> 💡 このURLは自分にしか見えません。他の人に見せられるURLは 07 で手に入ります。

### ③ ファイルを開くとき

VS Code のインストール（03 の ③）は不要です。ブラウザの中の VS Code をそのまま使ってください。
左側のファイル一覧の使い方は、パソコンに入れた VS Code と同じです。

### ④ 06 でコードをアップロードするとき

`06.md` の Step 2（GitHub CLI のインストール）は不要です。`gh` は最初から入っています。
Step 3（`git config`）から始めてください。

> ⚠️ もし Step 6 の `gh repo create` で「権限がない」という意味の英語のエラーが出たら、
> 以下を実行してから `gh auth login` をやり直してください。

```bash
unset GITHUB_TOKEN
```

> メンター視点
> - Codespaces には「このリポジトリだけ触れる鍵」が最初から置かれていることがあり、これが `gh repo create` を邪魔します
> - 環境側で無効にしてありますが、当日エラーが出たら上のコマンドで確実に外せます

## 知っておいてほしいこと

### 使い終わったら止める

無料で使える時間は、1人あたり月120時間ぶんです（このハンズオンの1日は12時間ぶんを使います）。
使い終わったら止めておくと、残りを減らさずに済みます。

止め方は、[https://github.com/codespaces](https://github.com/codespaces) を開いて、
右側の「…」→「Stop codespace」です。ブラウザのタブを閉じただけでも、しばらくすると自動で止まります。

クレジットカードの登録は不要です。無料ぶんを使い切ると、課金されるのではなく使えなくなります。

### 続きは家でもできる

同じURLを開けば、当日の続きから再開できます。書いたコードもそのまま残っています。

30日間まったく使わないと自動で削除されるので、続けたい方は 06 で GitHub にアップロードしておいてください。
アップロードしてあれば、環境が消えてもコードは残ります。

### ネットが切れたとき

Codespaces はインターネット越しに動いています。会場のWi-Fiが切れると作業も止まります。
つながり直すと、多くの場合そのまま続きから再開できます。慌てて閉じないでください。

---

準備ができたら、04 に進みましょう。

次のガイド: [ブログを作ろう](./04.md)
