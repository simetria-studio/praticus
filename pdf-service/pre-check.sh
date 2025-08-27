#!/bin/bash

echo "🔍 Verificação Pré-Instalação - PDF Service"
echo "========================================"

# Verificar Node.js
echo -n "📦 Node.js: "
if command -v node &> /dev/null; then
    NODE_VERSION=$(node --version)
    echo "✅ $NODE_VERSION"

    # Verificar se a versão é >= 18
    NODE_MAJOR=$(echo $NODE_VERSION | cut -d'.' -f1 | tr -d 'v')
    if [ "$NODE_MAJOR" -lt 18 ]; then
        echo "⚠️  Aviso: Node.js versão $NODE_VERSION pode não ser compatível. Recomendado: >= 18"
    fi
else
    echo "❌ Não instalado"
    echo "💡 Instale com: curl -fsSL https://rpm.nodesource.com/setup_lts.x | sudo bash - && sudo dnf install -y nodejs"
fi

# Verificar NPM
echo -n "📦 NPM: "
if command -v npm &> /dev/null; then
    NPM_VERSION=$(npm --version)
    echo "✅ $NPM_VERSION"
else
    echo "❌ Não instalado"
fi

# Verificar Chrome/Chromium
echo -n "🌐 Chrome/Chromium: "
CHROME_FOUND=false
CHROME_PATHS=(
    "/usr/bin/google-chrome"
    "/usr/bin/google-chrome-stable"
    "/usr/bin/chromium"
    "/usr/bin/chromium-browser"
    "/snap/bin/chromium"
    "/usr/bin/chrome"
)

for path in "${CHROME_PATHS[@]}"; do
    if [ -f "$path" ]; then
        echo "✅ Encontrado em $path"
        CHROME_FOUND=true

        # Verificar versão se possível
        if [ -x "$path" ]; then
            VERSION_OUTPUT=$($path --version 2>/dev/null || echo "versão não disponível")
            echo "   📋 $VERSION_OUTPUT"
        fi
        break
    fi
done

if [ "$CHROME_FOUND" = false ]; then
    echo "❌ Não encontrado"
    echo "💡 Será instalado automaticamente durante a configuração"
fi

# Verificar dependências do sistema
echo ""
echo "📚 Verificando dependências do sistema:"

DEPS=(
    "gtk3"
    "libX11"
    "libXcomposite"
    "libXcursor"
    "cups-libs"
    "liberation-fonts"
)

for dep in "${DEPS[@]}"; do
    echo -n "   $dep: "
    if rpm -q $dep &> /dev/null; then
        echo "✅ Instalado"
    else
        echo "❌ Não encontrado (será instalado)"
    fi
done

# Verificar porta 3001
echo ""
echo -n "🔌 Porta 3001: "
if netstat -tuln 2>/dev/null | grep -q ":3001 "; then
    echo "❌ Em uso"
    echo "💡 Pare o processo que está usando a porta 3001"
    netstat -tuln | grep ":3001 "
else
    echo "✅ Disponível"
fi

# Verificar permissões sudo
echo -n "🔐 Permissões sudo: "
if sudo -n true 2>/dev/null; then
    echo "✅ Disponível"
else
    echo "❌ Necessário"
    echo "💡 Execute o script com: sudo ./production-setup.sh"
fi

# Verificar espaço em disco
echo -n "💾 Espaço em disco: "
AVAILABLE_GB=$(df . | awk 'NR==2 {print int($4/1024/1024)}')
if [ "$AVAILABLE_GB" -gt 1 ]; then
    echo "✅ ${AVAILABLE_GB}GB disponível"
else
    echo "⚠️  Apenas ${AVAILABLE_GB}GB disponível (recomendado: >1GB)"
fi

# Verificar memória
echo -n "🧠 Memória RAM: "
TOTAL_MEM_GB=$(free -g | awk 'NR==2{print $2}')
AVAILABLE_MEM_GB=$(free -g | awk 'NR==2{print $7}')
if [ "$TOTAL_MEM_GB" -gt 1 ]; then
    echo "✅ ${TOTAL_MEM_GB}GB total, ${AVAILABLE_MEM_GB}GB disponível"
else
    echo "⚠️  ${TOTAL_MEM_GB}GB total (recomendado: >1GB)"
fi

echo ""
echo "🎯 Resumo:"
echo "========"

# Contar problemas
PROBLEMS=0

if ! command -v node &> /dev/null; then
    echo "❌ Node.js não instalado"
    PROBLEMS=$((PROBLEMS + 1))
fi

if [ "$CHROME_FOUND" = false ]; then
    echo "⚠️  Chrome/Chromium não encontrado (será resolvido automaticamente)"
fi

if netstat -tuln 2>/dev/null | grep -q ":3001 "; then
    echo "❌ Porta 3001 em uso"
    PROBLEMS=$((PROBLEMS + 1))
fi

if ! sudo -n true 2>/dev/null; then
    echo "❌ Permissões sudo necessárias"
    PROBLEMS=$((PROBLEMS + 1))
fi

if [ "$PROBLEMS" -eq 0 ]; then
    echo "✅ Sistema pronto para instalação!"
    echo ""
    echo "🚀 Próximo passo:"
    echo "   sudo ./production-setup.sh"
else
    echo "❌ $PROBLEMS problema(s) encontrado(s)"
    echo ""
    echo "💡 Resolva os problemas acima antes de continuar"
fi
