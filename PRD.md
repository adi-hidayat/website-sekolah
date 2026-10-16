# Product Requirements Document (PRD)

## Website Sekolah Menengah Atas (SMA)

**Versi:** 1.0  
**Tanggal:** Oktober 2026  
**Status:** Draft  
**Platform:** Web Application

---

## 1. Ringkasan Produk

Website SMA merupakan platform informasi resmi sekolah yang menyediakan profil sekolah, program jurusan, berita, pengumuman, fasilitas, galeri, prestasi, dan informasi Penerimaan Murid Baru (SPMB/PPDB).

Website dilengkapi Content Management System (CMS) berbasis Laravel Filament sehingga administrator dapat mengelola konten, membuat halaman khusus melalui page builder, serta mengatur struktur navigasi secara fleksibel tanpa mengubah kode program.

Frontend menggunakan Vue 3 untuk menampilkan halaman publik secara responsif dan dinamis.

### 1.1 Tujuan Produk

- Menyediakan pusat informasi resmi sekolah.
- Memperkenalkan jurusan atau program peminatan sekolah.
- Memudahkan staf mengelola konten website.
- Memungkinkan pembuatan halaman baru tanpa coding.
- Menyediakan menu dinamis yang dapat dihubungkan dengan halaman CMS.
- Mendukung optimasi SEO dan perangkat mobile.
- Menyediakan fondasi yang dapat dikembangkan menjadi sistem informasi sekolah.

### 1.2 Target Pengguna

1. Calon siswa dan orang tua.
2. Siswa aktif.
3. Guru dan tenaga kependidikan.
4. Alumni.
5. Masyarakat umum.
6. Administrator website.
7. Editor dan kontributor konten.

---

## 2. Teknologi dan Arsitektur

| Komponen        | Teknologi                      |
| --------------- | ------------------------------ |
| Backend         | Laravel                        |
| Admin Panel     | Filament                       |
| Login UI        | Filament Login Enhancer        |
| Page Builder    | Filament Crumbls / Layup       |
| Frontend        | Vue 3                          |
| Bahasa frontend | TypeScript                     |
| Styling         | Tailwind CSS                   |
| Database        | MySQL atau MariaDB             |
| Build Tool      | Vite                           |
| API             | Laravel REST API               |
| Authentication  | Laravel session authentication |
| Media Storage   | Laravel Filesystem             |
| Version Control | Git                            |

### 2.1 Prinsip Arsitektur

Laravel berfungsi sebagai backend dan sumber data utama.

Filament digunakan untuk administrasi konten dan konfigurasi website. Crumbls/Layup digunakan untuk membangun halaman dinamis melalui antarmuka admin sesuai kemampuan versi package yang dipilih.

Vue digunakan untuk merender website publik berdasarkan data yang dikirimkan backend.

Prinsip utama:

- Konten disimpan di database, bukan hardcoded di frontend.
- Halaman CMS dapat dibuat tanpa menambah route khusus untuk setiap halaman.
- Menu dikelola melalui CMS.
- Data jurusan bersifat dinamis.
- Konten publik hanya berasal dari data yang sudah dipublikasikan.
- Struktur section page builder menggunakan konfigurasi terkontrol.
- Backend bertanggung jawab atas validasi, otorisasi, dan status publikasi.

### 2.2 Integrasi Package

Sebelum implementasi, pastikan versi Laravel, Filament, Login Enhancer, dan Crumbls/Layup kompatibel.

Page builder harus dapat menghasilkan struktur data yang bisa dibaca renderer Vue. Jika package menghasilkan struktur yang tidak dapat langsung dirender Vue, implementasikan adapter atau lapisan transformasi data.

---

## 3. Ruang Lingkup

### 3.1 Fitur MVP

- Homepage dinamis.
- Profil sekolah.
- Manajemen jurusan.
- Manajemen halaman CMS.
- Page builder.
- Manajemen menu.
- Manajemen berita.
- Manajemen pengumuman.
- Manajemen fasilitas.
- Manajemen prestasi.
- Manajemen galeri.
- Media library.
- SEO dasar.
- Role dan permission.
- Dashboard administrator.
- Responsive frontend.

