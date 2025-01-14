<template>
  <div>
    <!-- Título da página -->
    <h1 class="">Cadastro</h1>
    
    <!-- Formulário de cadastro de usuário -->
    <form @submit.prevent="registerUser">
      <div class="row">
        <div class="form-group col-md-12 col-sm-12">
          <h4>Usuário:</h4> <!-- Seção de informações do usuário -->
        </div>

        <!-- Campo para nome do usuário -->
        <div class="form-group col-md-6 col-sm-12">
          <label for="name">Nome</label>
          <input
            type="text"
            v-model="formData.name"
            class="form-control"
            id="name"
            minlength="5"
            maxlength="250"
            required
            placeholder="Digite o nome do usuário..."
          />
        </div>

        <!-- Campo para e-mail do usuário -->
        <div class="form-group col-md-6 col-sm-12">
          <label for="email">Email</label>
          <input
            type="email"
            v-model="formData.email"
            class="form-control"
            id="email"
            minlength="6"
            maxlength="250"
            required
            placeholder="Digite o email..."
          />
        </div>

        <!-- Campo para CPF do usuário -->
        <div class="form-group col-md-6 col-sm-12">
          <label for="cpf">CPF</label>
          <input
            type="text"
            v-model="formData.cpf"
            class="form-control"
            id="cpf"
            @input="applyCpfMask"
            minlength="14"
            maxlength="14"
            required
            placeholder="Digite o CPF..."
          />
        </div>

        <!-- Campo para seleção de perfil do usuário -->
        <div class="form-group col-md-6 col-sm-12">
          <label for="profile_id">Perfil</label>
          <select
            v-model="formData.profile_id"
            class="form-control"
            id="profile_id"
            required
          >
            <option value="" disabled>Selecione um perfil...</option>
            <!-- Itera sobre os perfis carregados da API -->
            <option
              v-for="profile in profiles"
              :key="profile.id"
              :value="profile.id"
            >
              {{ profile.profile_name }}
            </option>
          </select>
        </div>
      </div>

      <div class="row mt-3">
        <div class="form-group col-md-12 col-sm-12">
          <h4>Endereço:</h4> <!-- Seção de informações de endereço -->
        </div>

        <!-- Campo para logradouro do endereço -->
        <div class="form-group col-md-6 col-sm-12">
          <label for="logradouro">Logradouro</label>
          <input
            type="text"
            v-model="formData.logradouro"
            class="form-control"
            id="logradouro"
            minlength="5"
            maxlength="100"
            required
            placeholder="Digite o logradouro..."
          />
        </div>

        <!-- Campo para CEP do endereço -->
        <div class="form-group col-md-6 col-sm-12">
          <label for="cep">CEP</label>
          <input
            type="text"
            v-model="formData.cep"
            class="form-control"
            id="cep"
            @input="applyCepMask"
            minlength="9"
            maxlength="9"
            required
            placeholder="Digite o CEP..."
          />
        </div>
      </div>

      <div class="row mt-3">
        <div class="form-group col-md-6 col-sm-12"></div>
        <!-- Botão para enviar o formulário -->
        <div class="form-group col-md-6 col-sm-12">
          <button type="submit" class="btn btn-primary btn-block">
            Cadastrar
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import axios from "../services/api.js";

export default {
  name: "RegisterForm", // Nome do componente
  data() {
    return {
      formData: {
        name: "", // Nome do usuário
        email: "", // E-mail do usuário
        cpf: "", // CPF do usuário
        profile_id: "", // ID do perfil selecionado
        logradouro: "", // Logradouro do endereço
        cep: "", // CEP do endereço
      },
      profiles: [], // Lista de perfis carregados da API
    };
  },
  methods: {
    /**
     * Carrega os perfis disponíveis da API.
     */
    async loadProfiles() {
      try {
        const response = await axios.get("/profile");
        this.profiles = response.data; // Armazena os perfis na variável `profiles`
      } catch (error) {
        console.error("Erro ao carregar perfis:", error);
      }
    },
    /**
     * Aplica a máscara de CPF no campo correspondente.
     */
    applyCpfMask() {
      const numbers = this.formData.cpf.replace(/\D/g, ""); // Remove caracteres não numéricos
      this.formData.cpf = numbers
        .replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, "$1.$2.$3-$4") // Aplica a máscara no formato XXX.XXX.XXX-XX
        .substring(0, 14); // Limita o campo a 14 caracteres
    },
    /**
     * Aplica a máscara de CEP no campo correspondente.
     */
    applyCepMask() {
      const numbers = this.formData.cep.replace(/\D/g, ""); // Remove caracteres não numéricos
      this.formData.cep = numbers
        .replace(/^(\d{5})(\d{3}).*/, "$1-$2") // Aplica a máscara no formato XXXXX-XXX
        .substring(0, 9); // Limita o campo a 9 caracteres
    },
    /**
     * Registra um novo usuário com os dados fornecidos no formulário.
     */
    async registerUser() {
      // Verifica se todos os campos estão preenchidos
      if (Object.values(this.formData).some((value) => !value)) {
        alert("Todos os campos são obrigatórios!");
        return;
      }

      // Solicita confirmação do usuário antes de enviar o formulário
      const confirmar = window.confirm(
        "Tem certeza de que deseja cadastrar este usuário?"
      );
      if (!confirmar) {
        return;
      }

      console.log(this.formData); // Exibe os dados no console para depuração
      try {
        const response = await axios.post("/users", this.formData); // Envia os dados para a API
        alert(response.data.message || "Usuário cadastrado com sucesso!");
        // Limpa o formulário após o sucesso
        this.formData = {
          name: "",
          email: "",
          cpf: "",
          profile_id: "",
          logradouro: "",
          cep: "",
        };
      } catch (error) {
        console.error("Erro ao cadastrar usuário:", error);
        alert(
          "Erro ao cadastrar usuário. Verifique os dados e tente novamente."
        );
      }
    },
  },
  /**
   * Carrega os perfis ao montar o componente.
   */
  async mounted() {
    await this.loadProfiles();
  },
};
</script>
