# Taller PHP Avanzado - Proyecto listo

## Requisitos
- PHP 8+
- Composer
- Laragon (o XAMPP/WAMP) para servir desde `public/`.

## Instalación local (Laragon)
1. Copia la carpeta `taller-php-avanzado` en `C:\\laragon\\www\\`.
2. Abre una terminal en la raíz del proyecto y ejecuta:

```
composer install
```

3. Abre Laragon y reinicia Apache/Nginx si hace falta.
4. Accede en el navegador a: `http://localhost/taller-php-avanzado/public/`

## Notas sobre correo (Symfony Mailer)
- Configura la variable `MAILER_DSN` en un archivo `.env` en la raíz (no subirlo a GitHub). Ejemplo para Mailtrap:

```
MAILER_DSN=smtp://username:password@smtp.mailtrap.io:2525
MAIL_FROM=no-reply@example.com
```

- Si no configuras `MAILER_DSN`, el proyecto intentará usar `smtp://localhost`.

## Librerías usadas
- dompdf/dompdf (generación de PDF)
- symfony/mailer (envío de correos)
- intervention/image (ejemplo: opción para manipular imágenes si quieres extender)

## Estructura
- `app/` — Modelos, Controladores, Vistas
- `public/` — Punto de entrada (index.php)

## Entrega
- Sube todo al repositorio GitHub excepto `vendor/` y `.env`.
- Incluye en el README instrucciones para ejecutar `composer install`.