---

## 4. Struktur Website Publik

### 4.1 Homepage

Homepage terdiri dari section yang dapat diatur melalui CMS.

Urutan section default:

1. Hero banner.
2. Sambutan kepala sekolah.
3. Profil singkat sekolah.
4. Statistik sekolah.
5. Daftar jurusan.
6. Berita terbaru.
7. Pengumuman terbaru.
8. Prestasi sekolah.
9. Fasilitas.
10. Galeri.
11. Informasi SPMB/PPDB.
12. Mitra sekolah.
13. Kontak dan lokasi.
14. Footer.

Administrator dapat mengubah urutan section, mengaktifkan atau menonaktifkan section, serta mengubah konten dan konfigurasi yang tersedia.

### 4.2 Profil Sekolah

Halaman profil menyediakan informasi:

- Sejarah sekolah.
- Visi dan misi.
- Sambutan kepala sekolah.
- Identitas sekolah.
- Struktur organisasi.
- Data statistik.
- Tenaga pendidik.
- Akreditasi.
- Prestasi institusi.
- Fasilitas sekolah.
- Lokasi dan kontak.

### 4.3 Halaman Jurusan

Sistem mendukung jumlah jurusan yang dinamis. Jurusan tidak boleh ditentukan menggunakan enum atau daftar hardcoded di frontend.

Contoh program untuk SMA:

- IPA.
- IPS.
- Program peminatan lain sesuai kebijakan sekolah.

Nama dan kategori program harus dapat disesuaikan oleh administrator.

Setiap jurusan mempunyai data:

- Nama jurusan.
- Kode jurusan.
- Slug URL.
- Deskripsi singkat.
- Deskripsi lengkap.
- Gambar thumbnail.
- Banner.
- Kompetensi atau mata pelajaran unggulan.
- Guru terkait.
- Fasilitas pendukung.
- Prestasi.
- Galeri.
- SEO title.
- Meta description.
- Status publikasi.
- Urutan tampilan.

Route:

- `/jurusan`
- `/jurusan/{slug}`

### 4.4 Berita dan Artikel

Fitur berita meliputi:

- Judul.
- Slug.
- Ringkasan.
- Isi artikel.
- Featured image.
- Galeri tambahan.
- Kategori.
- Tag.
- Penulis.
- Tanggal publikasi.
- Status draft, published, dan archived.
- SEO title.
- Meta description.
- Canonical URL.
- Artikel terkait.
- Pagination.
- Pencarian.

Route:

- `/berita`
- `/berita/{slug}`

### 4.5 Pengumuman

Setiap pengumuman mempunyai:

- Judul.
- Slug.
- Isi.
- Kategori.
- Tanggal mulai.
- Tanggal berakhir.
- Lampiran dokumen.
- Status publikasi.
- Penanda penting.
- Urutan tampilan.

Pengumuman yang melewati tanggal berakhir tidak ditampilkan sebagai pengumuman aktif, tetapi dapat tetap diakses jika kebijakan arsip mengizinkannya.

Route:

- `/pengumuman`
- `/pengumuman/{slug}`

### 4.6 Halaman Tambahan

Halaman yang dapat dibuat melalui CMS meliputi:

- Ekstrakurikuler.
- Prestasi siswa.
- Alumni.
- Perpustakaan.
- Fasilitas.
- Kalender kegiatan.
- Beasiswa.
- Informasi SPMB/PPDB.
- Kerja sama sekolah.
- Halaman informasi khusus.

Halaman tambahan tidak memerlukan perubahan kode Laravel atau Vue untuk setiap halaman baru.

---

## 5. CMS dan Page Builder

### 5.1 Manajemen Halaman

Setiap halaman CMS mempunyai field:

