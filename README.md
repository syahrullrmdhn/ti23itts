<div align="center">
  
# 🎓 TI'23 Digital Yearbook

### *"How Fun We Are!"*

**Digital Yearbook Angkatan Teknik Informatika 2023 ITTS**

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Nuxt](https://img.shields.io/badge/Nuxt-3-00DC82?style=for-the-badge&logo=nuxt.js&logoColor=white)](https://nuxt.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![SQLite](https://img.shields.io/badge/SQLite-3-003B57?style=for-the-badge&logo=sqlite&logoColor=white)](https://sqlite.org)

[Live Demo](#) • [Documentation](./ADMIN_GUIDE.md) • [Report Bug](../../issues)

---

</div>

## ✨ Tentang Project

Website **ti23itts.my.id** adalah digital yearbook interaktif untuk angkatan Teknik Informatika 2023 ITTS. Didesain dengan konsep *bright, playful, dan colorful* yang jauh dari kesan website kampus yang kaku dan membosankan. 

Terinspirasi dari platform streaming seperti Netflix dan culture page perusahaan modern, website ini menjadi tempat nongkrong digital yang menyimpan semua memori angkatan—dari momen keren hingga aib legendaris.

<div align="center">

### 🎨 **Design Philosophy**

**Bright** • **Happy** • **Playful** • **Colorful**

</div>

---

## 🚀 Features

### 🌟 Public Features
- **Hero Section** - Landing page eye-catching dengan branding TI'23 Insiders
- **The Time Box** - Timeline interaktif perjalanan semester dengan statistik real-time
- **Hall of Shame** - Carousel horizontal ala Netflix untuk momen legendaris
- **Cast & Crew** - Profile cards dengan hover effect (foto normal ⇄ foto aib)
- **Mobile Responsive** - Perfect di semua device

### 🔐 Admin Dashboard
- **Authentication** - Secure login dengan Laravel Sanctum
- **Student Management** - CRUD mahasiswa dengan photo upload
- **Episode Management** - Kelola momen legendaris angkatan
- **Timeline Management** - Update semester & statistik mahasiswa
- **Semester Management** - Manage data dosen per semester

---

## 🛠️ Tech Stack

<table>
<tr>
<td width="50%" valign="top">

### **Frontend**
```yaml
Framework: Nuxt.js 3
Styling: Tailwind CSS
Fonts: Poppins (Display), Inter (Body)
Icons: Emoji-based
Images: Unsplash API
```

**Design System:**
- 🟡 Bright Yellow `#FFD93D`
- 🔵 Bright Blue `#6BCF7F`
- 🟢 Mint Green `#A8E6CF`
- 🔴 Fun Pink `#FF6B9D`
- 🟣 Soft Purple `#C3AED6`
- 🟠 Vibrant Orange `#FF8C42`

</td>
<td width="50%" valign="top">

### **Backend**
```yaml
Framework: Laravel 11
Database: SQLite (dev) → MySQL/PostgreSQL (prod)
API: RESTful API
Auth: Laravel Sanctum
Storage: Local Storage
```

**Architecture:**
- Headless CMS approach
- API-first design
- Token-based authentication
- Clean code & PSR standards

</td>
</tr>
</table>

---

## 📦 Installation

### Prerequisites
```bash
Node.js ≥ 18.x
PHP ≥ 8.2
Composer ≥ 2.x
```

### 🔧 Backend Setup

```bash
# Navigate to backend directory
cd backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations & seed database
php artisan migrate:fresh --seed

# Start development server
php artisan serve
```

**🔗 API:** `http://localhost:8000`

### 🎨 Frontend Setup

```bash
# Navigate to frontend directory
cd frontend

# Install dependencies
npm install

# Start development server
npm run dev
```

**🔗 Frontend:** `http://localhost:3000`

---

## 🔑 Default Credentials

```
Email: admin@ti23itts.my.id
Password: password123
```

> ⚠️ **Important:** Change this password in production!

---

## 📂 Project Structure

```
ti23itts/
├── 📁 frontend/                    # Nuxt.js Application
│   ├── 📁 assets/css/             # Global styles
│   ├── 📁 components/             # Vue components
│   │   ├── HeroSection.vue
│   │   ├── TimeBox.vue
│   │   ├── Episodes.vue
│   │   ├── ProfilAngkatan.vue
│   │   └── Footer.vue
│   ├── 📁 pages/
│   │   └── index.vue              # Main page
│   ├── nuxt.config.ts             # Nuxt configuration
│   └── tailwind.config.js         # Tailwind configuration
│
├── 📁 backend/                     # Laravel API
│   ├── 📁 app/
│   │   ├── 📁 Models/
│   │   │   ├── Student.php
│   │   │   ├── Episode.php
│   │   │   ├── Semester.php
│   │   │   └── Timeline.php
│   │   └── 📁 Http/Controllers/Api/
│   │       ├── AuthController.php
│   │       ├── StudentController.php
│   │       ├── EpisodeController.php
│   │       ├── SemesterController.php
│   │       └── TimelineController.php
│   ├── 📁 database/
│   │   ├── 📁 migrations/
│   │   └── 📁 seeders/
│   └── 📁 routes/
│       └── api.php
│
├── 📄 README.md                    # You are here
├── 📄 ADMIN_GUIDE.md              # Admin documentation
└── 📄 project-brief.md            # Original project brief
```

---

## 🔌 API Endpoints

### Public Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/students` | Get all students |
| `GET` | `/api/episodes` | Get all episodes |
| `GET` | `/api/timeline` | Get timeline with semesters |

### Protected Endpoints (Requires Auth)
| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/login` | Admin login |
| `POST` | `/api/logout` | Admin logout |
| `GET` | `/api/me` | Get authenticated user |
| `POST` | `/api/students` | Create student |
| `PUT` | `/api/students/{id}` | Update student |
| `DELETE` | `/api/students/{id}` | Delete student |
| `POST` | `/api/episodes` | Create episode |
| `PUT` | `/api/episodes/{id}` | Update episode |
| `DELETE` | `/api/episodes/{id}` | Delete episode |
| `POST` | `/api/semesters` | Create semester |
| `PUT` | `/api/semesters/{id}` | Update semester |
| `DELETE` | `/api/semesters/{id}` | Delete semester |
| `POST` | `/api/timeline` | Update timeline stats |

> 📖 Detailed API documentation available in [ADMIN_GUIDE.md](./ADMIN_GUIDE.md)

---

## 🎨 Design System

### Color Palette
<div align="center">

| Color | Hex | Usage |
|-------|-----|-------|
| ![#FFD93D](https://via.placeholder.com/15/FFD93D/000000?text=+) Bright Yellow | `#FFD93D` | Primary accents |
| ![#6BCF7F](https://via.placeholder.com/15/6BCF7F/000000?text=+) Bright Blue | `#6BCF7F` | Success states |
| ![#A8E6CF](https://via.placeholder.com/15/A8E6CF/000000?text=+) Mint Green | `#A8E6CF` | Backgrounds |
| ![#FF6B9D](https://via.placeholder.com/15/FF6B9D/000000?text=+) Fun Pink | `#FF6B9D` | Interactive elements |
| ![#C3AED6](https://via.placeholder.com/15/C3AED6/000000?text=+) Soft Purple | `#C3AED6` | Highlights |
| ![#FF8C42](https://via.placeholder.com/15/FF8C42/000000?text=+) Vibrant Orange | `#FF8C42` | CTAs |

</div>

### Typography
- **Display Font:** Poppins (Bold, Black) - For headlines
- **Body Font:** Inter - For content

### Components
- Rounded corners (border-radius: 24px)
- Smooth transitions (300ms)
- Card hover effects with scale & shadow
- Gradient overlays for visual depth

---

## 📊 Sample Data

The database seeder includes:
- ✅ 1 Admin user
- ✅ 8 Students with Unsplash photos
- ✅ 5 Legendary episodes
- ✅ 6 Semesters with lecturer data
- ✅ Timeline statistics (45 initial → 38 current)

---

## 🚢 Deployment

### Database Migration (Production)

1. Update `.env` database configuration:
```env
DB_CONNECTION=mysql
DB_HOST=your-host
DB_PORT=3306
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

2. Run migrations:
```bash
php artisan migrate --force
php artisan db:seed --force
```

### Frontend Build

```bash
cd frontend
npm run build
npm run generate  # For static hosting
```

### Backend Optimization

```bash
cd backend
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

---

## 🐛 Troubleshooting

<details>
<summary><b>CORS Error</b></summary>

Make sure `SANCTUM_STATEFUL_DOMAINS` in `.env` includes your frontend URL:
```env
SANCTUM_STATEFUL_DOMAINS=localhost:3000,ti23itts.my.id
```
</details>

<details>
<summary><b>Authentication Failed</b></summary>

1. Clear Laravel cache: `php artisan config:clear`
2. Regenerate app key: `php artisan key:generate`
3. Re-seed database: `php artisan migrate:fresh --seed`
</details>

<details>
<summary><b>Image Upload Failed</b></summary>

1. Check storage permissions: `chmod -R 775 storage`
2. Create symbolic link: `php artisan storage:link`
3. Verify max upload size in `php.ini`
</details>

---

## 📝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is created for **TI'23 ITTS** internal use.

---

## 👥 Team

<div align="center">

**Made with 💖 by TI'23 ITTS**

*Angkatan Teknik Informatika 2023*  
*Institut Teknologi Telkom Surabaya*

[Website](https://ti23itts.my.id) • [GitHub](https://github.com/syahrullrmdhn/ti23itts)

---

### ⭐ Star this repo if you like it!

</div>
