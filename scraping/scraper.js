const { chromium } = require('playwright');
const fs = require('fs');

(async () => {
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext();

    // Reemplazar con cookies válidas o usar login manual
    // Ejemplo en scraper.js: (línea para agregar cookies)
    await context.addCookies(JSON.parse(fs.readFileSync('cookies.json', 'utf8')));

    const page = await context.newPage();
    const keyword = process.argv[2] || 'hacking';

    await page.goto(`https://www.linkedin.com/search/results/content/?keywords=${encodeURIComponent(keyword)}`);

    await page.waitForTimeout(5000); // Esperar carga

    const posts = await page.$$eval('.update-components-text', nodes =>
        nodes.map(n => ({
            content: n.innerText,
        }))
    );

    console.log(JSON.stringify(posts, null, 2));

    await browser.close();
})();
