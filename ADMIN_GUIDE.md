# 🎓 Admin Dashboard Guide - TI'23 Digital Yearbook

Panduan lengkap untuk mengelola konten website melalui Admin Dashboard.

---

## 🔐 Login ke Admin Dashboard

### Akses Login
Untuk mengakses admin dashboard, gunakan endpoint API:
```
POST http://localhost:8000/api/login
```

### Kredensial Default
```json
{
  "email": "admin@ti23itts.my.id",
  "password": "password123"
}
```

**Response:**
```json
{
  "user": {
    "id": 1,
    "name": "Admin TI23",
    "email": "admin@ti23itts.my.id"
  },
  "token": "1|xxxxxxxxxxxxxxxxxxxx"
}
```

Simpan `token` ini untuk digunakan di setiap request berikutnya dengan header:
```
Authorization: Bearer {token}
```

---

## 👥 Mengelola Data Mahasiswa (Students)

### Melihat Semua Mahasiswa
```bash
GET /api/students
```

### Menambah Mahasiswa Baru
```bash
POST /api/students
Authorization: Bearer {token}
Content-Type: multipart/form-data

Body:
- name: string (required) - Nama mahasiswa
- role: string (required) - Role/julukan (contoh: "Si Paling Rajin 📚")
- status: enum (required) - Aktif|Cuti|Alumni
- photo: file (optional) - Foto normal
- aib_photo: file (optional) - Foto aib (akan muncul saat hover)
- fun_fact: string (optional) - Fun fact tentang mahasiswa
```

**Contoh dengan cURL:**
```bash
curl -X POST http://localhost:8000/api/students \
  -H "Authorization: Bearer {token}" \
  -F "name=Budi Santoso" \
  -F "role=The Debugger 🐛" \
  -F "status=Aktif" \
  -F "fun_fact=Bisa fix bug apapun" \
  -F "photo=@/path/to/photo.jpg" \
  -F "aib_photo=@/path/to/aib.jpg"
```

### Mengupdate Data Mahasiswa
```bash
PUT /api/students/{id}
Authorization: Bearer {token}
```

### Menghapus Mahasiswa
```bash
DELETE /api/students/{id}
Authorization: Bearer {token}
```

---

## 🎬 Mengelola Episodes (Hall of Shame)

### Melihat Semua Episodes
```bash
GET /api/episodes
```

### Menambah Episode Baru
```bash
POST /api/episodes
Authorization: Bearer {token}
Content-Type: application/json

Body:
{
  "category": "ZOOM FAILS",
  "title": "Judul Episode",
  "icon": "🎯",
  "gradient_color": "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
  "short_description": "Deskripsi singkat untuk thumbnail",
  "full_description": "Cerita lengkap episode yang akan muncul di modal",
  "date": "Juni 2024",
  "participants": "Nama peserta yang terlibat"
}
```

**Kategori yang Tersedia:**
- `ZOOM FAILS` - Kejadian lucu saat Zoom
- `ZOOM MYSTERY` - Misteri yang belum terpecahkan
- `DRAMA` - Drama angkatan
- `HALL OF FAME` - Momen membanggakan
- Custom kategori lainnya

**Contoh Gradient Colors:**
```
Purple: linear-gradient(135deg, #667eea 0%, #764ba2 100%)
Pink: linear-gradient(135deg, #f093fb 0%, #f5576c 100%)
Blue: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)
Yellow: linear-gradient(135deg, #fa709a 0%, #fee140 100%)
Green: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)
```

### Mengupdate Episode
```bash
PUT /api/episodes/{id}
Authorization: Bearer {token}
```

### Menghapus Episode
```bash
DELETE /api/episodes/{id}
Authorization: Bearer {token}
```

---

## ⏰ Mengelola Timeline & Semester

### Melihat Timeline
```bash
GET /api/timeline
```

**Response:**
```json
{
  "initialCount": 45,
  "currentCount": 38,
  "semesters": [...]
}
```