| Field             | Keterangan                  |
| ----------------- | --------------------------- |
| `title`           | Judul halaman               |
| `slug`            | URL unik                    |
| `excerpt`         | Ringkasan                   |
| `content`         | Konten rich text opsional   |
| `sections`        | Struktur section dalam JSON |
| `template`        | Template rendering          |
| `featured_image`  | Gambar utama                |
| `status`          | Draft, published, archived  |
| `published_at`    | Jadwal publikasi            |
| `seo_title`       | Judul SEO                   |
| `seo_description` | Deskripsi SEO               |
| `canonical_url`   | URL canonical               |
| `created_by`      | Pembuat                     |
| `updated_by`      | Pengubah terakhir           |
| `created_at`      | Waktu pembuatan             |
| `updated_at`      | Waktu pembaruan             |
| `deleted_at`      | Soft delete jika digunakan  |

### 5.2 Status Halaman

- **Draft:** hanya dapat dilihat oleh pengguna berizin.
- **Published:** dapat ditampilkan pada website publik.
- **Archived:** tidak tampil sebagai konten aktif.
- **Scheduled:** opsional, dipublikasikan pada waktu yang ditentukan.

Ketentuan:

- Slug harus unik.
- Admin dapat melakukan preview sebelum publikasi.
- Halaman draft tidak dapat diakses publik.
- Perubahan slug sebaiknya menghasilkan redirect 301.
- Jadwal publikasi menggunakan Laravel Scheduler jika fitur tersebut diaktifkan.

### 5.3 Integrasi Crumbls/Layup

Page builder digunakan untuk menyusun section halaman melalui antarmuka administratif.

Section yang tersedia:

| Section       | Konfigurasi                         |
| ------------- | ----------------------------------- |
| Hero          | Judul, deskripsi, gambar, CTA       |
| Rich Text     | Heading, paragraf, daftar, tautan   |
| Image         | Gambar, caption, alignment          |
| Image Gallery | Koleksi gambar                      |
| Card Grid     | Judul, deskripsi, gambar, tautan    |
| Statistics    | Label dan nilai statistik           |
| Teachers      | Daftar guru                         |
| Programs      | Daftar jurusan                      |
| Latest News   | Berita terbaru                      |
| Announcements | Pengumuman aktif                    |
| CTA           | Judul, deskripsi, tombol            |
| Contact       | Alamat, telepon, email, peta        |
| FAQ           | Pertanyaan dan jawaban              |
| Custom HTML   | Opsional, hanya untuk admin berizin |

Admin dapat:

- Menambahkan section.
- Menghapus section.
- Mengubah konfigurasi section.
- Mengurutkan section.
- Melakukan preview.
- Menyimpan draft.
- Menerbitkan halaman.

### 5.4 Struktur Section

Struktur konseptual:

```json
{
    "version": 1,
    "sections": [
        {
            "id": "hero-001",
            "type": "hero",
            "data": {
                "title": "Selamat Datang di SMA Nusantara",
                "description": "Membangun generasi unggul dan berkarakter",
                "image": "/storage/pages/hero.webp",
                "cta": {
                    "label": "Profil Sekolah",
                    "url": "/profil"
                }
            }
        },
        {
            "id": "news-001",
            "type": "latest_news",
            "data": {
                "title": "Berita Terbaru",
                "limit": 3
            }
        }
    ]
}
```

Ketentuan renderer:

- Setiap `type` terdaftar di registry komponen Vue.
- Setiap jenis section memiliki schema konfigurasi.
- Data divalidasi di backend.
- Section yang tidak dikenal tidak boleh menyebabkan seluruh halaman gagal.
- URL gambar harus berasal dari media yang valid.
- HTML khusus harus disanitasi.
- JavaScript bebas tidak boleh dijalankan dari konten CMS.

### 5.5 Template Halaman

Template default:

- `default`: halaman standar.
- `landing`: landing page dengan hero dan CTA.
- `full-width`: konten selebar container utama.
- `article`: layout editorial.
- `contact`: halaman kontak.
- `jurusan`: detail jurusan.

Template menentukan struktur layout, sedangkan konten tetap dapat dikelola melalui CMS.

---

## 6. Sistem Menu Fleksibel

### 6.1 Tujuan

Administrator dapat membuat halaman baru lalu memasukkannya ke navigasi website tanpa mengubah kode.

