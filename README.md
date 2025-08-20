# ImagePostingBBS

PHP + Nginx を Docker で動かす、**画像投稿が可能な掲示板**です。  
（Stack: PHP / Nginx / Docker Compose）

<p align="center">
  <a href="https://github.com/Stone5656/ImagePostingBBS/blob/main/LICENSE">
    <img src="https://img.shields.io/badge/License-MIT-informational?style=for-the-badge" alt="License: MIT">
  </a>
</p>

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

* 停止/削除:

```bash
docker compose down
```

## 開発メモ

* 画像の保存先や最大サイズ、拡張子制限などは、必要に応じて`public/`配下のPHPやNginx設定（`nginx/conf.d/`）を調整してください。
* Docker以外での簡易確認は `php -S 127.0.0.1:8080 -t public` でも可能ですが、本番想定の挙動はNginx経由でご確認ください。

## ディレクトリ構成DME.md` にコピペできるセクション」を出力します。
下を `README.md` の末尾などに貼り付ければ、MySQLへの接続方法とテーブル作成方法がまとまります。

---

## MySQL の利用方法

### コンテナに入って MySQL に接続
```bash
docker compose exec mysql mysql
```

### データベースを選択

```mysql
USE example_db;
```

### 掲示板エントリ用テーブルの作成

```mysql
CREATE TABLE `bbs_entries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `body` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `image_filename` TEXT DEFAULT NULL
);
```

### 動作確認

```mysql
INSERT INTO bbs_entries (body, image_filename)
VALUES ('はじめての投稿です', 'hello.jpg');

SELECT * FROM bbs_entries;
```

---

この形なら**DDL を別ファイルに用意しなくても、README だけ見れば試せる**ようになります。  
必要なら「テーブル削除方法」や「volumeを消して初期化する方法」も合わせて追記できますが、どうしますか？
```


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

| Web | Language/Runtime | Container/Orchestration |
|---|---|---|
| ![Nginx](https://img.shields.io/badge/Nginx-009639?style=for-the-badge&logo=nginx&logoColor=white) | ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white) | ![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white) ![Compose](https://img.shields.io/badge/Compose-v2-192133?style=for-the-badge&logo=docker&logoColor=white) |

<!-- skillicons.dev を使う場合の例（見た目を変えたいときに切替）：
[![Tech](https://skillicons.dev/icons?i=php,nginx,docker)](https://skillicons.dev)
-->

## ライセンス

本ソフトウェアは **MIT License** で配布されます。詳細は [`LICENSE`](./LICENSE) を参照してください。

