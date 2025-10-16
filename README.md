# 🚀 Laravel 11 Social Media MVP

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-blue.svg)](https://php.net)
[![Docker](https://img.shields.io/badge/Docker-Ready-brightgreen.svg)](https://docker.com)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-blue.svg)](https://postgresql.org)

Modern ijtimoiy tarmoq platformasi Laravel 11 asosida qurilgan, to'liq Docker muhitida sozlangan va production deployment uchun tayyor.

---

## ✨ Asosiy Xususiyatlar

- 🔐 **Autentifikatsiya** - Login, Register, Password Reset
- 📝 **Post Management** - Yaratish, Tahrirlash, O'chirish
- 💬 **Comments & Likes** - Real-time interaksiya
- 👥 **Follow System** - Foydalanuvchilarni kuzatish
- 🔔 **Notifications** - Real-time bildirishnomalar
- 💬 **Real-time Chat** - Jonli chat tizimi
- 🔍 **Full-text Search** - Meilisearch orqali tezkor qidiruv
- 📧 **Email System** - Mailpit bilan testing

---

## 🛠️ Texnologiyalar

### Backend
- **PHP 8.3** - Zamonaviy PHP versiyasi
- **Laravel 11** - Eng so'nggi Laravel framework
- **PostgreSQL 16** - Kuchli relational database
- **Redis 7** - Cache, Session va Queue management

### Frontend
- **Blade Templates** - Laravel native templating
- **Tailwind CSS** - Modern CSS framework
- **Alpine.js** - Yengil JavaScript framework
- **Livewire** - Reactive components (optional)

### DevOps
- **Docker** - Konteynerizatsiya
- **Docker Compose** - Multi-container orchestration
- **Nginx** - Web server
- **Supervisor** - Process management

### Qo'shimcha Servislar
- **Meilisearch** - Full-text search engine
- **Laravel Horizon** - Queue dashboard
- **Mailpit** - Email testing tool

---

## 📋 Talablar

- Docker Engine 20.10+
- Docker Compose 2.0+
- Git
- 4GB RAM (minimum)
- 10GB Disk space

---

## 🚀 O'rnatish

### 1. Repositoriyani Clone Qilish

```bash
git clone https://github.com/shoh-27/social-media.git
cd social-media
```

### 2. Environment Sozlash

```bash
# .env faylini nusxalash
cp .env.example .env

# .env faylini tahrirlash (ixtiyoriy)
nano .env
```

### 3. Docker Containerlarni Ishga Tushirish

```bash
# Barcha servicelarni build va start qilish
docker-compose up -d --build

# Container statusini tekshirish
docker-compose ps
```

### 4. Laravel Sozlash

```bash
# App containerga kirish
docker-compose exec app sh

# Composer dependencies o'rnatish
composer install

# Application key generatsiya qilish
php artisan key:generate

# Database migratsiya va seed
php artisan migrate --seed

# Storage link yaratish
php artisan storage:link

# Cache sozlash
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Exit
exit
```

### 5. Qo'shimcha Paketlar (ixtiyoriy)

```bash
docker-compose exec app sh

# Laravel Horizon (Queue dashboard)
composer require laravel/horizon
php artisan horizon:install

# Laravel Scout + Meilisearch
composer require laravel/scout meilisearch/meilisearch-php
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"

# Laravel Sanctum (API auth)
php artisan install:api

exit
```

---

## 🌐 Kirish

Browserda quyidagi URL'larni oching:

| Service | URL | Tavsif |
|---------|-----|--------|
| **Web App** | http://localhost:8080 | Asosiy dastur |
| **Horizon** | http://localhost:8080/horizon | Queue dashboard |
| **Mailpit** | http://localhost:8025 | Email testing |
| **Meilisearch** | http://localhost:7700 | Search dashboard |

---

## 📦 Docker Servislar

```yaml
Service       Container Name        Port    Status
─────────────────────────────────────────────────────
app           laravel_app           9000    ✅ Running
nginx         laravel_nginx         8080    ✅ Running
db            laravel_postgres      5432    ✅ Running
redis         laravel_redis         6379    ✅ Running
meilisearch   laravel_meilisearch   7700    ✅ Running
mailpit       laravel_mailpit       8025    ✅ Running
horizon       laravel_horizon       -       ✅ Running
queue         laravel_queue         -       ✅ Running
scheduler     laravel_scheduler     -       ✅ Running
```

---

## 🔧 Foydali Buyruqlar

### Docker Boshqaruv

```bash
# Barcha containerlarni ishga tushirish
docker-compose up -d

# Container loglarini ko'rish
docker-compose logs -f app

# Bitta serviceni restart qilish
docker-compose restart nginx

# Barcha containerlarni to'xtatish
docker-compose down

# Container va volumelarni o'chirish (⚠️ Ma'lumotlar o'chadi!)
docker-compose down -v
```

### Laravel Artisan

```bash
# Artisan buyruqlarini ishga tushirish
docker-compose exec app php artisan [command]

# Masalan:
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan tinker
docker-compose exec app php artisan queue:work
```

### Database Boshqaruv

```bash
# PostgreSQL'ga ulanish
docker-compose exec db psql -U postgres -d laravel

# Database backup
docker-compose exec db pg_dump -U postgres laravel > backup.sql

# Database restore
docker-compose exec -T db psql -U postgres -d laravel < backup.sql

# Migration rollback
docker-compose exec app php artisan migrate:rollback
```

### Cache Tozalash

```bash
# Barcha cache'ni tozalash
docker-compose exec app php artisan optimize:clear

# Yoki alohida:
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

### NPM/Node.js

```bash
# Frontend assets build qilish
docker-compose exec app npm install
docker-compose exec app npm run dev

# Production build
docker-compose exec app npm run build

# Watch mode (development)
docker-compose exec app npm run dev -- --watch
```

---

## 🧪 Testing

```bash
# PHPUnit testlarni ishga tushirish
docker-compose exec app php artisan test

# Feature tests
docker-compose exec app php artisan test --testsuite=Feature

# Unit tests
docker-compose exec app php artisan test --testsuite=Unit

# Test coverage
docker-compose exec app php artisan test --coverage
```

---

## 📁 Loyiha Strukturasi

```
├── docker-compose.yml       # Docker services konfiguratsiyasi
├── Dockerfile              # PHP/Laravel image
├── .env                    # Environment variables
├── .env.example            # Environment template
├── nginx/
│   └── default.conf        # Nginx konfiguratsiyasi
├── supervisor/
│   └── supervisord.conf    # Process manager
├── docker/
│   └── php/
│       └── php.ini         # PHP sozlamalari
├── src/                    # Laravel application
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── resources/
│   ├── routes/
│   └── storage/
└── README.md
```

---

## 🔒 Xavfsizlik

### Development Muhiti

- `.env` faylini **hech qachon** commit qilmang
- Default parollarni o'zgartiring
- `APP_DEBUG=true` faqat development'da

### Production Muhiti

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] SSL sertifikat o'rnating
- [ ] Kuchli parollar ishlating
- [ ] Firewall sozlang
- [ ] Regular backup oling
- [ ] `composer install --no-dev --optimize-autoloader`

---

## 🚀 Production Deployment

### 1. Server Tayyorlash

```bash
# VPS'da Docker o'rnatish (Ubuntu)
sudo apt update
sudo apt install docker.io docker-compose-v2 -y
sudo systemctl enable docker
sudo systemctl start docker
```

### 2. Loyihani Deploy Qilish

```bash
# Git clone
git clone https://github.com/shoh-27/social-media.git
cd social-media

# Production .env sozlash
cp .env.example .env
nano .env  # Production sozlamalarni kiriting

# Build va run
docker-compose -f docker-compose.prod.yml up -d --build
```

### 3. SSL Sertifikat (Let's Encrypt)

```bash
# Certbot o'rnatish
sudo apt install certbot python3-certbot-nginx -y

# SSL sertifikat olish
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

### 4. Optimization

```bash
# Laravel optimization
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
docker-compose exec app php artisan event:cache

# Composer optimization
docker-compose exec app composer install --optimize-autoloader --no-dev
```

---

## 🐛 Troubleshooting

### Container ishlamasa

```bash
# Loglarni tekshirish
docker-compose logs app
docker-compose logs nginx
docker-compose logs db

# Container restart
docker-compose restart app

# Rebuild
docker-compose up -d --build --force-recreate
```

### Permission xatoliklari

```bash
# Storage va cache permission
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R laravel:laravel storage bootstrap/cache
```

### Database connection xatolik

```bash
# Database'ni tekshirish
docker-compose exec db psql -U postgres -l

# .env faylini tekshiring:
# DB_HOST=db (container nomi)
# DB_PORT=5432
```

### Redis connection xatolik

```bash
# Redis'ni test qilish
docker-compose exec redis redis-cli ping

# .env faylini tekshiring:
# REDIS_HOST=redis (container nomi)
```

---

## 📚 Qo'shimcha Resurslar

- [Laravel Documentation](https://laravel.com/docs/11.x)
- [Docker Documentation](https://docs.docker.com)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)
- [Meilisearch Documentation](https://www.meilisearch.com/docs)
- [Laravel Horizon](https://laravel.com/docs/11.x/horizon)

---

## 🤝 Hissa Qo'shish

1. Fork qiling
2. Feature branch yarating (`git checkout -b feature/AmazingFeature`)
3. Commit qiling (`git commit -m 'Add some AmazingFeature'`)
4. Push qiling (`git push origin feature/AmazingFeature`)
5. Pull Request oching

---

## 📝 Changelog

### v1.0.0 (2025-10-13)
- ✨ Initial release
- 🔐 Authentication system
- 📝 Post management
- 💬 Comments & Likes
- 👥 Follow system
- 🔔 Notifications
- 💬 Real-time chat
- 🔍 Full-text search
- 🐳 Full Docker setup

---

## 👨‍💻 Muallif

**Your Name**
- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com
- LinkedIn: [Your Name](https://linkedin.com/in/yourprofile)

---

---

## 🙏 Minnatdorchilik

- [Laravel](https://laravel.com) - The PHP Framework
- [Docker](https://docker.com) - Containerization Platform
- [PostgreSQL](https://postgresql.org) - Database
- [Meilisearch](https://meilisearch.com) - Search Engine
- [Tailwind CSS](https://tailwindcss.com) - CSS Framework

---

## ⭐ Star History

Agar loyiha foydali bo'lsa, ⭐ star bering!

[![Star History Chart](https://api.star-history.com/svg?repos=username/laravel-social-media&type=Date)](https://star-history.com/#username/laravel-social-media&Date)

---

<div align="center">

**Made with ❤️ by [Your Name](https://github.com/yourusername)**

[Report Bug](https://github.com/username/laravel-social-media/issues) · [Request Feature](https://github.com/username/laravel-social-media/issues)

</div>
