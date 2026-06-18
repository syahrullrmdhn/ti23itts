# 🚀 Project Brief: Website Angkatan TI 2023 (ti23itts.my.id)

## 📌 Latar Belakang & Visi
Project ini bertujuan untuk ngebangun *digital yearbook* atau website kenang-kenangan buat anak-anak Teknik Informatika angkatan 2023 (TI'23) di ITTS. Kita sepakat untuk buang jauh-jauh kesan web kampus yang kaku dan bosenin. 

Visi utamanya adalah: **"How fun we are!"** Web ini harus jadi tempat nongkrong digital yang nyimpen semua memori, dari yang keren sampai aib-aib legendaris angkatan, dibungkus dengan UI/UX ala Netflix/platform streaming dan *culture page* perusahaan keren.

---

## 🎨 Referensi Visual, UI/UX, & Design System
1. **Design System: Bright, Happy & Playful (Colorful Tone)**
   * **Vibe:** Ceria, enerjik, dan penuh warna. Kita tinggalkan kesan *dark mode* yang terlalu serius. 
   * **Warna Utama:** Kombinasi warna-warna cerah atau pastel yang *popping* (misal: kuning cerah, biru muda, hijau mint, atau *pink* yang *fun*). Latar belakang putih atau *off-white* biar konten foto dan videonya lebih *stand out*.
   * **Tipografi:** Font *sans-serif* yang modern, tebal, dan asik buat *headline* (ala-ala font *startup* atau agensi kreatif).
2. **Layout Ala ABC iView (Streaming Service Vibe)**
   * **Konsep:** Walaupun warnanya cerah, secara tata letak (*layout*) kita tetap pakai sistem *carousel* atau *slider* horizontal untuk nampilin "Episode" memori.
   * **Penerapan:** *Slider* khusus "Zoom Fails", baris lain buat "Drama Kampus", baris lain buat "Hall of Fame". Pas diklik, bakal muncul detail ceritanya dalam *modal* atau halaman yang mulus.
3. **Ala MIFX Careers (Fun & Energetic Vibe)**
   * **Konsep:** Halaman depan penuh dengan *grid* foto-foto candid, *vibes* kelas, dan kebersamaan dengan nuansa terang.
   * **Penerapan:** Headline gede "What It's Like to Be TI'23 Insider". Tombol "Find Opportunities" diganti jadi tombol interaktif yang lucu kayak "Stalk Our Momen" atau "Spill The Tea" dengan warna yang kontras.

---

## 📂 Struktur Halaman & Fitur Utama (Frontend)

### 1. Hero Section (Muka Web)
* Headline: "TI'23 Insiders"
* Background: *Grid* foto-foto *random* anak-anak (yang cakep dicampur yang aib dikit) dengan filter atau *overlay* warna cerah, atau video *loop* momen asik kelas.
* Call to Action (CTA): Tombol *colorful* buat langsung *scroll* ke "Time Box" atau "Hall of Shame".

### 2. The Time Box (Perjalanan Angkatan)
* *Timeline* interaktif perjalanan dari Semester 1 sampai sekarang (desainnya dibikin ceria, misal pakai *icon-icon* lucu tiap semesternya).
* **Data yang ditampilin:**
  * Berapa orang yang masuk di awal, sisa berapa yang masih *survive* sekarang (lengkap dengan anak reguler dll).
  * Dosen-dosen yang ngajar tiap semester (termasuk *honorable mention* buat **Dosen Anomali**).

### 3. "Episodes" / Hall of Shame (Konten Utama)
Ini tempat buat naruh aib dan *inside jokes* kelas. Dibuat kayak *thumbnail* YouTube/Netflix tapi *cover*-nya dibikin *pop-art* atau nyeleneh. Beberapa konten wajib masuk:
* 🐒 **"Monkey Business"**: Tragedi Willy TI di kebon dikejar nyemot pas absen Zoom sampai ngereog.
* 💡 **"Leaked Jidat"**: Insiden Dhila yang *legend*.
* 🚿 **"The Naked Truth"**: Insiden Nico habis mandi pas Zoom.
* 💤 **"Symphony of Zoom"**: Misteri siapa yang ngorok pas kelas online.
* ✉️ **"Surat Terbuka Moment"**: Arsip momen-momen *spicy* surat terbuka.

### 4. Profil Angkatan (Cast & Crew)
* Kartu profil masing-masing anak TI'23 yang masih aktif. *Card design*-nya dibikin *clean* tapi *playful*.
* Isinya simpel: Foto (bisa di-*hover* berubah jadi foto aib), Nama, Role/Karakter di kelas (Misal: "Tukang Tidur", "Si Paling Rajin", "Korban Monyet").

---

## ⚙️ Spesifikasi Teknis & Admin Dashboard (Backend)
Biar webnya *maintainable*, gak cuma *hardcode*, dan "gak ada yang abu-abu", kita butuh Admin Dashboard yang *clean*.

### Fitur Admin Dashboard:
* **Autentikasi:** Login khusus admin (bisa perwakilan kelas aja yang pegang).
* **Manajemen Profil (CRUD Mahasiswa):** Tambah, edit, atau hapus profil mahasiswa, unggah foto (normal & aib), set status (aktif/cuti/hilang).
* **Manajemen "Episodes" (CRUD Momen):** Form buat *upload* cerita lucu, masukin judul, deskripsi, foto/video *thumbnail*, dan milih kategori momen.
* **Manajemen Timeline:** Tambah data dosen, update jumlah mahasiswa *survive* per semester.

### Rekomendasi Tech Stack:
* **Frontend (Nuxt.js):** Cocok banget buat bikin UI/UX interaktif yang butuh *smooth scroll* dan transisi asik. *Styling* bisa pakai Tailwind CSS untuk gampang bikin tema warna-warni yang konsisten.
* **Backend (Laravel API):** Sistemnya kita pisah (*headless*). Backend pakai Laravel cukup buat nyediain REST API. *Setup* autentikasi admin dan CRUD kontennya gampang dan terstruktur.
* **Database (SQLite -> Migration):** Mulai *development* pakai **SQLite** biar praktis dan *sat-set* di lokal. Nanti pas mau *deploy* ke *production* (`ti23itts.my.id`), tinggal jalankan *migration* ke MySQL/PostgreSQL.

---

## 🛑 Out of Scope (Yang Nggak Perlu Dibuat)
Biar fokus dan gak *over-engineering*:
1. Nggak perlu ada fitur register buat *user* biasa (publik cuma bisa lihat, nggak bisa komen/login).
2. Nggak perlu sistem *payment* atau transaksi.
3. Nggak perlu integrasi e-learning beneran (ini murni web *showcase*/kenang-kenangan).

---

## 🎯 Aturan Main (Development Rules)
1. **Clean Code:** Penamaan variabel dan *routing* harus masuk akal (contoh: `/api/students`, bukan `/api/data123`).
2. **Mobile Responsive:** Wajib enak dilihat di HP, karena pasti anak-anak bakal buka web ini buat pamer ke temennya lewat HP.
3. **Asset Optimization:** Karena bakal banyak foto dan video aib, pastikan pakai kompresi (misal format WebP) biar *loading* web di `ti23itts.my.id` nggak lemot.