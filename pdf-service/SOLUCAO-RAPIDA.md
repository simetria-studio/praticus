# 🚨 SOLUÇÃO RÁPIDA - Chrome não encontrado

## ❌ **Seu Erro:**
```
❌ Erro no Puppeteer: Could not find Chrome (ver. 121.0.6167.85)
```

## ✅ **Solução Imediata:**

### **1. 🔍 Verificar Sistema**
```bash
# Execute primeiro para diagnosticar
chmod +x pre-check.sh
./pre-check.sh
```

### **2. 🚀 Instalar Chrome Manualmente**
```bash
# Instalar Google Chrome
sudo dnf install -y wget
wget -q -O - https://dl.google.com/linux/linux_signing_key.pub | sudo rpm --import -
sudo dnf config-manager --add-repo http://dl.google.com/linux/chrome/rpm/stable/x86_64
sudo dnf install -y google-chrome-stable

# OU instalar Chromium (alternativa)
sudo dnf install -y chromium
```

### **3. 🔧 Executar Script Corrigido**
```bash
# Execute o novo script (com correções)
sudo ./production-setup.sh
```

## 🎯 **O que o Script Corrigido Faz:**

1. ✅ **Detecta Chrome automaticamente** em vários locais
2. ✅ **Instala Chrome** se não encontrar
3. ✅ **Baixa via Puppeteer** como último recurso
4. ✅ **Configura variáveis de ambiente** corretas
5. ✅ **Testa todas as configurações** antes de continuar

## 📊 **Resultado Esperado:**
```
✅ Chrome encontrado em: /usr/bin/google-chrome-stable
🔧 Configurado PUPPETEER_EXECUTABLE_PATH=/usr/bin/google-chrome-stable
✅ Puppeteer funcionando!
✅ PDF Service configurado com sucesso!
```

## 🆘 **Se Ainda Não Funcionar:**

### **Opção A - Forçar Download do Chrome:**
```bash
cd /var/www/pdf-service
export PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=false
npx puppeteer browsers install chrome
```

### **Opção B - Usar Chromium do Sistema:**
```bash
# Instalar Chromium
sudo dnf install -y chromium

# Configurar manualmente
export PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium
echo "PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium" >> .env
```

### **Opção C - Teste Manual:**
```bash
# Testar se Chrome funciona
/usr/bin/google-chrome-stable --version
/usr/bin/chromium --version

# Testar Puppeteer isoladamente
node -e "
const puppeteer = require('puppeteer');
puppeteer.launch({
  executablePath: '/usr/bin/google-chrome-stable',
  args: ['--no-sandbox']
}).then(browser => {
  console.log('✅ Funcionou!');
  browser.close();
}).catch(err => console.error('❌', err.message));
"
```

## 🎉 **Depois que Funcionar:**

1. **Teste o serviço:**
   ```bash
   curl http://localhost:3001/health
   ```

2. **Configure o Laravel:**
   ```bash
   # Adicione no .env do Laravel
   PDF_SERVICE_URL=http://SEU-SERVIDOR-IP:3001
   ```

3. **Teste um certificado** no sistema Laravel

---

## 📋 **Resumo dos Comandos:**

```bash
# 1. Verificar sistema
./pre-check.sh

# 2. Instalar Chrome
sudo dnf install -y google-chrome-stable

# 3. Executar setup corrigido
sudo ./production-setup.sh

# 4. Testar serviço
curl http://localhost:3001/health
```

**Agora deve funcionar perfeitamente!** 🚀
