# Sistema de Gerenciamento de Usuários e Endereços

Este sistema foi construído utilizando **Laravel 11** como backend **MariaDB** como banco de dados e **Vue.js 3** para o frontend, estilizado com **Bootstrap**. 

O objetivo principal é gerenciar usuários, perfis e endereços através de uma interface intuitiva e bem estruturada. Ele permite o gerenciamento de usuários, perfis e endereços com funcionalidades básicas, incluindo operações CRUD (Create, Read, Update, Delete).

## Funcionalidades Principais

- **Gerenciamento de Usuários**: Criação, leitura, atualização e exclusão de usuários.
- **Associação de Endereços**: Vinculação de endereços a usuários, com suporte para múltiplos endereços por usuário.
- **Perfis de Usuário**: Cada usuário pode estar associado a um perfil específico.
- **Validação de Dados**: O sistema valida entradas como e-mail, CPF e campos obrigatórios.
- **API REST**: O backend fornece uma API REST para interações.


# BackEnd da aplicação:

## Modelos e Relacionamentos

### Models

- **User**:
  - Representa os usuários do sistema.
  - Relacionamentos:
    - Muitos para muitos com o modelo `Adress` (tabela intermediária: `relations`).
    - Um para um com o modelo `Profile`.

- **Adress**:
  - Representa os endereços dos usuários.
  - Relacionamentos:
    - Muitos para muitos com o modelo `User` (tabela intermediária: `relations`).

- **Profile**:
  - Representa os perfis dos usuários.
  - Relacionamentos:
    - Um para muitos com o modelo `User`.

---

## Tabela de Rotas

| Método | Rota                        | Funcionalidade                   | Exemplo de Envio                | Exemplo de Retorno              |
|--------|-----------------------------|----------------------------------|---------------------------------|---------------------------------|
| GET    | `/users`                    | Lista todos os usuários.         | `-`                             | `[{"id":1,"name":"..."}]`      |
| GET    | `/users/{user}`             | Detalha um usuário específico.   | `-`                             | `{"id":1,"name":"..."}`        |
| POST   | `/users`                    | Cria um novo usuário.            | `{"name":"...","email":"...","cpf":"...","profile_id":"...","logradouro":"...","cep":"...."}`  | `{"status":true,"message":"..."}` |
| PUT    | `/users/{user}`             | Atualiza um usuário existente.   | `{"name":"...","email":"...","cpf":"...","profile_id":"..."}`  | `{"status":true,"message":"..."}` |
| DELETE | `/users/{user}`             | Remove um usuário específico.    | `-`                             | `{"status":true,"message":"..."}` |
| POST   | `/adress`                   | Adiciona um novo endereço.       | `{"logradouro":"...","cep":"..."}` | `{"status":true,"message":"..."}` |
| DELETE | `/adress/{adress}`          | Remove um endereço específico.   | `-`                             | `{"status":true,"message":"..."}` |
| GET    | `/profile`                  | Lista todos os perfis.           | `-`                             | `[{"id":1,"profile_name":"..."}]` |


## Configuração e Execução
Pré-requisitos : 

- PHP >= 8.2

- Composer

- MariaDB

### Passos para Configuração
- Clone o repositório.
- Configure o arquivo .env com as credenciais do banco de dados.
- Instale as dependências do projeto:
```bash
$ composer install
```
- Execute as migrações para criar as tabelas:
```bash
$ php artisan migrate
```

- Execute o seeders para realizar um pré carregamento de dados fakers para execução de testes. É importante destacar que a tabel profiles(excencial para a o cadastro e edição de usuários) no momento só é preenchida desta forma, em caso da não utilização dos dados fakers, faz-se necessária a edição do arquivo "database\seeders\DatabaseSeeder.php".
Segue abaixo o comando para execução dos seeders:
```
$ php artisan db:seed
```

Inicie o servidor:

```bash
$ php artisan serve
```

### Estrutura do Banco de Dados
Tabelas Principais
- users: Armazena informações dos usuários.
- adresses: Armazena informações dos endereços.
- profiles: Armazena informações dos perfis.
- relations: Tabela intermediária para associar usuários e endereços.


# FrontEnd da aplicação:


## Estrutura do Frontend

### Principais Componentes

1. **`App.vue`**:
   - Componente raiz do sistema.
   - Define a estrutura principal com a navegação (`Navbar`) e rodapé (`Footer`).

2. **`Home.vue`**:
   - Tela inicial do sistema.
   - Inclui o componente `HomeForm` para exibição e gerenciamento de usuários.