### Update Statistik Timeline
```bash
POST /api/timeline
Authorization: Bearer {token}
Content-Type: application/json

Body:
{
  "initial_count": 45,
  "current_count": 38
}
```

### Menambah Semester Baru
```bash
POST /api/semesters
Authorization: Bearer {token}
Content-Type: application/json

Body:
{
  "semester": 7,
  "period": "Sep 2026 - Jan 2027",
  "icon": "🎯",
  "student_count": 36,
  "lecturers": [
    {
      "name": "Pak Ahmad",
      "isAnomaly": false
    },
    {
      "name": "Dosen Anomali",
      "isAnomaly": true
    }
  ]
}
```

**Icon Suggestions:**
- 🎯 (Target)
- 🚀 (Rocket)
- ⚡ (Lightning)
- 🎨 (Art)
- 🔥 (Fire)
- 🎓 (Graduation)
- 💻 (Computer)
- 🌟 (Star)

### Update Semester
```bash
PUT /api/semesters/{id}
Authorization: Bearer {token}
```

### Hapus Semester
```bash
DELETE /api/semesters/{id}
Authorization: Bearer {token}
```

---

## 📸 Upload Foto

### Format yang Didukung
- JPG/JPEG
- PNG
- WebP (recommended untuk optimasi)

### Ukuran Maksimal
- 2MB per file

### Rekomendasi
- Gunakan foto dengan rasio 1:1 (square) untuk profil mahasiswa
- Compress foto sebelum upload untuk performa lebih baik
- Gunakan format WebP untuk ukuran file lebih kecil

---

## 🔄 Workflow Admin yang Disarankan

### 1. Setup Awal
1. Login sebagai admin
2. Update statistik timeline (jumlah mahasiswa awal & sekarang)
3. Tambahkan data semester per semester
4. Tambahkan dosen-dosen ke setiap semester

### 2. Menambah Konten
1. Upload profil mahasiswa dengan foto normal dan aib
2. Tambahkan episodes/momen legendaris
3. Isi deskripsi lengkap untuk setiap episode

### 3. Maintenance Rutin
1. Update jumlah mahasiswa yang survive setiap semester
2. Tambahkan episode baru ketika ada kejadian lucu
3. Update status mahasiswa (Aktif/Cuti/Alumni)

---

## 🛡️ Security Best Practices

### ⚠️ Penting untuk Production

1. **Ganti Password Default**
   ```bash
   # Di Laravel tinker atau database
   $user = User::find(1);
   $user->password = Hash::make('password_baru_yang_kuat');
   $user->save();
   ```

2. **Gunakan HTTPS**
   - Pastikan API menggunakan HTTPS di production
   - Update `API_BASE_URL` di frontend

3. **Token Management**
   - Simpan token di localStorage atau secure cookie
   - Logout ketika selesai menggunakan dashboard
   - Token akan expired sesuai konfigurasi Laravel Sanctum

4. **Backup Database**
   - Backup database secara berkala
   - Simpan backup di lokasi yang aman

---

## 🐛 Troubleshooting

### Token Tidak Valid
**Error:** `Unauthenticated`
**Solusi:** 
- Pastikan header Authorization sudah benar
- Login ulang untuk mendapatkan token baru

### Upload Foto Gagal
**Error:** `The photo must be an image` atau `The photo may not be greater than 2048 kilobytes`
**Solusi:**
- Pastikan file adalah gambar (jpg, png, webp)
- Compress foto jika lebih dari 2MB

### CORS Error
**Error:** `Access to XMLHttpRequest blocked by CORS policy`
**Solusi:**
- Pastikan frontend URL sudah ditambahkan di `SANCTUM_STATEFUL_DOMAINS`
- Restart Laravel server setelah update .env

---

## 📞 Support

Jika ada masalah teknis:
1. Check error logs di `backend/storage/logs/laravel.log`
2. Pastikan server Laravel dan Nuxt sudah running
3. Verify database migrations sudah berjalan

---

**Happy Managing! 🎉**
