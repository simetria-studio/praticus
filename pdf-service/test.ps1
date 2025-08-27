# Script de teste para PDF Service
Write-Host "🚀 Testando PDF Service..." -ForegroundColor Cyan

# Teste 1: Health Check
Write-Host "`n1️⃣ Testando conectividade..." -ForegroundColor Yellow
try {
    $healthResponse = Invoke-RestMethod -Uri "http://localhost:3001/health" -Method Get
    Write-Host "✅ Health Check: $($healthResponse.message)" -ForegroundColor Green
} catch {
    Write-Host "❌ Erro no Health Check: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "💡 Certifique-se de que o serviço está rodando: npm start" -ForegroundColor Yellow
    exit 1
}

# Teste 2: Puppeteer
Write-Host "`n2️⃣ Testando inicialização do Puppeteer..." -ForegroundColor Yellow
try {
    $puppeteerResponse = Invoke-RestMethod -Uri "http://localhost:3001/test-puppeteer" -Method Post -ContentType "application/json"

    if ($puppeteerResponse.success) {
        Write-Host "✅ Puppeteer inicializado com sucesso!" -ForegroundColor Green
        if ($puppeteerResponse.version) {
            Write-Host "📦 Versão do Puppeteer: $($puppeteerResponse.version)" -ForegroundColor Blue
        }
    } else {
        Write-Host "❌ Falha na inicialização do Puppeteer: $($puppeteerResponse.message)" -ForegroundColor Red
        Write-Host "💡 Sugestão: $($puppeteerResponse.suggestion)" -ForegroundColor Yellow
    }
} catch {
    Write-Host "❌ Erro no teste do Puppeteer: $($_.Exception.Message)" -ForegroundColor Red
}

# Teste 3: PDF Simples
Write-Host "`n3️⃣ Testando geração de PDF simples..." -ForegroundColor Yellow
try {
    $testResponse = Invoke-RestMethod -Uri "http://localhost:3001/test-pdf" -Method Post -ContentType "application/json"

    if ($testResponse.success) {
        Write-Host "✅ PDF de teste gerado com sucesso!" -ForegroundColor Green
        Write-Host "📏 Tamanho do PDF: $([math]::Round($testResponse.pdf.Length * 0.75 / 1024)) KB" -ForegroundColor Blue

        # Salvar PDF de teste
        $pdfBytes = [System.Convert]::FromBase64String($testResponse.pdf)
        $testFileName = "teste-pdf-$(Get-Date -Format 'yyyyMMdd-HHmmss').pdf"
        [System.IO.File]::WriteAllBytes($testFileName, $pdfBytes)
        Write-Host "💾 PDF salvo como: $testFileName" -ForegroundColor Green
    } else {
        Write-Host "❌ Falha na geração do PDF: $($testResponse.message)" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Erro na geração do PDF: $($_.Exception.Message)" -ForegroundColor Red
}

# Teste 4: PDF do Certificado (simulação)
Write-Host "`n4️⃣ Testando geração de certificado..." -ForegroundColor Yellow
$certificateHtml = @"
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificado Teste</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .certificate-container {
            border: 30px solid #1e40af;
            padding: 20px;
            text-align: center;
            min-height: 400px;
        }
        h1 { color: #1e40af; font-size: 14px; margin: 5px 0; }
        h2 { color: #1e40af; font-size: 16px; margin: 15px 0; }
        .content { font-size: 10px; margin: 15px 0; }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .signature {
            flex: 1;
            text-align: center;
            margin: 0 10px;
        }
        .signature-line {
            border-bottom: 1px solid black;
            height: 20px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <h1>REPÚBLICA FEDERATIVA DO BRASIL</h1>
        <h1>ESTADO DO PIAUÍ</h1>
        <h1>SECRETARIA DE ESTADO DA EDUCAÇÃO</h1>

        <h2>CERTIFICADO DE CONCLUSÃO DO ENSINO MÉDIO</h2>

        <div class="content">
            <p>Certificamos que <strong>ALUNO TESTE</strong> concluiu o Ensino Médio.</p>
        </div>

        <div class="signatures">
            <div class="signature">
                <div class="signature-line"></div>
                <div>SECRETÁRIO(A)</div>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <div>DIRETOR(A)</div>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <div>CONCLUINTE</div>
            </div>
        </div>
    </div>
</body>
</html>
"@

try {
    $certificateBody = @{
        html = $certificateHtml
        options = @{
            format = "A4"
            landscape = $true
            margin = @{
                top = "10mm"
                right = "10mm"
                bottom = "10mm"
                left = "10mm"
            }
            printBackground = $true
        }
    } | ConvertTo-Json -Depth 10

    $certificateResponse = Invoke-RestMethod -Uri "http://localhost:3001/generate-pdf" -Method Post -Body $certificateBody -ContentType "application/json"

    if ($certificateResponse.success) {
        Write-Host "✅ Certificado PDF gerado com sucesso!" -ForegroundColor Green
        Write-Host "📏 Tamanho: $([math]::Round($certificateResponse.pdf.Length * 0.75 / 1024)) KB" -ForegroundColor Blue

        # Salvar certificado PDF
        $pdfBytes = [System.Convert]::FromBase64String($certificateResponse.pdf)
        $certFileName = "certificado-teste-$(Get-Date -Format 'yyyyMMdd-HHmmss').pdf"
        [System.IO.File]::WriteAllBytes($certFileName, $pdfBytes)
        Write-Host "💾 Certificado salvo como: $certFileName" -ForegroundColor Green
    } else {
        Write-Host "❌ Falha na geração do certificado: $($certificateResponse.message)" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Erro na geração do certificado: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n🎉 Teste concluído!" -ForegroundColor Cyan
Write-Host "📂 Verifique os arquivos PDF gerados na pasta atual." -ForegroundColor Yellow
