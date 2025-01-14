<template>
  <div>
    <!-- Modal para editar usuário -->
    <div
      class="modal fade"
      id="UpdateUserModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="UpdateUserModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="UpdateUserModalLabel">
              Editar Usuário
            </h5>
            <!-- Botão para fechar o modal -->
            <button
              type="button"
              @click="$emit('close-modal')"
              class="close"
              data-dismiss="modal"
              aria-label="Fechar"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div v-if="user">
              <!-- Formulário de Editar Usuário -->
              <form @submit.prevent="updateUser">
                <div class="form-group">
                  <label for="name">Nome:</label>
                  <input
                    type="text"
                    v-model="user.name"
                    class="form-control"
                    id="name"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="email">Email:</label>
                  <input
                    type="email"
                    v-model="user.email"
                    class="form-control"
                    id="email"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="cpf">CPF:</label>
                  <input
                    type="text"
                    v-model="user.cpf"
                    @input="mascararCPF"
                    class="form-control"
                    id="cpf"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="profile">Perfil:</label>
                  <select
                    v-model="user.profile.id"
                    class="form-control"
                    id="profile"
                    required
                  >
                    <option value="" disabled>Selecione um perfil</option>
                    <option
                      v-for="profile in profiles"
                      :key="profile.id"
                      :value="profile.id"
                    >
                      {{ profile.profile_name }}
                    </option>
                  </select>
                </div>
                <div class="row justify-content-end">
                  <div class="col-4 my-3">
                    <button type="submit" class="btn btn-success btn-block">
                      Salvar Alterações
                    </button>
                  </div>
                </div>
              </form>
              <hr class="my-4" />

              <!-- Formulário de Adicionar Novo Endereço -->
              <h5>Adicionar Novo Endereço</h5>

              <form @submit.prevent="addAddress">
                <div class="form-group">
                  <label for="logradouro">Logradouro:</label>
                  <input
                    type="text"
                    v-model="newAddress.logradouro"
                    class="form-control"
                    id="logradouro"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="cep">CEP:</label>
                  <input
                    type="text"
                    v-model="newAddress.cep"
                    @input="mascararCEP"
                    class="form-control"
                    id="cep"
                    required
                  />
                </div>
                <div class="row justify-content-end">
                  <div class="col-4 my-3">
                    <button type="submit" class="btn btn-primary btn-block">
                      Adicionar Endereço
                    </button>
                  </div>
                </div>
              </form>
              <hr class="my-4" />

              <!-- Listagem de Endereços -->
              <h5 class="mb-3">Endereços Existentes</h5>
              <table class="table">
                <thead class="thead-dark">
                  <tr>
                    <th>ID</th>
                    <th>Logradouro</th>
                    <th>CEP</th>
                    <th>Ação</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Iteração sobre os endereços do usuário -->
                  <tr v-for="address in user.adress" :key="address.id">
                    <td>{{ address.id }}</td>
                    <td>{{ address.logradouro }}</td>
                    <td>{{ address.cep }}</td>
                    <td>
                      <input
                        type="button"
                        class="btn btn-danger btn-block px-5"
                        value="Excluir"
                        @click="deleteAddress(address.id)"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <p v-else>Carregando informações...</p>
          </div>

          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
              @click="$emit('close-modal')"
            >
              Fechar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  
  <script>
import axios from "../services/api.js";

export default {
  name: "UpdateUser",
  props: {
    userId: {
      type: Number, // O ID do usuário é obrigatório e deve ser um número
      required: true,
    },
  },
  data() {
    return {
      user: null, // Objeto para armazenar os dados do usuário
      profiles: [], // Lista de perfis obtidos da API
      newAddress: {
        logradouro: "",
        cep: "",
      },
    };
  },
  watch: {
    userId: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          this.getUser(newVal); // Carrega os dados do usuário ao receber o ID
        }
      },
    },
  },
  methods: {
    // Busca os dados do usuário pelo ID
    async getUser(id) {
      try {
        const response = await axios.get(`/users/${id}`);
        this.user = response.data;
      } catch (error) {
        console.error("Erro ao buscar usuário:", error);
      }
    },
    // Busca os perfis disponíveis da API
    async getProfiles() {
      try {
        const response = await axios.get("/profile");
        this.profiles = response.data;
      } catch (error) {
        console.error("Erro ao buscar perfis:", error);
      }
    },
     // Atualiza os dados do usuário no backend
    async updateUser() {
      try {
        // Atualiza o campo profile_id com o ID selecionado no formulário
        const payload = {
          ...this.user,
          profile_id: this.user.profile.id, // Adiciona o campo profile_id
        };

        console.log(payload);

        await axios.put(`/users/${this.user.id}`, payload);

        alert("Usuário atualizado com sucesso!");
        this.$emit("close-modal");
      } catch (error) {
        console.error("Erro ao atualizar usuário:", error);
        alert("Erro ao atualizar usuário.");
      }
    },
    // Adiciona um novo endereço ao usuário
    async addAddress() {
      if (!this.newAddress.logradouro || !this.newAddress.cep) {
        alert("Preencha todos os campos do endereço.");
        return;
      }
      try {
        await axios.post("/adress", {
          id: this.user.id,
          logradouro: this.newAddress.logradouro,
          cep: this.newAddress.cep,
        });
        alert("Endereço adicionado com sucesso!");
        this.newAddress = { logradouro: "", cep: "" }; // Limpa os campos
        this.getUser(this.user.id); // Atualiza os endereços
      } catch (error) {
        console.error("Erro ao adicionar endereço:", error);
        alert("Erro ao adicionar endereço.");
      }
    },
    // Exclui um endereço do usuário
    async deleteAddress(addressId) {
      const confirmar = window.confirm(
        "Tem certeza de que deseja excluir este endereço?"
      );
      if (!confirmar) {
        return; // Cancela a exclusão se o usuário não confirmar
      }
      try {
        await axios.delete(`/adress/${addressId}`, {
          data: { user_id: this.user.id }, // Envia o ID do usuário no corpo da requisição
        });
        alert("Endereço excluído com sucesso!");
        this.getUser(this.user.id); // Atualiza os endereços
      } catch (error) {
        console.error("Erro ao excluir endereço:", error);
        alert("Erro ao excluir endereço.");
      }
    },
    // Aplica máscara ao CPF enquanto o usuário digita
    mascararCPF() {
      const apenasNumeros = this.user.cpf.replace(/\D/g, ""); // Remove caracteres não numéricos
      this.user.cpf = apenasNumeros
        .replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, "$1.$2.$3-$4")
        .substring(0, 14); // Aplica a máscara e limita o tamanho
    },
    // Aplica máscara ao CEP enquanto o usuário digita
    mascararCEP() {
      const apenasNumeros = this.newAddress.cep.replace(/\D/g, ""); // Remove caracteres não numéricos
      this.newAddress.cep = apenasNumeros
        .replace(/^(\d{5})(\d{3}).*/, "$1-$2")
        .substring(0, 9); // Aplica a máscara e limita o tamanho
    },
  },

  async mounted() {
    await this.getProfiles(); // Carrega os perfis ao montar o componente
  },
};
</script>
  