### 6.2 Jenis Menu

- `page`: halaman CMS.
- `department`: detail jurusan.
- `news`: halaman berita atau kategori.
- `announcement`: halaman pengumuman.
- `custom_url`: URL khusus.
- `dropdown`: parent submenu.

### 6.3 Field Menu

| Field           | Fungsi                |
| --------------- | --------------------- |
| `menu_id`       | Lokasi menu           |
| `label`         | Teks menu             |
| `type`          | Jenis item            |
| `page_id`       | Referensi halaman     |
| `department_id` | Referensi jurusan     |
| `target_url`    | URL khusus            |
| `parent_id`     | Parent submenu        |
| `target`        | Same tab atau new tab |
| `is_visible`    | Status tampilan       |
| `sort_order`    | Urutan                |
| `visibility`    | Aturan visibilitas    |
| `created_at`    | Waktu pembuatan       |
| `updated_at`    | Waktu pembaruan       |

### 6.4 Lokasi Menu

Sistem menyediakan lokasi menu:

- Header.
- Footer.
- Utility navigation.
- Lokasi tambahan sesuai konfigurasi.

### 6.5 Aturan Bisnis Menu

1. Menu dapat merujuk ke halaman CMS yang sudah published.
2. Label menu tidak harus sama dengan judul halaman.
3. Admin dapat mengubah urutan melalui drag-and-drop jika didukung implementasi UI.
4. Dropdown mendukung submenu dengan batas kedalaman yang dikonfigurasi.
5. Menu menuju halaman draft atau halaman yang dihapus harus dinonaktifkan atau ditandai.
6. Menu header dan footer dapat memiliki struktur berbeda.
7. Perubahan menu harus terlihat setelah cache diperbarui.
8. URL eksternal harus divalidasi.
9. Tautan tab baru menggunakan atribut keamanan yang sesuai.
10. Halaman published tetap dapat diakses melalui URL meskipun tidak terdaftar di menu.
11. Referensi menu harus divalidasi untuk mencegah parent loop.
12. Item menu yang tidak valid tidak boleh merusak seluruh navigasi.

### 6.6 Mode Menu

**Manual:** admin memilih item dan urutan menu secara eksplisit.

**Dynamic source:** item menu dapat dibuat dari sumber seperti jurusan atau kategori tertentu.

MVP wajib mendukung menu manual. Dynamic source dapat ditambahkan agar jurusan baru dapat muncul otomatis pada navigasi tertentu.

---

## 7. Backend Filament

### 7.1 Dashboard

Dashboard menampilkan:

- Jumlah halaman published.
- Jumlah halaman draft.
- Jumlah berita.
- Jumlah pengumuman aktif.
- Jumlah jurusan.
- Artikel yang terakhir diperbarui.
- Shortcut pembuatan konten.

### 7.2 Resource Admin

| Resource        | Fungsi                        |
| --------------- | ----------------------------- |
| Dashboard       | Ringkasan CMS                 |
| Pages           | CRUD halaman dan page builder |
| Menus           | Navigasi header dan footer    |
| Departments     | Manajemen jurusan             |
| News            | Berita dan artikel            |
| News Categories | Kategori berita               |
| Tags            | Tag artikel                   |
| Announcements   | Pengumuman                    |
| Teachers        | Tenaga pendidik               |
| Facilities      | Fasilitas                     |
| Achievements    | Prestasi                      |
| Galleries       | Album galeri                  |
| Media Library   | Aset media                    |
| School Settings | Konfigurasi sekolah           |
| Users & Roles   | Pengguna dan izin             |
| Activity Logs   | Riwayat aktivitas             |

### 7.3 Login Enhancer

Gunakan Filament Login Enhancer untuk kustomisasi tampilan login sesuai fitur package.

Kebutuhan:

- Logo sekolah.
- Nama sekolah.
- Branding warna.
- Pesan validasi.
- Tampilan responsif.
- Rate limiting.
- Proteksi autentikasi.
- Pemulihan password jika didukung.

Package ini tidak menggantikan autentikasi atau otorisasi Laravel.

