# Certificado em Formato Landscape

## Arquivo Criado
`resources/views/certificates/new-pdf-landscape.blade.php`

## O que foi alterado para Landscape

### 1. Configurações CSS Principais

- **@page**: Configurado para `A4 landscape` 
- **Container principal**: Aplicada rotação de 90° com `transform: rotate(90deg)`
- **Escala adaptativa**: Diferentes escalas para tela e impressão

### 2. Dimensões Mantidas

- **Largura original**: 842px (mantida)
- **Altura original**: 596px (mantida)
- **Classes CSS**: Todas as classes de posicionamento preservadas

### 3. Estrutura de Dados

- **Relacionamentos**: Certificate -> Student, School
- **Dados do curso**: Armazenados diretamente no Certificate (course_name, course_level)
- **Sem modelo Course**: As informações estão nos campos do próprio certificado

### 4. Funcionalidades Adicionadas

- **Zoom com teclado**: Ctrl + (+/-/0) para zoom in/out/reset
- **Escala automática**: Ajuste baseado na proporção da tela
- **Otimização para impressão**: Escala específica para @media print

### 5. Como usar

#### No Controller:
```php
public function certificateLandscape($id)
{
    $certificate = Certificate::findOrFail($id);
    return view('certificates.new-pdf-landscape', compact('certificate'));
}
```

#### Na rota:
```php
Route::get('/certificate/{id}/landscape', [CertificateController::class, 'certificateLandscape']);
```

#### Para impressão:
- O formato será automaticamente A4 landscape
- Margens zeradas para aproveitamento total da página
- Cores preservadas com `-webkit-print-color-adjust: exact`

### 6. Compatibilidade

- **Navegadores**: Chrome, Firefox, Safari, Edge
- **Impressão**: Funciona em impressoras PDF e físicas
- **Responsivo**: Escala automaticamente conforme tamanho da tela

### 7. Personalização

Para ajustar a escala padrão, modifique as linhas:

```javascript
// Para visualização em tela
var initialScale = screenRatio > 1.4 ? 0.8 : 0.6;

// Para impressão
transform: rotate(90deg) scale(0.75);
```

### 8. Vantagens do Landscape

- **Melhor aproveitamento**: Certificados ficam maiores no papel A4
- **Leitura mais fácil**: Textos em orientação horizontal natural
- **Impressão otimizada**: Sem cortes ou redimensionamentos desnecessários
- **Profissional**: Visual mais impactante e moderno

## Arquivos Relacionados

- `new-pdf.blade.php` - Versão original (portrait)
- `new-pdf-landscape.blade.php` - Nova versão (landscape)
- Este arquivo de instruções 
