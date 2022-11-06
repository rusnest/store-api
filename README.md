# Ý tưởng:
### Mô tả: 
- Đây là một hệ thống quản lý kho hàng cho một siêu thị.
- Người dùng hướng tới là những người quản lý ở siêu thị và những người đứng thu tiền ở quầy.
- Người dùng khác có thể là các cửa hàng tạp hoá.

### Các tính năng cơ bản
- register/login/logout
- crud product
- crud customer

### Database
#### Table
1. Table users
2. Table products
3. Table customers
4. Table shops
5. Table staffs

#### Relationship
1. users 1->n shops
2. shops 1->n products, customers, staffs

# Cài đặt
1. Copy .env
```
cp .env.example .env
```
2. Run api
```
docker compose up -d
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed --class=DevelopSeeder
```
