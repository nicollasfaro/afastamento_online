# 📌 Módulo Joomla - Afastamento Online para Instituição Militar 🇧🇷

Este módulo foi desenvolvido para gerenciar **afastamentos online** de militares do **Exército Brasileiro** dentro do Joomla. Ele permite que militares solicitem afastamentos, acompanhem suas férias e gerem relatórios administrativos.

---

## 🚀 **Funcionalidades**
✅ **Solicitação de afastamentos** (férias, dispensa médica, baixa hospitalar, etc.)  
✅ **Consulta de saldo de férias** com controle por **ano de referência**  
✅ **Registro e controle de apresentação** pós-afastamento  
✅ **Upload obrigatório de documentos para afastamentos específicos**  
✅ **Gerenciamento de solicitações com rastreamento de status**  

---

## 🛠 **Instalação**
### **1⃣ Clonar o Repositório**
```bash
git clone https://github.com/nicollasfaro/mod_afastamento_online.git
```

### **2⃣ Mover o módulo para o Joomla**
Copie a pasta do módulo para:
```bash
/modules/mod_afastamento_online/
```

### **3⃣ Instalar via Joomla**
1. Acesse o **Painel do Joomla** → **Extensões** → **Gerenciador de Extensões**  
2. Clique em **Instalar a partir do Diretório**  
3. Selecione a pasta `/modules/mod_afastamento_online/` e clique em **Instalar**  

---

## ⚙️ **Configuração**
1. No **Painel do Joomla**, vá até **Módulos**  
2. Encontre **Afastamento Online** e clique em **Configurar**  
3. Ajuste os parâmetros conforme necessário  
4. **Habilite o módulo** e **atribua a uma posição do template**  

---

## 📝 **Estrutura do Módulo**
```
mod_afastamento_online/
│️— mod_afastamento_online.php   # Arquivo principal do módulo
│️— helper.php                   # Funções auxiliares para buscar e processar dados
│️— tmpl/
│   └— default.php              # Template do frontend
│️— assets/
│   ├— script.js                # JavaScript para interações no formulário
│   └— style.css                # Estilos para o módulo
│️— mod_afastamento_online.xml   # Manifesto de instalação do Joomla
│️— README.md                    # Documentação do repositório
```

---

## 📌 **Dependências**
- Joomla 3.8+ ou 4.x  
- PHP 5.6+  
- Banco de Dados MySQL  

---

## 🛡 **Segurança**
🔒 **Validações no backend**:  
✔️ O sistema impede solicitações sem os dados obrigatórios  
✔️ O upload de documentos é controlado para afastamentos específicos  
✔️ Os dados sensíveis são tratados com segurança  

---

## 🛠 **Como Contribuir?**
1. **Faça um fork** do repositório  
2. **Crie uma branch** para sua feature/bugfix  
3. **Commit suas alterações** com mensagens claras  
4. **Envie um Pull Request**  

Exemplo:
```bash
git checkout -b feature-nova-funcionalidade
git commit -m "Adicionada funcionalidade de controle de saldo"
git push origin feature-nova-funcionalidade
```

---

## 🐝 **Licença**
📝 Este projeto é distribuído sob a licença **MIT**.  

---

## 📞 **Contato**
📧 Se precisar de suporte ou tiver sugestões, entre em contato:  
📧 **Email**: nicollasciuldin@gmail.com  
🐙 **GitHub**: [nicollasfaro](https://github.com/nicollasfaro)  

---

🚀 **Feito com dedicação para melhorar a gestão de afastamentos no Exército Brasileiro!** 🇧🇷  
