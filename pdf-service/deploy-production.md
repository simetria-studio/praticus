# 🚀 Deploy em Produção - AlmaLinux

## 📋 **Pré-requisitos**

1. **Servidor AlmaLinux** com acesso SSH
2. **Usuário com sudo** 
3. **Porta 3001 disponível**
4. **Pelo menos 1GB RAM livre**

## 🔧 **Passo a Passo**

### **1. Preparar o Servidor**

```bash
# Conectar no servidor
ssh usuario@seu-servidor.com

# Atualizar sistema
sudo dnf update -y

# Instalar Node.js LTS
curl -fsSL https://rpm.nodesource.com/setup_lts.x | sudo bash -
sudo dnf install -y nodejs git curl

# Verificar instalação
node --version  # deve ser >= 18
npm --version
```

### **2. Instalar Dependências do Puppeteer**

```bash
# Instalar Chrome/Chromium e dependências
sudo dnf install -y \
    chromium \
    gtk3 \
    libX11 \
    libXcomposite \
    libXcursor \
    libXdamage \
    libXext \
    libXi \
    libXrandr \
    libXss \
    libXtst \
    cups-libs \
    libdrm \
    gtk3-devel \
    libxkbcommon \
    at-spi2-atk \
    mesa-libgbm \
    xorg-x11-server-Xvfb \
    liberation-fonts \
    google-noto-fonts \
    google-noto-emoji-fonts
```

### **3. Configurar o Microserviço**

```bash
# Criar diretório
sudo mkdir -p /var/www/pdf-service
sudo chown $USER:$USER /var/www/pdf-service
cd /var/www/pdf-service

# Upload dos arquivos (escolha uma opção)

# Opção A: SCP (do seu computador local)
scp -r pdf-service/* usuario@servidor:/var/www/pdf-service/

# Opção B: Git
git clone <seu-repositorio> .

# Opção C: Copiar manualmente
# Faça upload dos arquivos via FTP/SFTP
```

### **4. Executar Script de Configuração**

```bash
# Dar permissão de execução
chmod +x production-setup.sh

# Executar configuração
./production-setup.sh
```

Se tudo der certo, você verá:
```
✅ PDF Service configurado com sucesso!
🌐 Acesse: http://SEU-IP:3001/health
```

### **5. Configurar Laravel**

No seu projeto Laravel, adicione no `.env`:

```bash
# URL do microserviço PDF (opcional - auto-detecta se não definir)
PDF_SERVICE_URL=http://SEU-SERVIDOR-IP:3001

# ou se usar domínio
PDF_SERVICE_URL=http://seu-dominio.com:3001
```

### **6. Testar o Sistema**

```bash
# No servidor, testar o microserviço
curl http://localhost:3001/health

# Teste mais completo
curl -X POST http://localhost:3001/test-pdf
```

## 🔄 **Comandos de Gerenciamento**

### **Monitoramento**
```bash
# Ver status do serviço
pm2 status

# Ver logs em tempo real
pm2 logs pdf-service

# Ver logs específicos
pm2 logs pdf-service --lines 100

# Monitoramento detalhado
pm2 monit
```

### **Controle do Serviço**
```bash
# Parar serviço
pm2 stop pdf-service

# Iniciar serviço
pm2 start pdf-service

# Reiniciar serviço
pm2 restart pdf-service

# Recarregar configuração
pm2 reload pdf-service
```

### **Atualizações**
```bash
# Atualizar código
cd /var/www/pdf-service
git pull  # ou fazer upload dos novos arquivos

# Reinstalar dependências se necessário
npm install --production

# Reiniciar serviço
pm2 restart pdf-service
```

## 🔥 **Configuração de Firewall**

```bash
# Permitir porta 3001
sudo firewall-cmd --permanent --add-port=3001/tcp
sudo firewall-cmd --reload

# Verificar se foi adicionada
sudo firewall-cmd --list-ports
```

## 🌐 **Configuração de Proxy Reverso (Opcional)**

Se você usa Apache ou Nginx, pode configurar um proxy reverso:

### **Nginx**
```nginx
location /pdf-service/ {
    proxy_pass http://localhost:3001/;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection 'upgrade';
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_cache_bypass $http_upgrade;
}
```

### **Apache**
```apache
ProxyPass /pdf-service/ http://localhost:3001/
ProxyPassReverse /pdf-service/ http://localhost:3001/
```

## 🛠️ **Solução de Problemas**

### **Serviço não inicia**
```bash
# Ver logs detalhados
pm2 logs pdf-service --err

# Testar manualmente
cd /var/www/pdf-service
node server.js
```

### **Puppeteer não funciona**
```bash
# Testar Puppeteer isoladamente
node -e "
const puppeteer = require('puppeteer');
puppeteer.launch({headless: 'new', args: ['--no-sandbox']})
  .then(browser => { console.log('OK'); browser.close(); })
  .catch(err => console.error('Erro:', err.message));
"

# Se falhar, reinstalar
npm uninstall puppeteer
npm install puppeteer
```

### **Laravel não consegue conectar**
```bash
# Testar conectividade do Laravel
curl -I http://localhost:3001/health

# Verificar logs do Laravel
tail -f /caminho/para/laravel/storage/logs/laravel.log
```

## 📊 **Monitoramento de Performance**

```bash
# Uso de CPU e memória
pm2 monit

# Logs de sistema
journalctl -u pm2-$USER -f

# Espaço em disco
df -h
```

## 🔄 **Backup e Restore**

```bash
# Backup da configuração PM2
pm2 dump

# Restore
pm2 resurrect
```

## ✅ **Checklist Final**

- [ ] Node.js instalado (>= 18)
- [ ] Dependências do Puppeteer instaladas
- [ ] Microserviço configurado em `/var/www/pdf-service`
- [ ] PM2 rodando o serviço
- [ ] Porta 3001 liberada no firewall
- [ ] Laravel configurado com `PDF_SERVICE_URL`
- [ ] Teste básico funcionando: `curl http://localhost:3001/health`
- [ ] Certificado sendo gerado no Laravel

## 🎉 **Resultado Esperado**

Após seguir todos os passos:

1. ✅ Microserviço Node.js rodando em produção
2. ✅ Laravel usando automaticamente o microserviço
3. ✅ PDFs sendo gerados com qualidade máxima
4. ✅ Reinicialização automática em caso de problemas
5. ✅ Logs centralizados com PM2

**Seus certificados agora terão qualidade profissional idêntica ao navegador!** 🚀
