# Coachtechフリマ 					

これはある企業が開発した独自のフリマアプリです。

## アプリケーションURL				
				
- 開発環境：http://localhost/				
- phpMyAdmin：http://localhost:8080/				
				
## 環境構築				
				
1. リポジトリをクローン				
```bash				
git clone <リポジトリのURL>				
cd <リポジトリ名>				
```				
2. Dockerコンテナのビルド				
```bash				
docker-compose up -d --build				
```				
3. コンテナに入る				
```bash				
docker-compose exec php bash				
```				
4. パッケージをインストール				
```bash				
composer install				
```				
5. 環境ファイルを設定				
```bash				
cp .env.example .env
```
```bash				
php artisan key:generate				
```
※環境変数を必要に応じて変更してください
```
//前略

DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
+ DB_HOST=mysql
DB_PORT=3306
- DB_DATABASE=laravel
- DB_USERNAME=root
- DB_PASSWORD=
+ DB_DATABASE=laravel_db
+ DB_USERNAME=laravel_user
+ DB_PASSWORD=laravel_pass

// 後略
```


6. データベースのマイグレーションとシーディング				
```bash				
php artisan migrate --seed				
```						
## 使用技術(実行環境)				
				
- PHP: 7.4.9				
- Nginx: 1.21.1				
- Laravel: 8.75				
- MySQL: 8.0.26				
				
## ER図				

<img width="802" alt="Screenshot 2024-12-29 at 20 27 50" src="https://github.com/user-attachments/assets/9e4e4ca2-0ec9-46be-abaa-d2c977dd3710" />
				
## 主要ページ				
				
- 商品一覧画面（トップ画面）: /
- 商品一覧画面（トップ画面）_マイリスト: /?tab=mylist
- 会員登録画面: /register
- ログイン画面: /login
- 商品詳細画面: /item/:item_id
- 商品購入画面: /purchase/:item_id
- 住所変更ページ: /purchase/address/:item_id
- 商品出品画面: /sell
- プロフィール画面: /mypage
- プロフィール編集画面: /mypage/profile
- プロフィール画面_購入した商品一覧: /mypage?tab=buy
- プロフィール画面_出品した商品一覧: /mypage?tab=sell
