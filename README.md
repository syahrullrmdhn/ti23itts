# TI'23 Digital Yearbook

Website Angkatan TI 2023 ITTS - "How fun we are!"

## 🎨 Tech Stack

### Frontend
- **Nuxt.js 3** - Vue framework
- **Tailwind CSS** - Styling with bright, colorful design system
- **Custom Components** - Hero, Timeline, Episodes, Profile Cards

### Backend
- **Laravel 11** - REST API
- **SQLite** - Database (development)
- **Laravel Sanctum** - API Authentication

## 🚀 Quick Start

### Prerequisites
- Node.js (v18+)
- PHP 8.2+
- Composer

### Backend Setup

```bash
cd backend

# Install dependencies (already done)
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations and seed database
php artisan migrate:fresh --seed

# Start development server
php artisan serve
```

**API will run on:** `http://localhost:8000`

**Admin Login Credentials:**
- Email: `admin@ti23itts.my.id`
- Password: `password123`

### Frontend Setup

```bash
cd frontend

# Install dependencies
npm install

# Start development server
npm run dev
```

**Frontend will run on:** `http://localhost:3000`

## 📂 Project Structure

```
ti23itts/
├── frontend/                 # Nuxt.js application
│   ├── components/          # Vue components
│   │   ├── HeroSection.vue
│   │   ├── TimeBox.vue
│   │   ├── Episodes.vue
│   │   ├── ProfilAngkatan.vue
│   │   └── Footer.vue
│   ├── pages/               # Pages
│   │   └── index.vue
│   ├── assets/css/          # Global styles
│   ├── nuxt.config.ts       # Nuxt configuration
│   └── tailwind.config.js   # Tailwind configuration
│
└── backend/                 # Laravel API
    ├── app/
    │   ├── Models/          # Eloquent models
    │   │   ├── Student.php
    │   │   ├── Episode.php
    │   │   ├── Semester.php
    │   │   └── Timeline.php
    │   └── Http/Controllers/Api/
    │       ├── AuthController.php
    │       ├── StudentController.php
    │       ├── EpisodeController.php
    │       ├── SemesterController.php
    │       └── TimelineController.php
    ├── database/
    │   ├── migrations/      # Database migrations
    │   └── seeders/         # Database seeders
    └── routes/
        └── api.php          # API routes
```

## 🎯 Features

### Public Pages (No Auth Required)
- **Hero Section** - Eye-catching landing with TI'23 Insiders branding
- **The Time Box** - Interactive timeline showing semester journey
- **Hall of Shame** - Netflix-style carousel of legendary moments
- **Cast & Crew** - Profile cards with hover effects (normal/aib photos)

### Admin Dashboard (Auth Required)
- Login/Logout with Laravel Sanctum
- CRUD Students (with photo uploads)
- CRUD Episodes/Moments
- CRUD Semesters
- Update Timeline statistics

## 🔌 API Endpoints

### Public Endpoints
```
GET /api/students       - Get all students
GET /api/episodes       - Get all episodes
GET /api/timeline       - Get timeline with semesters
```

### Protected Endpoints (Requires Auth Token)
```
POST   /api/login                    - Admin login
POST   /api/logout                   - Admin logout
GET    /api/me                       - Get authenticated user

POST   /api/students                 - Create student
PUT    /api/students/{id}            - Update student
DELETE /api/students/{id}            - Delete student

POST   /api/episodes                 - Create episode
PUT    /api/episodes/{id}            - Update episode
DELETE /api/episodes/{id}            - Delete episode

POST   /api/semesters                - Create semester
PUT    /api/semesters/{id}           - Update semester
DELETE /api/semesters/{id}           - Delete semester

POST   /api/timeline                 - Create/update timeline
```

## 🎨 Design System

### Colors
- **Bright Yellow**: `#FFD93D`
- **Bright Blue**: `#6BCF7F`
- **Mint Green**: `#A8E6CF`
- **Fun Pink**: `#FF6B9D`
- **Soft Purple**: `#C3AED6`
- **Vibrant Orange**: `#FF8C42`

### Typography
- **Display Font**: Poppins (Bold, Black)
- **Body Font**: Inter

### UI Principles
- White/off-white backgrounds
- Bright, playful colors
- Smooth animations and transitions
- Mobile-first responsive design
- Netflix-style carousels

## 📝 Sample Data

The database seeder includes:
- 1 Admin user
- 6 Semesters with lecturer data
- 5 Legendary episodes (Monkey Business, Leaked Jidat, etc.)
- 5 Sample students
- Timeline statistics (45 initial → 38 current)

## 🔧 Development Notes

### Frontend Environment Variables
Create `.env` in frontend directory:
```
API_BASE_URL=http://localhost:8000/api
```

### Backend CORS Configuration
Already configured in `.env`:
```
SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DOMAIN=localhost
```

## 📱 Mobile Responsive
All components are fully responsive with mobile-first design approach.

## 🚀 Deployment (Production)

### Database Migration
When deploying to production:
1. Update `.env` to use MySQL/PostgreSQL
2. Run migrations: `php artisan migrate`
3. Seed database: `php artisan db:seed`

### Asset Optimization
- Use WebP format for images
- Enable Laravel caching
- Build Nuxt for production: `npm run build`

## 👥 Default Admin Access
- **Email**: admin@ti23itts.my.id
- **Password**: password123

**⚠️ Change this password in production!**

---

Made with 💖 by TI'23 ITTS