### 7.4 Role dan Permission

| Role          | Hak akses                               |
| ------------- | --------------------------------------- |
| Super Admin   | Seluruh fitur dan konfigurasi           |
| Administrator | Mengelola konten dan website            |
| Editor        | Mengelola halaman, berita, pengumuman   |
| Kontributor   | Membuat dan mengedit konten sesuai izin |
| Viewer        | Melihat data tanpa mengubahnya          |

Aturan:

- Setiap resource memiliki kebijakan akses.
- Hak akses berlaku di backend, bukan hanya pada tampilan sidebar.
- Penghapusan dan perubahan konfigurasi penting dicatat.
- Perubahan role dan permission dibatasi.
- Pengguna tidak dapat menaikkan hak aksesnya sendiri tanpa otorisasi.

---

## 8. Frontend Vue

### 8.1 Prinsip Desain

- Desain profesional untuk institusi pendidikan.
- Responsive pada desktop, tablet, dan mobile.
- Navigasi konsisten.
- Tipografi yang jelas.
- Hierarki konten yang baik.
- Penggunaan foto sekolah yang relevan.
- Animasi seperlunya.
- Aksesibilitas keyboard.
- Kontras teks yang memadai.
- Alt text untuk gambar.

### 8.2 Struktur Direktori

Contoh struktur frontend:

```text
resources/
├── js/
│   ├── app.ts
│   ├── components/
│   │   ├── layout/
│   │   │   ├── AppHeader.vue
│   │   │   ├── AppFooter.vue
│   │   │   └── Breadcrumbs.vue
│   │   └── page-builder/
│   │       ├── PageRenderer.vue
│   │       ├── HeroSection.vue
│   │       ├── RichTextSection.vue
│   │       ├── CardGridSection.vue
│   │       ├── LatestNewsSection.vue
│   │       └── SectionFallback.vue
│   ├── layouts/
│   │   ├── DefaultLayout.vue
│   │   ├── LandingLayout.vue
│   │   └── ArticleLayout.vue
│   ├── pages/
│   │   ├── Home.vue
│   │   ├── PageDetail.vue
│   │   ├── DepartmentIndex.vue
│   │   ├── DepartmentDetail.vue
│   │   ├── NewsIndex.vue
│   │   ├── NewsDetail.vue
│   │   ├── AnnouncementIndex.vue
│   │   └── NotFound.vue
│   └── services/
│       ├── api.ts
│       └── seo.ts
└── css/
    └── app.css
```

Struktur ini merupakan contoh pemisahan modul. Jika Vue dijalankan sebagai proyek terpisah, direktori dapat disesuaikan.

### 8.3 Routing

| Route               | Halaman                          |
| ------------------- | -------------------------------- |
| `/`                 | Homepage                         |
| `/profil`           | Profil sekolah                   |
| `/jurusan`          | Daftar jurusan                   |
| `/jurusan/:slug`    | Detail jurusan                   |
| `/berita`           | Daftar berita                    |
| `/berita/:slug`     | Detail berita                    |
| `/pengumuman`       | Daftar pengumuman                |
| `/pengumuman/:slug` | Detail pengumuman                |
| `/page/:slug`       | Halaman CMS                      |
| `/:slug`            | Alias opsional untuk halaman CMS |
| `/*`                | Halaman 404                      |

Jika alias `/:slug` digunakan, route khusus harus diprioritaskan untuk menghindari konflik. Slug global harus unik di antara jenis konten yang berbagi namespace URL.

### 8.4 Rendering Halaman

Alur rendering:

1. Vue menerima URL halaman.
2. Frontend meminta data halaman kepada backend.
3. Backend memeriksa status publikasi.
4. Frontend menerima metadata dan section.
5. `PageRenderer.vue` memilih komponen berdasarkan `type`.
6. Komponen menampilkan konfigurasi section.
7. Header dan footer menampilkan menu aktif.
8. Metadata SEO diterapkan sesuai halaman.

Untuk SEO, gunakan SSR atau prerendering sesuai kebutuhan. SPA murni dengan konten yang hanya muncul setelah JavaScript dijalankan perlu dievaluasi berdasarkan kebutuhan pengindeksan mesin pencari.