3. **`Register.vue`**:
   - Tela de cadastro de usuários.
   - Inclui o componente `RegisterForm` para entrada de informações de novos usuários e endereços.

4. **`DetailUser.vue`**:
   - Modal que detalha informações de um usuário, incluindo dados pessoais, perfil e endereços associados.

5. **`UpdateUser.vue`**:
   - Modal que permite a edição de informações de um usuário.
   - Inclui funcionalidade para adicionar e remover endereços do usuário.

6. **`HomeForm.vue`**:
   - Exibe uma lista de usuários com filtros por nome, e-mail, CPF e data de criação.
   - Permite ações como detalhar, editar ou excluir usuários.

7. **`RegisterForm.vue`**:
   - Formulário para cadastro de usuários e endereços.

8. **`Navbar.vue` e `Footer.vue`**:
   - Elementos de navegação e rodapé do sistema.

---

## Configuração do Ambiente

### Pré-requisitos

- **Node.js** (v16+)
- **Vue CLI**
- **Backend em execução** (API Laravel 11 configurada em `http://127.0.0.1:8000/api`)

### Passos para Configuração

1. Clone o repositório do frontend:
```bash
  $ git clone <URL_DO_REPOSITORIO>
```

```bash
  $ cd nome-do-projeto
```

```bash
  $ npm install
```

```bash
  $ npm run serve
```

### Consumo da API REST

A API REST é consumida utilizando o pacote axios. O arquivo de configuração principal é api.js, localizado na pasta services. Ele define a baseURL como:



```javascript
baseURL: 'http://127.0.0.1:8000/api'
```

### Exemplo de consumo com axios:

```javascript
import axios from '../services/api.js';

async function getUsers() {
  try {
    const response = await axios.get('/users');
    console.log(response.data);
  } catch (error) {
    console.error('Erro ao buscar usuários:', error);
  }
}
```

### Estrutura de DDiretórios

```plaintext
src/
├── components/
│   ├── DetailUser.vue
│   ├── Footer.vue
│   ├── HomeForm.vue
│   ├── Navbar.vue
│   ├── RegisterForm.vue
│   ├── UpdateUser.vue
├── services/
│   └── api.js
├── views/
│   ├── Home.vue
│   ├── Register.vue
└── App.vue

```

### Tecnologias Utilizadas
- Vue.js 3: Framework JavaScript progressivo para construção de interfaces.
- Bootstrap: Framework CSS para estilização responsiva.
- Axios: Biblioteca para requisições HTTP.
- Backend: API REST em Laravel 11.


## Observações
O sistema está configurado para validação básica de entradas, como CPF e email.
Para mais informações sobre as funcionalidades, consulte os arquivos do controlador e as rotas associadas.


## ✒️ Autores

* **SESAB** - *Idealizador do sistema e solicitador do desafio* 
* **Vitor Costa Menezes** - *Codificação, layout* - [Perfil GitHub](https://github.com/VitorCostaMenezes), [Perfil Linkedin](https://www.linkedin.com/in/vitor-costa-10073089/)


## Telas

### Tela inicial (Home)
![<alt-text>](<https://github.com/VitorCostaMenezes/desafio-tecnico-sesab/blob/main/capturas/1_home.JPG>) 

# 
### Tela inicial (Uso de filtro)
![<alt-text>](<https://github.com/VitorCostaMenezes/desafio-tecnico-sesab/blob/main/capturas/2_home_filtro.JPG>)

#
### Tela Inicial (Modal Detalhar)

![<alt-text>](<https://github.com/VitorCostaMenezes/desafio-tecnico-sesab/blob/main/capturas/3_home_detalhar.JPG>)

#
### Tela Inicial (Modal Editar parte-1)

![<alt-text>](<https://github.com/VitorCostaMenezes/desafio-tecnico-sesab/blob/main/capturas/4_home_editar_pt1.JPG>)


### Tela Inicial (Modal Editar parte-2)

![<alt-text>](<https://github.com/VitorCostaMenezes/desafio-tecnico-sesab/blob/main/capturas/5_home_editar_pt2.JPG>)

#
### Tela Inicial (Botão Excluir)

![<alt-text>](<https://github.com/VitorCostaMenezes/desafio-tecnico-sesab/blob/main/capturas/6_home_bt_excluir.JPG>)

#
### Tela cadastro 

![<alt-text>](<https://github.com/VitorCostaMenezes/desafio-tecnico-sesab/blob/main/capturas/7_cadastro.JPG>)
#
### Tela cadastro (Confirmação)

![<alt-text>](<https://github.com/VitorCostaMenezes/desafio-tecnico-sesab/blob/main/capturas/8_cadastro.JPG>)












