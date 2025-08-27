@echo off
echo 🔍 Verificando PDF Service...

REM Testa se o serviço está rodando na porta 3001
netstat -an | findstr ":3001" >nul
if errorlevel 1 (
    echo ❌ Serviço NÃO está rodando na porta 3001
    echo.
    echo 💡 Para iniciar o serviço:
    echo    1. Abra um novo terminal
    echo    2. cd pdf-service
    echo    3. npm start
    echo.
    echo ⚠️  Aguarde aparecer: "🚀 PDF Service running on port 3001"
    pause
    exit /b 1
) else (
    echo ✅ Porta 3001 está em uso - serviço provavelmente rodando
    echo.
    echo 🌐 Testando conectividade...

    REM Testa a conectividade HTTP
    curl -s http://localhost:3001/health >nul 2>&1
    if errorlevel 1 (
        echo ❌ Porta 3001 ocupada mas serviço não responde
        echo 💡 Talvez outro processo esteja usando a porta
        pause
        exit /b 1
    ) else (
        echo ✅ PDF Service está funcionando corretamente!
        echo.
        echo 🎉 Você pode usar o sistema normalmente.
        echo 🌐 Teste: http://localhost:3001/health
        pause
    )
)
