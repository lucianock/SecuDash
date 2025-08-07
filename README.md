# 🔒 SecuDash - Security Dashboard

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-orange.svg)](https://laravel-livewire.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4.x-38B2AC.svg)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**SecuDash** es una plataforma integral de seguridad web que combina herramientas avanzadas de análisis de vulnerabilidades, gestión de credenciales, generación de contraseñas seguras y monitoreo de sistemas en una interfaz moderna y fácil de usar.

## ✨ Características Principales

### 🛡️ Escáner Avanzado de Vulnerabilidades
- **Escaneo Rápido**: Análisis básico de headers de seguridad y vulnerabilidades comunes
- **Escaneo Completo**: Análisis exhaustivo con pruebas de inyección, fuzzing de parámetros y enumeración de recursos
- **Detección Inteligente**: Sistema basado en patrones con filtrado de falsos positivos
- **Progreso en Tiempo Real**: Monitoreo del estado del escaneo con actualizaciones en vivo
- **Categorización OWASP**: Clasificación según las categorías OWASP Top 10 2021

### 🔐 Gestión de Credenciales (Vault)
- Almacenamiento seguro de credenciales de servidores (cPanel, FTP, WHM)
- Encriptación de contraseñas
- Organización por tipo de servicio
- Notas adicionales para cada entrada

### 🔑 Generador de Contraseñas
- Generación de contraseñas seguras y aleatorias
- Configuración de longitud y complejidad
- Múltiples tipos de caracteres (mayúsculas, minúsculas, números, símbolos)

### 📊 Monitoreo de Sistema
- Dashboard en tiempo real con métricas del sistema
- Monitoreo de CPU, memoria RAM y disco
- Información detallada del servidor
- Uptime y estadísticas de servicios

### 🔍 Búsqueda de Vulnerabilidades
- Integración con APIs de vulnerabilidades
- Búsqueda por CVE, tipo de vulnerabilidad o descripción
- Información detallada de cada vulnerabilidad encontrada

### 📱 LinkedIn Scraper
- Extracción automatizada de contenido de LinkedIn
- Búsqueda por palabras clave
- Almacenamiento de posts relevantes
- Interfaz web para gestionar búsquedas

## 🚀 Tecnologías Utilizadas

### Backend
- **Laravel 12.x** - Framework PHP robusto y elegante
- **Livewire 3.x** - Componentes dinámicos sin JavaScript
- **PHP 8.2+** - Lenguaje de programación moderno
- **SQLite** - Base de datos ligera y rápida
- **Guzzle HTTP** - Cliente HTTP para escaneos
- **Queue System** - Procesamiento asíncrono de escaneos

### Frontend
- **Tailwind CSS 4.x** - Framework CSS utility-first
- **Alpine.js** - JavaScript reactivo ligero
- **Chart.js** - Gráficos interactivos para métricas
- **Vite** - Build tool moderno y rápido

### Herramientas de Escaneo
- **Playwright** - Automatización de navegador para scraping
- **Advanced Vulnerability Scanner** - Motor personalizado de detección
- **OWASP Top 10 2021** - Estándares de seguridad

## 📋 Requisitos del Sistema

- PHP 8.2 o superior
- Composer 2.0 o superior
- Node.js 18+ y npm
- Extensión PHP SQLite3
- Extensión PHP cURL
- Extensión PHP JSON

## 🛠️ Instalación

### 1. Clonar el Repositorio
```bash
git clone https://github.com/tu-usuario/secudash.git
cd secudash
```

### 2. Instalar Dependencias PHP
```bash
composer install
```

### 3. Instalar Dependencias Node.js
```bash
npm install
```

### 4. Configurar Variables de Entorno
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configurar Base de Datos
```bash
# Crear base de datos SQLite
touch database/database.sqlite

# Ejecutar migraciones
php artisan migrate

# Opcional: Ejecutar seeders para datos de ejemplo
php artisan db:seed
```

### 6. Configurar Colas (Opcional)
Para escaneos completos asíncronos:
```bash
# Configurar driver de colas en .env
QUEUE_CONNECTION=database

# Ejecutar worker de colas
php artisan queue:work
```

### 7. Compilar Assets
```bash
npm run build
```

### 8. Iniciar Servidor
```bash
php artisan serve
```

## 🔧 Configuración

### Variables de Entorno Importantes

```env
APP_NAME=SecuDash
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

QUEUE_CONNECTION=database
CACHE_DRIVER=file
SESSION_DRIVER=file
```

### Configuración de Seguridad

1. **Headers de Seguridad**: Configurar en `config/security.php`
2. **Rate Limiting**: Ajustar límites en `app/Http/Kernel.php`
3. **CORS**: Configurar en `config/cors.php`

## 📖 Uso

### Escáner de Vulnerabilidades

1. **Acceder al Escáner**: Navegar a `/vulnerability`
2. **Ingresar URL**: Proporcionar la URL objetivo
3. **Seleccionar Tipo**: Elegir entre escaneo rápido o completo
4. **Iniciar Escaneo**: El sistema comenzará el análisis
5. **Monitorear Progreso**: Ver el estado en tiempo real
6. **Revisar Resultados**: Analizar vulnerabilidades encontradas

### Gestión de Vault

1. **Acceder al Vault**: Navegar a `/vault`
2. **Crear Nueva Entrada**: Agregar credenciales de servidor
3. **Organizar**: Categorizar por tipo de servicio
4. **Gestionar**: Editar o eliminar entradas existentes

### Generador de Contraseñas

1. **Acceder al Generador**: Navegar a `/password-generator`
2. **Configurar Parámetros**: Establecer longitud y complejidad
3. **Generar**: Crear contraseña segura
4. **Copiar**: Usar la contraseña generada

### LinkedIn Scraper

1. **Configurar Cookies**: Preparar archivo `cookies.json`
2. **Ejecutar Scraper**: Usar el script de Node.js
3. **Gestionar Resultados**: Revisar posts extraídos

## 🔍 Características de Seguridad

### Escáner de Vulnerabilidades
- **Detección de Headers de Seguridad**: X-Frame-Options, CSP, HSTS, etc.
- **Pruebas de Inyección**: SQL, XSS, Command Injection
- **Análisis de Puertos**: Escaneo de puertos comunes
- **Fuzzing de Parámetros**: Pruebas de parámetros vulnerables
- **Enumeración de Recursos**: Búsqueda de recursos sensibles

### Protecciones Implementadas
- **Validación de Entrada**: Sanitización de datos de usuario
- **Rate Limiting**: Protección contra ataques de fuerza bruta
- **CSRF Protection**: Tokens de seguridad en formularios
- **SQL Injection Prevention**: Uso de prepared statements
- **XSS Protection**: Escapado de salida HTML

## 📊 Estructura del Proyecto

```
secudash/
├── app/
│   ├── Http/Controllers/     # Controladores principales
│   ├── Services/            # Servicios de negocio
│   ├── Models/              # Modelos de datos
│   ├── Jobs/                # Trabajos en cola
│   └── Livewire/            # Componentes Livewire
├── resources/
│   ├── views/               # Vistas Blade
│   ├── css/                 # Estilos CSS
│   └── js/                  # JavaScript
├── routes/                  # Definición de rutas
├── database/
│   ├── migrations/          # Migraciones de BD
│   └── seeders/             # Datos de ejemplo
├── scraping/                # Scripts de scraping
└── public/                  # Archivos públicos
```

## 🤝 Contribuir

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request

### Guías de Contribución

- Seguir las convenciones de Laravel
- Escribir tests para nuevas funcionalidades
- Documentar cambios importantes
- Mantener compatibilidad con versiones anteriores

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

## ⚠️ Descargo de Responsabilidad

**SecuDash** es una herramienta educativa y de prueba. Los usuarios son responsables de:

- Obtener autorización antes de escanear sistemas
- Cumplir con las leyes locales de ciberseguridad
- Usar la herramienta de manera ética y responsable
- No realizar ataques maliciosos o no autorizados

## 🆘 Soporte

- **Issues**: Reportar bugs en [GitHub Issues](https://github.com/tu-usuario/secudash/issues)
- **Discussions**: Preguntas y discusiones en [GitHub Discussions](https://github.com/tu-usuario/secudash/discussions)
- **Documentación**: Ver la [wiki del proyecto](https://github.com/tu-usuario/secudash/wiki)

## 🙏 Agradecimientos

- [Laravel](https://laravel.com) - Framework PHP
- [Livewire](https://laravel-livewire.com) - Componentes dinámicos
- [Tailwind CSS](https://tailwindcss.com) - Framework CSS
- [OWASP](https://owasp.org) - Estándares de seguridad
- [Playwright](https://playwright.dev) - Automatización de navegador

---

**Desarrollado con ❤️ para la comunidad de seguridad**