---

## 9. Database Schema

### 9.1 Tabel Utama

| Tabel                 | Fungsi                         |
| --------------------- | ------------------------------ |
| `school_settings`     | Identitas sekolah              |
| `pages`               | Halaman CMS                    |
| `menus`               | Lokasi navigasi                |
| `menu_items`          | Item menu dan hierarki         |
| `departments`         | Data jurusan                   |
| `news`                | Berita dan artikel             |
| `news_categories`     | Kategori berita                |
| `news_category_pivot` | Relasi berita dan kategori     |
| `tags`                | Tag                            |
| `news_tag_pivot`      | Relasi berita dan tag          |
| `announcements`       | Pengumuman                     |
| `teachers`            | Tenaga pendidik                |
| `facilities`          | Fasilitas                      |
| `achievements`        | Prestasi                       |
| `galleries`           | Album galeri                   |
| `gallery_items`       | Foto dan video                 |
| `media`               | Metadata media jika diperlukan |
| `users`               | Pengguna admin                 |
| `activity_logs`       | Riwayat aktivitas              |

Tabel role dan permission mengikuti mekanisme otorisasi yang digunakan.

### 9.2 Tabel `pages`

```text
pages
- id
- title
- slug (unique)
- excerpt (nullable)
- content (nullable)
- sections (JSON)
- template
- featured_image (nullable)
- status
- published_at (nullable)
- seo_title (nullable)
- seo_description (nullable)
- canonical_url (nullable)
- created_by
- updated_by
- created_at
- updated_at
- deleted_at (nullable)
```

### 9.3 Tabel `menu_items`

```text
menu_items
- id
- menu_id
- parent_id (nullable)
- label
- type
- page_id (nullable)
- department_id (nullable)
- target_url (nullable)
- target
- is_visible
- sort_order
- created_at
- updated_at
```

Tambahkan foreign key dan indeks pada kolom relasi yang relevan.

Validasi berdasarkan jenis menu harus memastikan bahwa setiap item mempunyai target yang sesuai.

### 9.4 Relasi Data

- Satu menu memiliki banyak menu item.
- Menu item dapat memiliki parent dan child.
- Menu item dapat mereferensikan halaman CMS.
- Menu item dapat mereferensikan jurusan.
- Satu jurusan dapat memiliki banyak guru.
- Berita dapat memiliki banyak kategori.
- Berita dapat memiliki banyak tag.
- Galeri dapat memiliki banyak item.
- Pengguna dapat membuat banyak halaman dan berita.

---

## 10. API Backend

Gunakan prefix `/api/v1` untuk endpoint publik.

| Method | Endpoint                       | Fungsi              |
| ------ | ------------------------------ | ------------------- |
| GET    | `/api/v1/site-settings`        | Konfigurasi website |
| GET    | `/api/v1/menus/{location}`     | Navigasi            |
| GET    | `/api/v1/pages/{slug}`         | Detail halaman      |
| GET    | `/api/v1/departments`          | Daftar jurusan      |
| GET    | `/api/v1/departments/{slug}`   | Detail jurusan      |
| GET    | `/api/v1/news`                 | Daftar berita       |
| GET    | `/api/v1/news/{slug}`          | Detail berita       |
| GET    | `/api/v1/news-categories`      | Kategori berita     |
| GET    | `/api/v1/announcements`        | Daftar pengumuman   |
| GET    | `/api/v1/announcements/{slug}` | Detail pengumuman   |
| GET    | `/api/v1/facilities`           | Daftar fasilitas    |
| GET    | `/api/v1/achievements`         | Daftar prestasi     |
| GET    | `/api/v1/galleries`            | Daftar galeri       |

Endpoint publik hanya mengembalikan data yang boleh dipublikasikan. Endpoint administratif harus menerapkan autentikasi, validasi, dan policy.

### 10.1 Contoh Respons Halaman

