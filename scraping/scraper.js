// scraper.js
const { chromium } = require('playwright');
const fs = require('fs');

(async () => {
  // Lanzar Chromium en modo no-headless para evitar bloqueos
  const browser = await chromium.launch({ headless: false });

  // Crear contexto con User Agent simulado
  const context = await browser.newContext({
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36'
  });

  // Cargar cookies guardadas (asegurate que no estén vencidas)
  const cookies = JSON.parse(fs.readFileSync('cookies.json', 'utf8'));
  await context.addCookies(cookies);

  const page = await context.newPage();
  const keyword = process.argv[2] || 'hacking';

  // Navegar a la búsqueda de contenido con espera por network idle
  await page.goto(
    `https://www.linkedin.com/search/results/content/?keywords=${encodeURIComponent(keyword)}`,
    { waitUntil: 'networkidle' }
  );

  // Esperar que al menos un post se haya renderizado
  await page.waitForSelector('div.feed-shared-update-v2', { timeout: 20000 });

  // Scroll para cargar más resultados dinámicos
  for (let i = 0; i < 5; i++) {
    await page.mouse.wheel(0, 3000);
    await page.waitForTimeout(5000);
  }

  // Extraer resumen y link de cada post
  const posts = await page.$$eval('div.feed-shared-update-v2', cards =>
    cards
      .map(card => {
        const textEl = card.querySelector('.update-components-text span');
        const content = textEl ? textEl.innerText.trim() : null;

        const linkEl = card.querySelector('a.app-aware-link');
        const link = linkEl ? linkEl.href : null;

        if (!content || !link) return null;
        const summary = content.split('. ').slice(0, 2).join('. ').slice(0, 200);
        return { summary, link };
      })
      .filter(p => p)
  );

  // Mostrar resultado
  console.log(JSON.stringify(posts, null, 2));
  await browser.close();
})();
