# Eコマースウェブサイト (PHP MVC プロジェクト)

PHPのModel-View-Controller（MVC）アーキテクチャパターンに基づいて構築された、フル機能のEコマースウェブサイトです。DockerとDocker Composeを使用して完全にコンテナ化されており、簡単なセットアップとデプロイが可能です。

## 📋 目次

- [機能概要](#機能概要)
- [技術スタック](#技術スタック)
- [セットアップ手順](#セットアップ手順)
- [プロジェクト構造](#プロジェクト構造)
- [主要機能](#主要機能)
- [管理画面](#管理画面)
- [開発環境](#開発環境)
- [ライセンス](#ライセンス)

## 🎯 機能概要

本プロジェクトは、以下のEコマース機能をサポートしています：

### 顧客向け機能
- **ユーザー認証**: ログイン、新規登録、パスワード管理
- **商品カタログ**: 商品一覧、検索、フィルタリング、詳細表示
- **ショッピングカート**: 商品追加、数量変更、削除
- **注文処理**: チェックアウト、注文確認、注文履歴
- **アカウント管理**: プロフィール編集、注文履歴確認

### 管理者向け機能
- **ダッシュボード**: 売上統計、注文状況、在庫管理
- **商品管理**: 商品の追加、編集、削除、在庫管理
- **注文管理**: 注文一覧、詳細確認、ステータス更新
- **顧客管理**: 顧客情報の確認、管理
- **コンテンツ管理**: About Us、Q&Aページの動的編集（TinyMCE統合）
- **ニュース管理**: お知らせ・ニュースの投稿・編集
- **プロモーション管理**: クーポン・セールの設定

## 🛠 技術スタック

### バックエンド
- **言語**: PHP 8.x (Pure PHP、フレームワーク不使用)
- **アーキテクチャ**: Model-View-Controller (MVC) パターン
- **データベース**: MySQL 8.0
- **データアクセス**: PDO (PHP Data Objects)

### フロントエンド
- **マークアップ**: HTML5
- **スタイリング**: CSS3、Bootstrap 5
- **JavaScript**: Vanilla JavaScript
- **リッチテキストエディタ**: TinyMCE 8

### インフラストラクチャ
- **コンテナ化**: Docker、Docker Compose
- **Webサーバー**: Apache/Nginx (Dockerコンテナ内)
- **バージョン管理**: Git

## 🚀 セットアップ手順

### 前提条件

以下のソフトウェアがインストールされている必要があります：

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (Docker Engine 20.10以上)
- [Docker Compose](https://docs.docker.com/compose/install/) (通常、Docker Desktopに含まれています)
- Git

### インストール手順

#### 1. リポジトリのクローン

```bash
git clone https://github.com/vietlecd/Ecommerce_Website.git
cd Ecommerce_Website
```

#### 2. 環境変数の設定（オプション）

必要に応じて、`.env`ファイルを作成して環境変数を設定します：

```bash
cp .env.example .env
```

#### 3. Dockerコンテナのビルドと起動

```bash
docker-compose up -d --build
```

このコマンドは以下を実行します：
- Dockerイメージのビルド
- コンテナの作成と起動（PHP、MySQL、その他のサービス）
- バックグラウンドモードでの実行

#### 4. データベースのセットアップ

##### マイグレーションの実行

データベーステーブルを作成します：

```bash
./bin/migrate.sh all
```

または、個別のマイグレーションファイルを実行：

```bash
./bin/migrate.sh 003_create_site_contents_table.sql
```

##### シーダーの実行（オプション）

サンプルデータを投入します：

```bash
./bin/seed.sh all
```

#### 5. アプリケーションへのアクセス

すべてのコンテナが起動したら、ブラウザで以下のURLにアクセスできます：

- **フロントエンド**: http://localhost:8080
- **管理画面**: http://localhost:8080/index.php?controller=adminDashboard&action=dashboard

### デフォルト認証情報

**管理者アカウント**（シーダー実行後）:
- ユーザー名: `admin`
- パスワード: （シーダーファイルを確認してください）

## 📁 プロジェクト構造

```
Ecommerce_Website/
├── assets/                    # 静的リソース
│   ├── config/               # 設定ファイル
│   │   └── mysql/            # データベース設定
│   │       ├── migrations/   # マイグレーションファイル
│   │       └── seeders/      # シーダーファイル
│   ├── css/                  # スタイルシート
│   ├── images/               # 画像ファイル
│   └── js/                   # JavaScriptファイル
├── bin/                      # スクリプト
│   ├── migrate.sh           # マイグレーション実行スクリプト
│   └── seed.sh              # シーダー実行スクリプト
├── controllers/              # コントローラー（MVC）
│   ├── AboutController.php
│   ├── AdminContentController.php
│   ├── AdminDashboardController.php
│   ├── AdminProductController.php
│   ├── AuthController.php
│   ├── HomeController.php
│   ├── ProductsController.php
│   └── QnaController.php
├── models/                   # モデル（MVC）
│   ├── ContentModel.php
│   ├── Database.php
│   ├── ProductModel.php
│   └── ...
├── views/                    # ビュー（MVC）
│   ├── admin/               # 管理画面ビュー
│   │   ├── components/      # 共通コンポーネント
│   │   ├── content/         # コンテンツ管理ビュー
│   │   └── pages/           # 各ページビュー
│   ├── components/          # フロントエンド共通コンポーネント
│   ├── errors/              # エラーページ
│   └── pages/               # フロントエンドページ
├── logs/                    # ログファイル
├── Dockerfile               # PHPアプリケーション用Dockerfile
├── docker-compose.yml       # Docker Compose設定
├── index.php                # エントリーポイント
└── README.md               # このファイル
```

## ✨ 主要機能

### 商品管理システム
- 商品のCRUD操作（作成、読み取り、更新、削除）
- カテゴリー別商品表示
- 価格・在庫管理
- 商品画像アップロード

### 注文管理システム
- 注文の作成と処理
- 注文ステータスの管理
- 注文履歴の確認
- 配送管理

### コンテンツ管理システム（CMS）
- **動的コンテンツ編集**: About Us、Q&Aページを管理画面から編集可能
- **リッチテキストエディタ**: TinyMCE 8による高度な編集機能
- **データベース駆動**: コンテンツをデータベースに保存

### ユーザー管理
- ユーザー登録・認証
- プロフィール管理
- 権限管理（一般ユーザー・管理者）

## 🔐 管理画面

管理画面では、以下の機能にアクセスできます：

1. **ダッシュボード**: 売上統計、注文状況の概要
2. **商品管理**: 商品の追加、編集、削除
3. **注文管理**: 注文の確認、ステータス更新
4. **顧客管理**: 顧客情報の確認
5. **ニュース管理**: お知らせの投稿・編集
6. **プロモーション管理**: クーポン・セールの設定
7. **コンテンツ管理**: About Us、Q&Aページの編集

### 管理画面へのアクセス

```
http://localhost:8080/index.php?controller=adminDashboard&action=dashboard
```

管理者権限が必要です。

## 🧪 開発環境

### 開発用コマンド

#### データベースのリセット

```bash
./bin/reset_db.sh
```

#### マイグレーションの状態確認

```bash
./bin/migrate.sh --status
```

#### ログの確認

```bash
docker-compose logs -f web
```

### デバッグ

- PHPエラーログ: `logs/errors.log`
- Dockerコンテナログ: `docker-compose logs`

## 📝 データベーススキーマ

主要なテーブル：

- `member`: ユーザー情報
- `shoes`: 商品情報
- `order`: 注文情報
- `category`: 商品カテゴリー
- `site_contents`: 動的コンテンツ（About Us、Q&A）
- `qna`: Q&A項目
- `news`: ニュース・お知らせ

詳細は`assets/config/mysql/migrations/`を参照してください。

## 🔒 セキュリティ機能

- SQLインジェクション対策（PDOプリペアドステートメント）
- XSS対策（HTMLエスケープ）
- セッション管理
- 管理者認証・認可
- CSRF対策（推奨: 今後実装予定）

## 🤝 コントリビューション

プロジェクトへの貢献を歓迎します。以下の手順に従ってください：

1. このリポジトリをフォーク
2. 機能ブランチを作成 (`git checkout -b feature/AmazingFeature`)
3. 変更をコミット (`git commit -m 'Add some AmazingFeature'`)
4. ブランチにプッシュ (`git push origin feature/AmazingFeature`)
5. プルリクエストを作成

## 📄 ライセンス

このプロジェクトは教育目的で作成されています。

## 👥 開発者

- **Viet Le** - [GitHub](https://github.com/vietlecd)

## 🙏 謝辞

- Bootstrap 5
- TinyMCE
- Mazer Admin Template
- Font Awesome

## 📞 サポート

問題が発生した場合や質問がある場合は、GitHubのIssuesセクションでお知らせください。

---

**最終更新**: 2025年11月
