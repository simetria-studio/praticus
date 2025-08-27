#!/bin/bash

echo "🚀 Configurando PDF Service em Produção - Linux"

# Verificar se está no diretório correto
if [ ! -f "package.json" ]; then
    echo "❌ Execute este script na pasta pdf-service"
    exit 1
fi

# Verificar se tem permissões sudo
if ! sudo -n true 2>/dev/null; then
    echo "❌ Este script precisa de permissões sudo"
    echo "💡 Execute: sudo $0"
    exit 1
fi

# Criar diretório de logs
mkdir -p logs

# Instalar dependências básicas se não estiverem instaladas
echo "📦 Verificando dependências do sistema..."
sudo dnf install -y chromium google-chrome-stable 2>/dev/null || {
    echo "⚠️ Chrome/Chromium não instalado via DNF, tentando outras formas..."

    # Tentar instalar Google Chrome manualmente
    if ! command -v google-chrome &> /dev/null && ! command -v chromium-browser &> /dev/null; then
        echo "📥 Baixando e instalando Google Chrome..."
        wget -q -O - https://dl.google.com/linux/linux_signing_key.pub | sudo rpm --import -
        sudo dnf config-manager --add-repo http://dl.google.com/linux/chrome/rpm/stable/x86_64
        sudo dnf install -y google-chrome-stable 2>/dev/null || {
            echo "⚠️ Falha ao instalar Chrome, continuando com configuração alternativa..."
        }
    fi
}

# Instalar dependências Node.js
echo "📦 Instalando dependências Node.js..."
npm install --production

# Configurar variáveis de ambiente para Puppeteer
echo "⚙️ Configurando variáveis de ambiente..."
export NODE_ENV=production
export PORT=3001

# Encontrar executável do Chrome/Chromium
echo "🔍 Localizando Chrome/Chromium..."
CHROME_PATH=""

# Possíveis localizações do Chrome/Chromium
CHROME_LOCATIONS=(
    "/usr/bin/google-chrome"
    "/usr/bin/google-chrome-stable"
    "/usr/bin/chromium"
    "/usr/bin/chromium-browser"
    "/snap/bin/chromium"
    "/usr/bin/chrome"
)

for location in "${CHROME_LOCATIONS[@]}"; do
    if [ -f "$location" ]; then
        CHROME_PATH="$location"
        echo "✅ Chrome encontrado em: $CHROME_PATH"
        break
    fi
done

# Se não encontrou Chrome instalado, baixar via Puppeteer
if [ -z "$CHROME_PATH" ]; then
    echo "⬇️ Chrome não encontrado, baixando via Puppeteer..."
    export PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=false
    npx puppeteer browsers install chrome || {
        echo "❌ Falha ao baixar Chrome via Puppeteer"
        echo "💡 Tentando instalar Chromium manualmente..."
        sudo dnf install -y chromium
        CHROME_PATH="/usr/bin/chromium"
    }
fi

# Configurar variável de ambiente para Chrome
if [ -n "$CHROME_PATH" ]; then
    export PUPPETEER_EXECUTABLE_PATH="$CHROME_PATH"
    echo "🔧 Configurado PUPPETEER_EXECUTABLE_PATH=$CHROME_PATH"
fi

# Instalar PM2 globalmente se não estiver instalado
if ! command -v pm2 &> /dev/null; then
    echo "🔧 Instalando PM2..."
    sudo npm install -g pm2
fi

# Testar Puppeteer com configuração correta
echo "🧪 Testando Puppeteer..."
node -e "
const puppeteer = require('puppeteer');
(async () => {
  try {
    const browserOptions = {
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

    // Usar executablePath se encontrou Chrome
    const chromePath = process.env.PUPPETEER_EXECUTABLE_PATH;
    if (chromePath) {
      console.log('🔧 Usando Chrome em:', chromePath);
      browserOptions.executablePath = chromePath;
    }

    const browser = await puppeteer.launch(browserOptions);
    console.log('✅ Puppeteer funcionando!');
    await browser.close();
    process.exit(0);
  } catch (error) {
    console.error('❌ Erro no Puppeteer:', error.message);
    console.error('🔍 Stack:', error.stack);
    process.exit(1);
  }
})();
"

if [ $? -eq 0 ]; then
    echo "✅ Puppeteer configurado corretamente!"
else
    echo "❌ Erro na configuração do Puppeteer"
    echo "💡 Verificando alternativas..."

    # Tentar com path absoluto do Chromium
    echo "🔄 Tentando com Chromium do sistema..."
    export PUPPETEER_EXECUTABLE_PATH="/usr/bin/chromium"

    node -e "
    const puppeteer = require('puppeteer');
    (async () => {
      try {
        const browser = await puppeteer.launch({
          executablePath: '/usr/bin/chromium',
          headless: 'new',
          args: ['--no-sandbox', '--disable-setuid-sandbox']
        });
        console.log('✅ Funcionando com Chromium!');
        await browser.close();
      } catch (error) {
        console.error('❌ Ainda com erro:', error.message);
      }
    })();
    "
fi

# Criar arquivo de configuração de ambiente
echo "📝 Criando arquivo de configuração..."
cat > .env << EOF
NODE_ENV=production
PORT=3001
PUPPETEER_EXECUTABLE_PATH=$CHROME_PATH
PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true
EOF

# Configurar PM2 com as variáveis de ambiente
echo "🔧 Configurando PM2..."
pm2 delete pdf-service 2>/dev/null || true

# Usar ecosystem.config.js se existir, senão usar configuração inline
if [ -f "ecosystem.config.js" ]; then
    pm2 start ecosystem.config.js --env production
else
    pm2 start server.js --name "pdf-service" \
        --time \
        --env NODE_ENV=production \
        --env PORT=3001 \
        --env PUPPETEER_EXECUTABLE_PATH="$CHROME_PATH"
fi

# Configurar inicialização automática
echo "🔄 Configurando inicialização automática..."
pm2 startup > /dev/null 2>&1 || echo "⚠️ Configure manualmente: pm2 startup"
pm2 save

# Configurar firewall (se necessário)
echo "🔥 Configurando firewall..."
sudo firewall-cmd --permanent --add-port=3001/tcp 2>/dev/null || echo "⚠️ Firewall não configurado (opcional)"
sudo firewall-cmd --reload 2>/dev/null || echo "⚠️ Firewall reload pulado"

# Aguardar o serviço inicializar
echo "⏳ Aguardando serviço inicializar..."
sleep 5

# Testar o serviço
echo "🧪 Testando serviço..."
curl -s http://localhost:3001/health | grep -q "PDF Service is running"

if [ $? -eq 0 ]; then
    echo ""
    echo "🎉 ======================================"
    echo "✅ PDF Service configurado com sucesso!"
    echo "========================================"
    echo "🌐 Health Check: http://$(hostname -I | awk '{print $1}'):3001/health"
    echo "📊 Monitorar: pm2 logs pdf-service"
    echo "🔄 Reiniciar: pm2 restart pdf-service"
    echo "🔍 Status: pm2 status"
    echo ""
    echo "🎯 Configuração do Laravel:"
    echo "PDF_SERVICE_URL=http://$(hostname -I | awk '{print $1}'):3001"
    echo ""
else
    echo ""
    echo "❌ ================================"
    echo "   Erro na configuração do serviço"
    echo "================================"
    echo "📋 Diagnóstico:"
    echo "   pm2 logs pdf-service"
    echo "   pm2 status"
    echo "   curl http://localhost:3001/health"
fi