```json
{
    "data": {
        "title": "Profil Sekolah",
        "slug": "profil",
        "template": "default",
        "sections": [],
        "seo": {
            "title": "Profil SMA Nusantara",
            "description": "Informasi profil sekolah"
        }
    }
}
```

Respons produksi harus menyertakan struktur section lengkap sesuai data halaman.

---

## 11. SEO, Keamanan, dan Performa

### 11.1 SEO

- Meta title dan description per halaman.
- URL deskriptif.
- Sitemap XML otomatis.
- Canonical URL.
- Open Graph.
- Structured data yang relevan.
- Alt text gambar.
- Redirect 301 ketika slug berubah.
- Halaman draft tidak boleh terindeks.
- Pengaturan robots dan sitemap.

### 11.2 Keamanan

- Validasi server-side.
- CSRF protection.
- Rate limiting.
- Escape output.
- Sanitasi rich text.
- Validasi MIME type dan ukuran file.
- Pembatasan jenis file upload.
- Tidak menjalankan JavaScript bebas dari konten CMS.
- Role dan policy untuk operasi administratif.
- Audit log untuk perubahan penting.
- HTTPS pada produksi.
- Backup database dan media.

### 11.3 Performa

- Pagination.
- Optimasi gambar.
- Lazy loading.
- Cache untuk halaman published.
- Cache untuk menu dan konfigurasi.
- Invalidasi cache saat data berubah.
- Eager loading untuk menghindari query N+1.
- Indeks database yang sesuai.
- Pemantauan error dan performa endpoint.

---

## 12. Kebutuhan Nonfungsional

| Aspek         | Target                                                      |
| ------------- | ----------------------------------------------------------- |
| Responsivitas | Mobile, tablet, desktop                                     |
| Browser       | Browser modern                                              |
| Ketersediaan  | Target 99,5% per bulan                                      |
| Performa API  | Target p95 di bawah 500 ms pada kondisi uji yang ditentukan |
| Aksesibilitas | Mengikuti praktik WCAG 2.2 AA sejauh memungkinkan           |
| SEO           | Halaman publik dapat diindeks                               |
| Keamanan      | Validasi, otorisasi, proteksi upload, HTTPS                 |
| Backup        | Backup database dan media dengan pemulihan yang diuji       |

Target tersebut merupakan kriteria awal dan harus divalidasi melalui pengujian pada infrastruktur produksi.

---

## 13. Acceptance Criteria

### 13.1 CMS dan Halaman

- [ ] Admin dapat membuat, mengedit, preview, menerbitkan, dan mengarsipkan halaman.
- [ ] Slug harus unik.
- [ ] Halaman draft tidak tampil di website publik.
- [ ] Admin dapat mengubah urutan dan konfigurasi section.
- [ ] Setiap halaman dapat memiliki metadata SEO sendiri.
- [ ] Perubahan slug ditangani dengan redirect jika diperlukan.

### 13.2 Menu Dinamis

- [ ] Admin dapat membuat menu dari halaman CMS yang published.
- [ ] Admin dapat mengubah label, urutan, parent, dan visibilitas.
- [ ] Dropdown berfungsi pada desktop dan mobile.
- [ ] Menu menuju halaman draft tidak ditampilkan sebagai tautan publik aktif.
- [ ] Perubahan menu terlihat setelah cache diperbarui.
- [ ] Parent-child tidak dapat membentuk siklus.

### 13.3 Jurusan

- [ ] Admin dapat menambah jurusan tanpa perubahan kode.
- [ ] Setiap jurusan memiliki slug unik.
- [ ] Jurusan published tampil di daftar dan halaman detail.
- [ ] Jurusan draft tidak ditampilkan secara publik.
- [ ] Jurusan dapat ditautkan ke menu.

### 13.4 Konten dan Frontend

- [ ] Berita dan pengumuman memiliki halaman daftar dan detail.
- [ ] Homepage menampilkan section sesuai konfigurasi CMS.
- [ ] URL tidak valid menampilkan halaman 404.
- [ ] Website berfungsi pada perangkat mobile, tablet, dan desktop.
- [ ] Konten dinamis ditampilkan sesuai struktur data backend.

