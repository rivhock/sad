# SAD — Sistema de Avaliação de Desempenho

Sistema web de código aberto para gestão do ciclo completo de **avaliação de desempenho** e **progressão por qualificação** de servidores públicos municipais.

Desenvolvido originalmente para a **Prefeitura Municipal de Campo Bom / RS**, atendendo aos requisitos da **COMPAQ** (Comissão Permanente de Avaliação e Qualificação).

---

## Sobre o projeto

O SAD automatiza todo o processo de avaliação funcional de servidores públicos, substituindo formulários em papel por um fluxo digital com assinatura eletrônica, trilha de auditoria completa e geração automática de documentos oficiais.

**Principais desafios resolvidos:**

- Chefias sem acesso ao sistema — resolvido com links externos com token e modo lote
- Servidores sem e-mail cadastrado — acesso via CPF sem senha
- Integridade histórica — snapshot dos itens no momento da avaliação
- Validade jurídica — assinatura com hash SHA-256 + IP + data/hora
- Auditoria — log completo de cada ação do sistema

---

## Funcionalidades

### Autenticação e perfis

- Login unificado com detecção automática de perfil
- 4 papéis: Administrador, COMPAQ, Avaliador e Servidor
- Rate limit contra força bruta (5 tentativas/minuto)
- Cookies HttpOnly, Secure e SameSite
- Página "Meu Perfil" com alteração de senha
- Papel múltiplo (usuário pode ter mais de um papel)

### Avaliação de desempenho

- 4 formulários específicos: Saúde, Magistério, Guarda Municipal e Quadro Geral
- Fluxo completo: criar, preencher, assinar, notificar, defender, homologar
- Snapshot histórico: editar um formulário não altera avaliações antigas
- Notas de 0 a 10 com cálculo automático (soma, média ou média ponderada)
- Cálculo em tempo real enquanto o avaliador preenche

### Avaliador externo (sem login)

- Link com token de 64 caracteres, válido por 30 dias
- Identificação por nome + CPF
- Modo lote: 1 link, várias avaliações em uma única sessão
- Ativação e desativação individual

### Geração em massa

- Criação de centenas de avaliações em minutos
- Filtros por secretaria, categoria, período de admissão e fim de estágio
- Modo simulação: mostra quantas seriam criadas antes de confirmar

### Defesa e parecer

- Servidor protocola defesa escrita com anexo em PDF
- COMPAQ emite parecer estruturado com 4 decisões:
  - Deferida — aceita a defesa, ajusta a nota
  - Parcialmente deferida — aceita em parte
  - Indeferida — mantém a nota original
  - Encaminhamento para PAD — processo administrativo

### Progressão por qualificação

- Servidor anexa certificados de Curso ou Palestra (carga mínima 4h)
- COMPAQ aprova ou rejeita com justificativa
- Total de horas aprovadas calculado automaticamente
- Agrupamento por ano para relatórios

### Documentos e relatórios

- PDF oficial com brasão, notas, defesa, parecer e as 3 assinaturas
- Dashboards adaptados por perfil
- Log de auditoria com filtros por usuário, ação, entidade e data

### Infraestrutura

- Backup automático em 3 camadas (local, Samba e mensal)
- Fila de e-mail com worker systemd (4 tipos de notificação)
- Importação em massa via CSV com validação
- HTTPS obrigatório em produção

---

## Stack técnica

| Camada | Tecnologia |
|---|---|
| Linguagem | PHP 8.3 |
| Framework | Laravel 12 |
| Painéis admin | Filament 3 |
| Frontend reativo | Livewire 3 + Alpine.js |
| Banco de dados | PostgreSQL 14 |
| Servidor web | Nginx + PHP-FPM |
| Fila | Database queue + systemd worker |
| PDF | DomPDF |
| Permissões | spatie/laravel-permission |
| Auditoria | spatie/laravel-activitylog |
| Backup | spatie/laravel-backup |

---

## Requisitos

- PHP 8.3 ou superior
- Composer 2
- PostgreSQL 14 ou superior
- Node.js 20+ (opcional, para build de assets)
- Nginx ou Apache (produção)

---

## Instalação rápida

Passo 1. Clone o repositório:

    git clone https://github.com/SEU-USUARIO/sad.git
    cd sad

Passo 2. Instale as dependências:

    composer install

Passo 3. Configure o ambiente:

    cp .env.example .env
    php artisan key:generate

Passo 4. Configure o banco de dados:

    createdb sad_campo_bom

Edite as variáveis DB_* no arquivo .env.

Passo 5. Rode as migrations e o seeder:

    php artisan migrate --seed

Passo 6. Crie o link de storage:

    php artisan storage:link

Passo 7. Suba o servidor de desenvolvimento:

    php artisan serve

Acesso inicial:

- URL: http://localhost:8000
- E-mail: admin@campobom.rs.gov.br
- Senha: definida em ADMIN_PASSWORD no .env (padrão: mudar123)

Troque a senha imediatamente no primeiro acesso pelo menu "Meu Perfil".

---

## Estrutura do projeto

- app/Enums — Enums (StatusAvaliacao, CategoriaServidor)
- app/Filament/Pages — Páginas do admin (MeuPerfil, Importar)
- app/Filament/Resources — CRUDs administrativos
- app/Filament/Compaq — Painel COMPAQ
- app/Filament/Avaliador — Painel do avaliador
- app/Http/Controllers — Controllers públicos e autenticação
- app/Models — Models Eloquent
- app/Observers — Observer de notificações
- app/Services — Regras de negócio
- resources/views/avaliacao-publica — Link do servidor
- resources/views/avaliador-externo — Chefia sem login
- resources/views/auth — Login unificado
- resources/views/emails — Templates de e-mail
- resources/views/pdf — PDF da avaliação e slides
- database/migrations — 30+ migrations versionadas
- database/seeders

---

## Modelo de dados

Entidades principais:

- users — usuários do sistema (nome, email, cpf, matrícula)
- servidores — dados funcionais vinculados ao usuário
- avaliacoes — cada avaliação de um servidor em um ciclo
- respostas_itens — notas por item (com snapshot histórico)
- assinaturas — registros de assinatura digital (hash + IP)
- defesas — defesa escrita + parecer da COMPAQ
- qualificacoes — certificados de cursos e palestras
- formularios, itens_formulario — estrutura dos formulários
- ciclos_avaliacao — períodos bienais de avaliação
- lotes_avaliador — agrupamento de avaliações para link externo
- activity_log — trilha de auditoria completa

---

## Segurança

- HTTPS obrigatório em produção
- Rate limit no login: 5 tentativas por minuto
- Cookies seguros: HttpOnly, Secure, SameSite
- Senhas hasheadas com bcrypt
- Assinatura digital com hash SHA-256 + IP + timestamp
- Log de auditoria para toda operação de escrita
- Snapshot histórico: alterar formulários não afeta avaliações antigas
- LGPD: dados funcionais separados de dados pessoais

---

## Licença

Distribuído sob a licença MIT. Uso livre para qualquer prefeitura ou instituição pública, comercial ou não.

Consulte o arquivo LICENSE para mais detalhes.

---

## Contribuindo

Contribuições são bem-vindas. Leia o CONTRIBUTING.md antes de abrir uma issue ou Pull Request.

---

## Suporte

Para dúvidas, sugestões ou problemas, abra uma issue no repositório.
