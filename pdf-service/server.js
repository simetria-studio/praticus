const express = require('express');
const puppeteer = require('puppeteer');
const cors = require('cors');
const bodyParser = require('body-parser');

const app = express();
const PORT = process.env.PORT || 3001;

// Middleware
app.use(cors());
app.use(bodyParser.json({ limit: '10mb' }));
app.use(bodyParser.urlencoded({ extended: true, limit: '10mb' }));

// Configuração do Puppeteer baseada no ambiente
function getPuppeteerConfig() {
    const config = {
        headless: 'new',
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-gpu',
            '--disable-extensions',
            '--disable-plugins',
            '--no-first-run',
            '--no-default-browser-check',
            '--disable-background-timer-throttling',
            '--disable-backgrounding-occluded-windows',
            '--disable-renderer-backgrounding'
        ]
    };

    // Usar executablePath se definido nas variáveis de ambiente
    if (process.env.PUPPETEER_EXECUTABLE_PATH) {
        config.executablePath = process.env.PUPPETEER_EXECUTABLE_PATH;
        console.log('🔧 Usando Chrome customizado:', process.env.PUPPETEER_EXECUTABLE_PATH);
    }

    return config;
}

// Rota de saúde
app.get('/health', (req, res) => {
    res.json({
        status: 'OK',
        message: 'PDF Service is running',
        chrome_path: process.env.PUPPETEER_EXECUTABLE_PATH || 'auto-detect',
        node_env: process.env.NODE_ENV || 'development'
    });
});

// Teste muito básico - só inicializar Puppeteer
app.post('/test-puppeteer', async (req, res) => {
    try {
        console.log('🧪 Testing Puppeteer initialization...');
        console.log('🔧 Chrome path:', process.env.PUPPETEER_EXECUTABLE_PATH || 'auto-detect');

        const config = getPuppeteerConfig();
        const browser = await puppeteer.launch(config);

        console.log('✅ Puppeteer browser launched successfully');
        await browser.close();
        console.log('✅ Browser closed successfully');

        res.json({
            success: true,
            message: 'Puppeteer is working correctly',
            version: require('puppeteer/package.json').version,
            chrome_path: process.env.PUPPETEER_EXECUTABLE_PATH || 'auto-detect',
            config: {
                headless: config.headless,
                args_count: config.args.length,
                has_executable_path: !!config.executablePath
            }
        });

    } catch (error) {
        console.error('❌ Puppeteer test failed:', error.message);
        res.status(500).json({
            success: false,
            error: 'Puppeteer failed to initialize',
            message: error.message,
            chrome_path: process.env.PUPPETEER_EXECUTABLE_PATH || 'auto-detect',
            suggestion: 'Try: npm install puppeteer --force or check Chrome installation'
        });
    }
});

// Rota de teste para debug
app.post('/test-pdf', async (req, res) => {
    try {
        console.log('🧪 Test PDF endpoint called');

        const testHtml = `
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Teste PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: white;
        }
        .test-content {
            border: 2px solid #1e40af;
            padding: 20px;
            text-align: center;
        }
        h1 { color: #1e40af; }
    </style>
</head>
<body>
    <div class="test-content">
        <h1>TESTE DE PDF FUNCIONANDO!</h1>
        <p>Este é um teste simples para verificar se o Puppeteer está gerando PDFs corretamente.</p>
        <p>Data: ${new Date().toLocaleString('pt-BR')}</p>
        <p>Chrome: ${process.env.PUPPETEER_EXECUTABLE_PATH || 'auto-detect'}</p>
        <p>Ambiente: ${process.env.NODE_ENV || 'development'}</p>
    </div>
</body>
</html>`;

        console.log('🚀 Launching Puppeteer browser...');
        const config = getPuppeteerConfig();
        const browser = await puppeteer.launch(config);

        console.log('📄 Creating new page...');
        const page = await browser.newPage();

        console.log('🎨 Setting HTML content...');
        await page.setContent(testHtml, {
            waitUntil: 'networkidle0',
            timeout: 10000
        });

        console.log('📋 Generating PDF...');
        const pdfBuffer = await page.pdf({
            format: 'A4',
            landscape: true,
            margin: { top: '10mm', right: '10mm', bottom: '10mm', left: '10mm' },
            printBackground: true
        });

        console.log('🔒 Closing browser...');
        await browser.close();

        console.log('✅ PDF generated successfully, size:', pdfBuffer.length, 'bytes');
        const pdfBase64 = pdfBuffer.toString('base64');

        res.json({
            success: true,
            pdf: pdfBase64,
            message: 'Test PDF generated successfully',
            size: pdfBuffer.length,
            chrome_path: process.env.PUPPETEER_EXECUTABLE_PATH || 'auto-detect'
        });

    } catch (error) {
        console.error('❌ Test PDF generation failed:', error.message);
        console.error('🔍 Full error:', error);

        res.status(500).json({
            success: false,
            error: 'Failed to generate test PDF',
            message: error.message,
            chrome_path: process.env.PUPPETEER_EXECUTABLE_PATH || 'auto-detect',
            details: process.env.NODE_ENV === 'development' ? error.stack : 'Check server logs for details'
        });
    }
});

