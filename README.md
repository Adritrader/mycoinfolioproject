# 💰 MyCoinFolioProject

**Proyecto final del ciclo formativo de Desarrollo de Aplicaciones Web (2º DAW)**  
Aplicación web para la gestión de portafolios de criptomonedas, desarrollada con Symfony (PHP) como backend.

Este proyecto proporciona una API RESTful segura y robusta que permite a los usuarios gestionar sus inversiones en criptomonedas. Está diseñada para ser consumida por una aplicación frontend desarrollada en React (en desarrollo o por integrar).

---

## 🛠 Tecnologías utilizadas

- **PHP 8.x**
- **Symfony 5/6**
- **Composer**
- **Docker & Docker Compose**
- **Doctrine ORM**
- **JWT (JSON Web Token)** para autenticación segura
- **PHPUnit** para pruebas unitarias
- **PostgreSQL / MySQL** (según configuración)
- **OpenAPI / Swagger** (opcional para documentación)

---

## 🔐 Funcionalidades principales

- Registro y login de usuarios (con JWT)
- Gestión de portafolio personal de criptomonedas
- Endpoints protegidos para operaciones privadas
- Posibilidad de integrar frontend con React o Vue.js

---

## 🚀 Cómo ejecutar el proyecto

### 1. Clona el repositorio

```bash
git clone https://github.com/Adritrader/mycoinfolioproject.git
cd mycoinfolioproject

### 2. Crea el entorno
```bash

cp .env .env.local


Configura .env.local con tus datos de base de datos y JWT si es necesario.

### 3. Levanta el entorno con Docker

```bash

docker-compose up -d --build


### 4. Instala dependencias y configura
```bash

docker exec -it php bash
composer install
php bin/console doctrine:migrations:migrate
php bin/console lexik:jwt:generate-keypair

🧪 Tests

```bash

php bin/phpunit

📁 Estructura general

```bash

mycoinfolioproject/
├── config/               # Configuración del proyecto Symfony
├── src/                  # Código fuente: controladores, entidades, etc.
├── migrations/           # Migraciones de base de datos
├── public/               # Carpeta pública (entrada del servidor web)
├── tests/                # Pruebas con PHPUnit
├── docker/               # Configuración de contenedores
└── .env / docker-compose.yml / README.md

👨‍💻 Autor
Adrián García
Proyecto desarrollado de forma individual como trabajo final de segundo curso del ciclo Desarrollo de Aplicaciones Web (2º DAW).
