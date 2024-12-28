# Coachtechフリマ 					
				
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
				
<img width="645" alt="ER図" src="https://github.com/user-attachments/assets/fb1c0a34-4467-4114-8b77-b36d5b8ca081">				

## 機能一覧				
				
1. 会員登録				
- Laravelの認証機能を利用				
				
2. ログイン/ログアウト				
				
3. 勤務管理				
- 勤務開始				
- 日を跨いだ時点で翌日の出勤操作に切り替える				
- 勤務終了				
- 休憩開始				
- 1日で何度も休憩が可能				
- 休憩終了				
				
4. 日付別勤怠情報取得				
- ページネーション（5件ずつ取得）				
				
## 主要ページ				
				
- `/` : 打刻ページ				
- `/register` : 会員登録ページ				
- `/login` : ログインページ				
- `/attendance` : 日付別勤怠ページ
- `/users` : ユーザー別勤怠ページ			
				