// Rota principal para gerar PDF
app.post('/generate-pdf', async (req, res) => {
    try {
        const { html, options = {} } = req.body;

        if (!html) {
            return res.status(400).json({ error: 'HTML content is required' });
        }

        console.log('📄 Received PDF generation request');
        console.log('📏 HTML length:', html.length);
        console.log('⚙️ Options:', JSON.stringify(options, null, 2));

        // Configurações padrão para certificados
        const defaultOptions = {
            format: 'A4',
            landscape: true,
            margin: {
                top: '10mm',
                right: '10mm',
                bottom: '10mm',
                left: '10mm'
            },
            printBackground: true,
            preferCSSPageSize: true
        };

        const pdfOptions = { ...defaultOptions, ...options };

        // Inicializar Puppeteer
        const browser = await puppeteer.launch({
            headless: 'new',
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-gpu'
            ]
        });

        const page = await browser.newPage();

        // Definir conteúdo HTML
        await page.setContent(html, {
            waitUntil: 'networkidle0',
            timeout: 30000
        });

        console.log('🎨 HTML content loaded successfully');

        // Gerar PDF
        const pdfBuffer = await page.pdf(pdfOptions);

        await browser.close();

        console.log('✅ PDF generated successfully, size:', pdfBuffer.length, 'bytes');

        // Retornar PDF como base64
        const pdfBase64 = pdfBuffer.toString('base64');

        res.json({
            success: true,
            pdf: pdfBase64,
            message: 'PDF generated successfully'
        });

    } catch (error) {
        console.error('❌ Error generating PDF:', error);
        res.status(500).json({
            error: 'Failed to generate PDF',
            message: error.message,
            stack: process.env.NODE_ENV === 'development' ? error.stack : undefined
        });
    }
});

// Rota para gerar PDF direto de URL
app.post('/generate-pdf-from-url', async (req, res) => {
    try {
        const { url, options = {} } = req.body;

        if (!url) {
            return res.status(400).json({ error: 'URL is required' });
        }

        const defaultOptions = {
            format: 'A4',
            landscape: true,
            margin: {
                top: '10mm',
                right: '10mm',
                bottom: '10mm',
                left: '10mm'
            },
            printBackground: true,
            preferCSSPageSize: true
        };

        const pdfOptions = { ...defaultOptions, ...options };

        const browser = await puppeteer.launch({
            headless: 'new',
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-gpu'
            ]
        });

        const page = await browser.newPage();
        await page.goto(url, { waitUntil: 'networkidle0', timeout: 30000 });

        const pdfBuffer = await page.pdf(pdfOptions);
        await browser.close();

        const pdfBase64 = pdfBuffer.toString('base64');

        res.json({
            success: true,
            pdf: pdfBase64,
            message: 'PDF generated successfully from URL'
        });

    } catch (error) {
        console.error('Error generating PDF from URL:', error);
        res.status(500).json({
            error: 'Failed to generate PDF from URL',
            message: error.message
        });
    }
});

app.listen(PORT, () => {
    console.log(`🚀 PDF Service running on port ${PORT}`);
    console.log(`📋 Health check: http://localhost:${PORT}/health`);
});

// Graceful shutdown
process.on('SIGINT', () => {
    console.log('🛑 Shutting down PDF service...');
    process.exit(0);
});

process.on('SIGTERM', () => {
    console.log('🛑 Shutting down PDF service...');
    process.exit(0);
});
