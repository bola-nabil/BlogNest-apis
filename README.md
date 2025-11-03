# BlogNest-apis

A robust backend API for the BlogNest platform, providing endpoints for posts, authors, comments, and more.

---
## ✅ About  
BlogNest-apis is the backend part of the BlogNest ecosystem: a RESTful API (built with PHP / Laravel) that powers the blog frontend. It handles data models, authentication, authorization, routing, and integrations.

---
## 🧰 Tech Stack  
- PHP (Laravel framework)  
- MySQL (or any supported relational database)  
- Docker (Dockerfile included for containerization)  
- PHPUnit for automated testing  
- PHP Composer for dependency management

---
## 🚀 Installation & Setup

1. Clone the repository:
```
git clone https://github.com/bola-nabil/BlogNest-apis.git
```
2. Open Folder of Project
```
cd BlogNest-apis
```
3. Install PHP dependencies:
```
composer install
```
4. Generate the application key:
```
php artisan key:generate

```
5. Run migrations
```
php artisan migrate
```
6. Start the development server:
```
php artisan serve
```
