lign="center">
  <a href="https://github.com/Stone5656/ImagePostingBBS/blob/main/LICENSE">
    <img src="https://img.shields.io/badge/License-MIT-informational?style=for-the-badge" alt="License: MIT">
  </a>
</p>

# ImagePostingBBS

PHP + Nginx を Docker で動かす、**画像投稿が可能な掲示板**です。  
（Stack: PHP / Nginx / Docker Compose）

> リポジトリ: https://github.com/Stone5656/ImagePostingBBS

---

## 目次
- [特徴](#特徴)
- [クイックスタート（Docker Compose）](#クイックスタートdocker-compose)
- [開発メモ](#開発メモ)
- [ディレクトリ構成](#ディレクトリ構成)
- [技術スタック](#技術スタック)
- [ライセンス](#ライセンス)

---

## 特徴
- 画像を投稿できる簡易BBS
- コンテナ化（Docker Compose）でローカル起動が容易
- Nginx + PHP 構成（単一リポジトリ）

## クイックスタート（Docker Compose）
> 事前に Docker と Docker Compose v2 をインストールしてください。
```bash
git clone https://github.com/Stone5656/ImagePostingBBS.git
cd ImagePostingBBS
docker compose up -d --build
```

* アクセスURLは **`compose.yml` の `ports` 設定**に従います（例：`8080:80` → [http://localhost:8080）。](http://localhost:8080）。)
* 停止/削除:

```bash
docker compose down
```

## 開発メモ

* 画像の保存先や最大サイズ、拡張子制限などは、必要に応じて`public/`配下のPHPやNginx設定（`nginx/conf.d/`）を調整してください。
* Docker以外での簡易確認は `php -S 127.0.0.1:8080 -t public` でも可能ですが、本番想定の挙動はNginx経由でご確認ください。

## ディレクトリ構成

```
ImagePostingBBS/
├─ public/          # アプリ本体（エントリポイントや投稿処理）
├─ nginx/
│  └─ conf.d/      # サーバー設定（仮想ホスト等）
├─ Dockerfile       # コンテナビルド定義
├─ compose.yml      # Docker Compose v2
└─ LICENSE          # MIT License
```

## 技術スタック

> Shields.io のバッジで“ボックス風”に表示しています。必要に応じて追加・削除してください。
> \| Web | Language/Runtime | Container/Orchestration |
> \|---|---|---|
> \| ![Nginx](https://img.shields.io/badge/Nginx-009639?style=for-the-badge\&logo=nginx\&logoColor=white) | ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge\&logo=php\&logoColor=white) | ![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge\&logo=docker\&logoColor=white) ![Compose](https://img.shields.io/badge/Compose-v2-192133?style=for-the-badge\&logo=docker\&logoColor=white) |

<!-- skillicons.dev を使う場合の例（見た目を変えたいときに切替）：
[![Tech](https://skillicons.dev/icons?i=php,nginx,docker)](https://skillicons.dev)
-->

## ライセンス

本ソフトウェアは **MIT License** で配布されます。詳細は [`LICENSE`](./LICENSE) を参照してください。

---

### 参考リンク（README内の表現や起動方法の根拠）
- GitHubのMarkdown/HTMLの扱い（サニタイズや拡張仕様）:contentReference[oaicite:4]{index=4}  
- Docker Compose 公式（v2・既定ファイル名・基本操作）:contentReference[oaicite:5]{index=5}  
- Shields.io のロゴ指定（Simple Iconsのスラッグを`logo=`で）/ スタイル指定 :contentReference[oaicite:6]{index=6}  
- skillicons.dev（アイコン列の簡易表示）:contentReference[oaicite:7]{index=7}  
- リポジトリ自体の情報（MIT license / 説明）:contentReference[oaicite:8]{index=8}


