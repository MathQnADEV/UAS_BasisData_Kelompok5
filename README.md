# UAS MATA KULIAH BASIS DATA KELOMPOK 5

## Cara clone projek nya

Clone repository

```bash
  git clone https://github.com/MathQnADEV/UAS_BasisData_Kelompok5.git
```

Ubah path folder ke projek

```bash
  cd UAS_BasisData_Kelompok5
```

Install depedensi composer dan npm

```bash
  composer install
  npm install
```

copy file .env

```bash
  cp .env.example .env
```

Generate application key

```bash
  php artisan key:generate
```

setup database (sesuai projek)

```bash
  DB_DATABASE=namadb
  DB_USERNAME=root
  DB_PASSWORD=
```

Jalankan server

```bash
  composer run dev
```

