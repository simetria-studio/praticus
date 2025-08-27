# 🚀 PDF Certificate Service (Node.js + Puppeteer)

Microserviço para gerar PDFs **idênticos** ao preview do navegador usando Puppeteer.

## 📋 **Pré-requisitos**

1. **Node.js** (versão 18 ou superior)
   - Download: https://nodejs.org/

## ⚡ **Instalação Rápida**

### Windows:
```bash
# 1. Entre na pasta do microserviço
cd pdf-service

# 2. Execute o script de inicialização
start.bat
```

### Manual (qualquer OS):
```bash
# 1. Entre na pasta
cd pdf-service

# 2. Instale as dependências
npm install

# 3. Inicie o servidor
npm start
```

## 🔍 **Debug e Testes**

### 1. **Teste via Navegador:**
Abra `test.html` no navegador para testar interativamente:
- Health check do serviço
- Geração de PDF simples
- Download direto do PDF

### 2. **Teste via PowerShell:**
```powershell
# Execute o script de teste completo
./test.ps1
```

### 3. **Teste via Linha de Comando:**
```bash
# Health check
curl http://localhost:3001/health

# Teste de PDF simples
curl -X POST http://localhost:3001/test-pdf
```

## 🎯 **Como funciona**

1. **Laravel** envia HTML para o microserviço
2. **Puppeteer** renderiza o HTML (como Chrome real)
3. **PDF perfeito** é gerado e retornado

## 🔧 **Configuração no Laravel**

O código já está pronto! A hierarquia de geração é:

```php
1º. generateNodejsPDF()     // ⭐ Melhor qualidade (Node.js)
2º. generatePreviewStylePDF() // 🔄 Fallback (mPDF otimizado)
3º. generateMPDF()           // 🔄 Fallback (mPDF simples)
4º. generatePDFWithDomPDF()  // 🔄 Fallback final (DomPDF)
```

## 🚀 **Iniciar o Serviço**

```bash
# Desenvolvimento (com reload automático)
npm run dev

# Produção
npm start
```

## 📡 **Endpoints**

### `GET /health`
Verifica se o serviço está rodando.

### `POST /test-pdf`
Gera um PDF de teste simples (para debug):
```json
// Não precisa de parâmetros
{}
```

### `POST /generate-pdf`
Gera PDF de HTML:
```json
{
  "html": "<html>...</html>",
  "options": {
    "format": "A4",
    "landscape": true,
    "margin": {
      "top": "10mm",
      "right": "10mm",
      "bottom": "10mm",
      "left": "10mm"
    }
  }
}
```

### `POST /generate-pdf-from-url`
Gera PDF de URL:
```json
{
  "url": "http://localhost:8000/certificates/1/preview",
  "options": { ... }
}
```

## ✅ **Vantagens desta solução**

- ✅ **PDF idêntico** ao navegador
- ✅ **CSS moderno** (flexbox, grid, gradients)
- ✅ **Fontes perfeitas** e anti-aliasing
- ✅ **Uma única página** garantida
- ✅ **Assinaturas alinhadas** corretamente
- ✅ **Funciona em qualquer ambiente**

## 🛠️ **Solução de Problemas**

### ❌ Erro "Unexpected token '<', '<!DOCTYPE'... is not valid JSON"
**Causa:** O microserviço Node.js não está rodando na porta 3001.

**✅ Solução:**
1. **Verifique se o serviço está rodando:**
   ```bash
   # Windows - execute este script para verificar
   ./check-service.bat
   
   # Ou manualmente
   netstat -an | findstr :3001
   ```

2. **Inicie o serviço se não estiver rodando:**
   ```bash
   # Windows (recomendado)
   ./start.bat
   
   # Ou manualmente
   npm install
   npm start
   ```

3. **Aguarde a mensagem de confirmação:**
   ```
   🚀 PDF Service running on port 3001
   📋 Health check: http://localhost:3001/health
   ```

4. **Teste a conectividade:**
   - Abra `test.html` no navegador
   - Ou execute `./test.ps1`

### PDF está saindo em branco
1. **Teste o serviço:**
   ```bash
   # Execute o teste básico
   powershell ./test.ps1
   ```

2. **Verifique os logs do Laravel:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Verifique os logs do Node.js:**
   - Logs aparecem no terminal onde rodou `npm start`

### Erro "Node.js não encontrado"
- Instale o Node.js: https://nodejs.org/

### Porta 3001 em uso
```bash
# Windows - matar processo na porta 3001
netstat -ano | findstr :3001
taskkill /PID <PID> /F

# Ou use um script automático
./check-service.bat
```

### Erro de permissão (Linux/Mac)
```bash
sudo npm install
```

### Erro de conectividade do Laravel
1. **Certifique-se que o serviço está rodando:**
   ```bash
   curl http://localhost:3001/health
   ```

2. **Verifique se não há firewall bloqueando:**
   - Windows: Libere porta 3001 no firewall
   - Laravel deve conseguir acessar localhost:3001

### ⚡ Scripts de Diagnóstico

| Script | Função |
|--------|--------|
| `start.bat` | Inicia o serviço automaticamente |
| `check-service.bat` | Verifica se o serviço está rodando |
| `test.ps1` | Executa bateria completa de testes |
| `test.html` | Interface visual para testes |

## 🌐 **Deploy em Produção**

### PM2 (Recomendado)
```bash
npm install -g pm2
pm2 start server.js --name "pdf-service"
pm2 startup
pm2 save
```

### Docker
```dockerfile
FROM node:18-alpine
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
EXPOSE 3001
CMD ["npm", "start"]
```

## 📊 **Monitoramento**

### Logs do Serviço
O serviço exibe logs detalhados:
- 📄 Requisições recebidas
- 📏 Tamanho do HTML
- 🎨 Status de carregamento
- ✅ PDFs gerados com sucesso
- ❌ Erros detalhados

### Exemplo de Log Normal:
```
🚀 PDF Service running on port 3001
📄 Received PDF generation request
📏 HTML length: 15420
🎨 HTML content loaded successfully
✅ PDF generated successfully, size: 245760 bytes
```

## 🎉 **Resultado**

Agora seus certificados serão **idênticos** ao preview, com:
- 🎨 **Bordas decorativas**
- 🔵 **Círculos nos cantos**
- 🌈 **Gradientes perfeitos**
- 📝 **Assinaturas alinhadas**
- 📄 **Uma única página**

**Qualidade profissional garantida!** 🚀
