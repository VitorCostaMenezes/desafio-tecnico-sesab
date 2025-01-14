<template>
  <div class="mb-2">
    <h1 class="mt-5">Usuários</h1>

    <!-- Formulário de Filtros -->
    <form @submit.prevent="buscarUsuarios">
      <div class="row" id="filter">
        <!-- Campo para filtrar por nome -->
        <div class="col-md-2 col-sm-12">
          <label class="form-label" for="name">Nome:</label>
          <input
            class="form-control"
            id="name"
            type="text"
            v-model="filtros.name"
            placeholder="Filtrar por nome"
          />
        </div>

        <!-- Campo para filtrar por e-mail -->
        <div class="col-md-2 col-sm-12">
          <label class="form-label" for="email">Email:</label>
          <input
            class="form-control"
            id="email"
            type="email"
            v-model="filtros.email"
            placeholder="Filtrar por email"
          />
        </div>

        <!-- Campo para filtrar por CPF com máscara -->
        <div class="col-md-2 col-sm-12">
          <label class="form-label" for="cpf">CPF:</label>
          <input
            class="form-control"
            id="cpf"
            type="text"
            v-model="filtros.cpf"
            @input="mascararCPF"
            placeholder="Filtrar por CPF"
          />
        </div>

        <!-- Campo para filtrar por data de início -->
        <div class="col-md-2 col-sm-12">
          <label class="form-label" for="data_inicio">Data Início:</label>
          <input
            class="form-control"
            id="data_inicio"
            type="date"
            v-model="filtros.data_inicio"
          />
        </div>

        <!-- Campo para filtrar por data de fim -->
        <div class="col-md-2 col-sm-12">
          <label class="form-label" for="data_fim">Data Fim:</label>
          <input
            class="form-control"
            id="data_fim"
            type="date"
            v-model="filtros.data_fim"
          />
        </div>

        <!-- Botão para aplicar os filtros -->
        <div class="col-md-1 col-sm-12 pt-2">
          <input
            class="form-control btn btn-success mt-4"
            type="submit"
            value="Filtrar"
          />
        </div>

        <!-- Botão para limpar os filtros -->
        <div class="col-md-1 col-sm-12 pt-2">
          <input
            class="form-control btn btn-warning mt-4"
            type="button"
            @click="limparFiltros"
            value="Limpar"
          />
        </div>
      </div>
    </form>

    <div id="table-users">
      <!-- Status de carregamento -->
      <p v-if="carregando">Carregando usuários...</p>
      <!-- Mensagem de erro -->
      <p v-if="erro" style="color: red">{{ erro }}</p>

      <!-- Tabela de usuários -->
      <div v-if="usuarios.length">
        <table class="table table-hover">
          <thead class="thead-dark">
            <tr>
              <th>ID</th>
              <th>Data Cadastro</th>
              <th>Nome</th>
              <th>CPF</th>
              <th>E-mail</th>
              <th>Perfil</th>
              <th>Ação</th>
            </tr>
          </thead>
          <tbody>
            <!-- Iteração sobre a lista de usuários -->
            <tr
              class="burger-table-row"
              v-for="usuario in usuarios"
              :key="usuario.id"
            >
              <td>{{ usuario.id }}</td>
              <td>{{ usuario.created_at }}</td> <!-- Data formatada -->
              <td>{{ usuario.name }}</td>
              <td>{{ usuario.cpf }}</td>
              <td>{{ usuario.email }}</td>
              <td>{{ usuario.profile.profile_name }}</td>
              <td>
                <!-- Botão para detalhar o usuário -->
                <input
                  type="button"
                  value="Detalhar"
                  id="detalhar"
                  class="btn btn-info btn-sm mx-1"
                  data-toggle="modal"
                  data-target="#ExemploModalCentralizado"
                  @click="setUserId(usuario.id)"
                />
                <!-- Botão para editar o usuário -->
                <input
                  type="button"
                  value="Editar"
                  class="btn btn-warning btn-sm mx-1"
                  data-toggle="modal"
                  data-target="#UpdateUserModal"
                  @click="setEditUserId(usuario.id)"
                />
                <!-- Botão para excluir o usuário -->
                <input
                  type="button"
                  value="Excluir"
                  class="btn btn-danger btn-sm mx-1"
                  @click="deleteUser(usuario.id)"
                />
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Mensagem para nenhum resultado -->
      <p v-else-if="!carregando && !usuarios.length">
        Nenhum usuário encontrado.
      </p>
    </div>

    <!-- Componente de detalhamento do usuário -->
    <DetailUser :userId="selectedUserId" />
    <!-- Componente de edição do usuário -->
    <UpdateUser
      :userId="selectedEditUserId"
      @close-modal="buscarUsuarios"
    />
  </div>
