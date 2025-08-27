@echo off
echo 🚀 Iniciando PDF Certificate Service...

REM Verifica se Node.js está instalado
node --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Node.js não encontrado. Instale o Node.js primeiro!
    echo 💡 Download: https://nodejs.org/
    pause
    exit /b 1
)

REM Instala dependências se necessário
if not exist node_modules (
    echo 📦 Instalando dependências...
    npm install
)

echo ✅ Iniciando servidor na porta 3001...
npm start
