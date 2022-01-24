# Api and admin panel

## Installing

### 1. PHP
* Tested on php-7.4.
### 2. MySQL
* Required MySQL 5.7
* Create database and user for server:
```sql
CREATE DATABASE yii2_db;
```
* or change the name of the database in the file:
```bash
common/config/main-local.php
```
### 3. Prepare project for work
* Install [composer](https://getcomposer.org/download/)
* Go to project path
* Do:
```bash
composer install
php yii migrate
```
* Account admin:
```bash
email:      admin@example.com
password:   tempPassword
```

### 4. API requests
```bash
    POST v1/auth/login      - login user                      'email' and 'password' are required 
    POST v1/auth/signup     - register a new user             'username', 'email' and 'password' are required
            
    POST v1/posts           - create a post.                  'title', 'accessToken' and 'content' are  required 
    GET v1/posts            - get all posts.                  'limit' and 'offset' are optional
    GET v1/user/posts       - get all posts for current user. 'accessToken' is required
                                                              'limit' and 'offset' are optional
```