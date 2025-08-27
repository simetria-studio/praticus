# 🔧 Guia de Diagnóstico - PDF Service

## 🚨 **Problema Atual**
✅ **Health Check funcionando** (serviço rodando)  
❌ **Teste de PDF falhando** (problema com Puppeteer)

## 📋 **Passos de Diagnóstico**

### **1. Reiniciar o Serviço com Logs Detalhados**

No terminal onde o serviço está rodando:
1. **Pare o serviço**: `Ctrl+C`
2. **Reinicie com logs**: `npm start`
3. **Observe as mensagens**

### **2. Executar Testes na Ordem**

```bash
# 1. Abra test.html no navegador
# 2. Execute os testes nesta ordem:
#    - Health Check (deve funcionar ✅)
#    - Teste do Puppeteer (novo teste)
#    - Teste de PDF Simples
```

### **3. Verificar Logs do Terminal**

Quando você clicar em "Testar Puppeteer", deve aparecer no terminal:
```
🧪 Testing Puppeteer initialization...
🚀 Launching Puppeteer browser...
✅ Puppeteer browser launched successfully
✅ Browser closed successfully
```

## 🔍 **Possíveis Problemas**

### **Problema 1: Puppeteer não instalado corretamente**
**Sintomas:**
```
❌ Puppeteer test failed: Cannot find module 'puppeteer'
```

**Solução:**
```bash
cd pdf-service
npm install puppeteer --force
npm start
```

### **Problema 2: Chrome/Chromium não encontrado**
**Sintomas:**
```
❌ Could not find expected browser (chrome) locally
```

**Solução:**
```bash
# Reinstalar Puppeteer com Chrome
cd pdf-service
npm uninstall puppeteer
npm install puppeteer
npm start
```

### **Problema 3: Permissões no Windows**
**Sintomas:**
```
❌ spawn EACCES
```

**Solução:**
```bash
# Execute como administrador
# Ou instale em modo global
npm install -g puppeteer
```

### **Problema 4: Antivírus bloqueando**
**Sintomas:**
- Puppeteer inicializa mas trava
- PDF não é gerado

**Solução:**
- Adicione exceção no antivírus para a pasta `node_modules`
- Ou desabilite temporariamente o antivírus

## 🧪 **Testes Específicos**

### **Teste A: Puppeteer Manual**
```bash
cd pdf-service
node -e "const puppeteer = require('puppeteer'); puppeteer.launch().then(browser => { console.log('✅ Puppeteer OK'); browser.close(); });"
```

### **Teste B: Dependências**
```bash
cd pdf-service
npm list puppeteer
npm list express
```

### **Teste C: Reinstalação Completa**
```bash
cd pdf-service
rm -rf node_modules
rm package-lock.json
npm install
npm start
```

## 📊 **Como Reportar Problema**

Se os testes ainda falharem, me envie:

1. **Resultado do teste.html** (screenshot)
2. **Logs do terminal** (texto completo)
3. **Versão do Node.js**: `node --version`
4. **Sistema operacional**
5. **Tem antivírus ativo?**

## 🎯 **Próximos Passos**

1. ✅ **Execute `test.html`** - veja qual teste falha
2. 📋 **Copie os logs** do terminal Node.js
3. 🔧 **Siga as soluções** baseadas nos sintomas
4. 📤 **Reporte** se ainda não funcionar

**O objetivo é fazer todos os testes ficarem verdes! 🟢**