</template>

<script>
import axios from "../services/api.js";
import DetailUser from "./DetailUser.vue";
import UpdateUser from "./UpdateUser.vue";

export default {
  name: 'HomeForm',
  props: {
    userid: Number,
  },
  data() {
    return {
      filtros: {
        name: "",
        email: "",
        cpf: "",
        data_inicio: "",
        data_fim: "",
      },
      usuarios: [], // Lista de usuários obtidos da API
      carregando: false, // Status de carregamento
      erro: "", // Mensagem de erro
      selectedUserId: null, // ID do usuário selecionado para detalhamento
      selectedEditUserId: null, // ID do usuário selecionado para edição
    };
  },
  methods: {
    // Busca usuários com base nos filtros aplicados
    async buscarUsuarios() {
      this.carregando = true;
      this.erro = "";

      try {
        const response = await axios.get("/users", {
          params: this.filtros,
        });
        this.usuarios = response.data.map(usuario => ({
          ...usuario,
          created_at: this.formatarData(usuario.created_at),
        }));
      } catch (error) {
        console.error("Erro ao buscar usuários:", error);
        this.erro = "Erro ao buscar usuários. Tente novamente.";
      } finally {
        this.carregando = false;
      }
    },

    // Limpa os filtros aplicados
    async limparFiltros() {
      this.filtros = {
        name: "",
        email: "",
        cpf: "",
        data_inicio: "",
        data_fim: "",
      };
    },

    // Exclui um usuário
    async deleteUser(id) {
      const confirmar = window.confirm(
        "Tem certeza de que deseja excluir este usuário?"
      );
      if (!confirmar) {
        return;
      }

      this.carregando = true;
      this.erro = "";

      try {
        await axios.delete(`/users/${id}`);
        alert("Usuário excluído com sucesso!");
        await this.buscarUsuarios();
      } catch (error) {
        console.error("Erro ao excluir usuário:", error);
        this.erro = "Erro ao excluir usuário. Tente novamente.";
      } finally {
        this.carregando = false;
      }
    },

    // Define o ID do usuário para detalhamento
    setUserId(id) {
      this.selectedUserId = id;
    },

    // Define o ID do usuário para edição
    setEditUserId(id) {
      this.selectedEditUserId = id;
    },

    // Formata uma data para o padrão "DD/MM/AAAA"
    formatarData(data) {
      if (!data) return "";
      const date = new Date(data);
      const dia = String(date.getDate()).padStart(2, "0");
      const mes = String(date.getMonth() + 1).padStart(2, "0");
      const ano = date.getFullYear();
      return `${dia}/${mes}/${ano}`;
    },

    // Aplica máscara ao CPF no campo de filtro
    mascararCPF() {
      const apenasNumeros = this.filtros.cpf.replace(/\D/g, ""); // Remove caracteres não numéricos
      this.filtros.cpf = apenasNumeros
        .replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, "$1.$2.$3-$4")
        .substring(0, 14); // Limita a 14 caracteres
    },
  },

  // Carrega os usuários ao montar o componente
  async mounted() {
    await this.buscarUsuarios();
  },

  components: {
    DetailUser,
    UpdateUser,
  },
};
</script>

  
<style scoped>
#table-users {
  height: 46.5vh;
  margin: 0;
  padding: 0;
  bottom: 0;
  width: 100%;
  overflow-y: auto;
}

#filter {
  height: 15vh;
  align-items: center;
  margin: 0;
  padding: 0;
  bottom: 0;
  width: 100%;
}
</style>