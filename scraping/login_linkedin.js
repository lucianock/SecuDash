const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

(async () => {
  // Arrancamos el navegador en modo visible (headless: false)
  const browser = await chromium.launch({ headless: false });
  const context = await browser.newContext();
  const page = await context.newPage();

  // Vamos a la página de login de LinkedIn
  await page.goto('https://www.linkedin.com/login');

  // Reemplaza con tus credenciales o cargalas desde variables de entorno
  const email = process.env.LINKEDIN_EMAIL || 'lucho.gg.izi@gmail.com';
  const password = process.env.LINKEDIN_PASSWORD || '7895971320Lck.';

  // Completar el formulario
  await page.fill('input#username', email);
  await page.fill('input#password', password);
  await page.click('button[type="submit"]');

  // Esperamos que la autenticación se complete.
  // Puedes esperar un selector que sólo aparezca una vez logueado, por ejemplo, el avatar
  try {
    await page.waitForSelector('img.global-nav__me-photo', { timeout: 35000 });
    console.log('Login exitoso');
  } catch (error) {
    console.error('Error en el login o no se encontró el selector del avatar:', error);
    await browser.close();
    process.exit(1);
  }

  // Guardamos las cookies en un archivo para reutilizarlas
  const cookies = await context.cookies();
  const cookiesPath = path.resolve(__dirname, '../public/cookies.json');
  fs.writeFileSync(cookiesPath, JSON.stringify(cookies, null, 2));
  console.log(`Cookies guardadas en: ${cookiesPath}`);

  await browser.close();
})();
