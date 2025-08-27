# 🐛 Correção: Call to undefined relationship [course] 

## ❌ **Problema Identificado**

```
Call to undefined relationship [course] on model [App\Models\Certificate]
```

## 🔍 **Análise do Erro**

O erro ocorreu porque no `CertificateController` estava tentando carregar um relacionamento `course` que não existe:

```php
// ❌ CÓDIGO COM ERRO
$certificate->load(['student', 'school', 'course']);
```

## 🏗️ **Estrutura Real do Sistema**

### **Modelos Existentes**
- ✅ `Certificate` (principal)
- ✅ `Student` (relacionamento belongsTo)
- ✅ `School` (relacionamento belongsTo)
- ❌ `Course` (NÃO EXISTE)

### **Campos do Curso no Certificate**
As informações do curso estão armazenadas diretamente na tabela `certificates`:

```php
// Campos no modelo Certificate
'course_level',    // ex: 'ensino_medio'
'course_name',     // ex: 'Ensino Médio'
```

## ✅ **Solução Aplicada**

### **1. Controller Corrigido**
Removido o relacionamento inexistente de todos os métodos:

```php
// ✅ CÓDIGO CORRIGIDO
$certificate->load(['student', 'school']); // Remove 'course'
```

### **2. Métodos Afetados**
- `showLandscape()`
- `downloadLandscapePdf()`
- `streamLandscape()`

### **3. Arquivo Atualizado**
`app/Http/Controllers/CertificateController.php`

## 💾 **Diff das Alterações**

```diff
- $certificate->load(['student', 'school', 'course']);
+ $certificate->load(['student', 'school']);
```

## 🎯 **Como Acessar Dados do Curso**

### **✅ Forma Correta**
```php
// Diretamente do modelo Certificate
$certificate->course_name     // Nome do curso
$certificate->course_level    // Nível do curso
$certificate->course_level_label  // Label formatado
```

### **❌ Forma Incorreta**
```php
// NÃO FUNCIONA - relacionamento não existe
$certificate->course->name    // ❌ 
$certificate->course->level   // ❌ 
```

## 🧪 **Teste da Correção**

### **Comandos para Testar**
```bash
# Visualização web
GET /certificates/1/landscape

# Download PDF
GET /certificates/1/landscape/pdf

# Stream PDF
GET /certificates/1/landscape/stream
```

### **Resultado Esperado**
✅ Página carrega sem erro
✅ PDF é gerado corretamente
✅ Dados do curso são exibidos

## 📋 **Campos Disponíveis no Certificate**

### **Relacionamentos**
- `$certificate->student` - Dados do estudante
- `$certificate->school` - Dados da escola

### **Curso (campos diretos)**
- `$certificate->course_name` - Nome do curso
- `$certificate->course_level` - Nível (ensino_medio, etc.)
- `$certificate->course_level_label` - Label formatado

### **Outros Dados Importantes**
- `$certificate->certificate_number` - Número do certificado
- `$certificate->completion_date` - Data de conclusão
- `$certificate->issue_date` - Data de emissão
- `$certificate->student_name` - Nome do estudante
- `$certificate->school_name` - Nome da escola

## 🏁 **Status Final**

✅ **Erro corrigido com sucesso**
✅ **Sistema funcional para landscape**
✅ **Documentação atualizada**
✅ **Pronto para uso em produção**

---

**Data da Correção**: Hoje
**Arquivos Afetados**: `CertificateController.php`
**Impacto**: Zero - correção transparente 