### 13.5 Akses dan Keamanan

- [ ] Pengguna tanpa autentikasi tidak dapat mengakses admin yang dilindungi.
- [ ] Role dan policy membatasi operasi CRUD.
- [ ] Konten HTML dan upload divalidasi.
- [ ] Perubahan penting tercatat dalam activity log.
- [ ] Pengguna tidak dapat menaikkan hak aksesnya sendiri tanpa izin.

---

## 14. Tahapan Pengembangan

### Fase 1 — Fondasi

- Setup Laravel.
- Setup Filament.
- Integrasi Login Enhancer.
- Setup database.
- Implementasi role dan permission.
- Konfigurasi sekolah.

### Fase 2 — CMS

- CRUD halaman.
- Integrasi Crumbls/Layup.
- Implementasi section.
- Template halaman.
- Media library.
- Preview dan publikasi.

### Fase 3 — Menu dan Konten

- Menu dinamis.
- Manajemen jurusan.
- Berita dan kategori.
- Pengumuman.
- Fasilitas.
- Prestasi.
- Galeri.

### Fase 4 — Frontend

- Homepage.
- Page renderer Vue.
- Layout dan navigasi.
- Detail jurusan.
- Daftar dan detail berita.
- Pengumuman.
- API integration.
- SEO dan responsive design.

### Fase 5 — QA dan Deployment

- Pengujian CRUD.
- Pengujian role.
- Pengujian page builder.
- Pengujian navigasi.
- Pengujian SEO.
- Pengujian keamanan.
- Optimasi cache.
- Backup dan pemulihan.
- Deployment produksi.

---

## 15. Prioritas Fitur

| Prioritas         | Fitur                                                     |
| ----------------- | --------------------------------------------------------- |
| P0 — Wajib        | Login, role, CMS halaman, page builder, menu dinamis      |
| P0 — Wajib        | Homepage, jurusan, berita, pengumuman                     |
| P0 — Wajib        | Media upload, publikasi, SEO dasar                        |
| P1 — Berikutnya   | Guru, fasilitas, prestasi, galeri                         |
| P1 — Berikutnya   | Activity log, jadwal publikasi                            |
| P2 — Pengembangan | Portal siswa, SPMB online, integrasi akademik, e-learning |

---

## 16. Keputusan Implementasi

1. **Satu sumber data CMS:** konten dikelola di backend dan tidak diduplikasi sebagai konten hardcoded di Vue.
2. **Registry section:** setiap section memiliki komponen Vue dan schema konfigurasi.
3. **Menu terpisah dari halaman:** membuat halaman tidak otomatis mengubah menu kecuali dynamic source diaktifkan.
4. **Slug stabil:** konflik URL harus dicegah melalui validasi.
5. **Kompatibilitas package:** versi Laravel, Filament, Login Enhancer, dan Crumbls/Layup harus diverifikasi sebelum implementasi.
6. **SEO sejak awal:** tentukan SSR atau prerendering berdasarkan kebutuhan pengindeksan.
7. **Validasi backend:** seluruh data yang disimpan melalui page builder harus divalidasi.
8. **Cache terkelola:** perubahan halaman dan menu harus memicu invalidasi cache yang relevan.

---

## 17. Definisi Keberhasilan MVP

MVP dianggap berhasil ketika administrator dapat:

1. Membuat halaman baru dari admin panel.
2. Menyusun konten melalui page builder.
3. Melakukan preview dan menerbitkan halaman.
4. Membuat menu yang merujuk ke halaman tersebut.
5. Mengatur urutan dan struktur submenu.
6. Menambah jurusan tanpa perubahan kode.
7. Mengelola berita dan pengumuman.
8. Melihat semua konten yang dipublikasikan pada frontend Vue.
9. Mengelola akses pengguna sesuai role.
10. Melakukan seluruh proses tersebut tanpa perubahan kode untuk setiap halaman baru.

**Hasil akhir:** website SMA dengan CMS yang fleksibel, navigasi dinamis, page builder, dan frontend Vue yang dapat dikembangkan lebih lanjut menjadi sistem informasi sekolah.
