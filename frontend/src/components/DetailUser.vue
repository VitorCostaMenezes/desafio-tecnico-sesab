<template>
    <div>
        <div class="modal fade " id="ExemploModalCentralizado" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="TituloModalCentralizado">Detalhes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div v-if="user">
                        <p><strong>ID:</strong> {{ user.id }}</p>
                        <p><strong>Nome:</strong> {{ user.name }}</p>
                        <p><strong>Email:</strong> {{ user.email }}</p>
                        <p><strong>CPF:</strong> {{ user.cpf }}</p>
                        <p><strong>Perfil:</strong> {{ user.profile.profile_name }}</p>
                        <p><strong>Data de Cadastro:</strong> {{ user.created_at }}</p>

                        <table class="table">
                            <thead class="thead-dark">
                                    <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Logradouro</th>
                                    <th scope="col">CEP</th>
                                </tr>
                            </thead>
                                <tbody>
                                    <tr
                                        class="burger-table-row"
                                        v-for="adress in user.adress"
                                        :key="adress.id"
                                        >
                                    <td>{{adress.id}}</td>
                                    <td>{{adress.logradouro}}</td>
                                    <td>{{adress.cep}}</td>
                                </tr>
                    
                            </tbody>
                        </table>


                    </div>
                    <p v-else>Carregando informações...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        </div>

    </div>

</template>



<script>
import axios from "../services/api.js";

export default {
  name: "DetailUser",
  props: {
    userId: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      user: null,
    };
  },
  watch: {
    userId: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          this.getUser(newVal);
        }
      },
    },
  },
    methods: {
    async getUser(id) {
        try {
        const response = await axios.get(`/users/${id}`);
        this.user = {
            ...response.data,
            created_at: this.formatarData(response.data.created_at), // Formata a data ao carregar
        };
        } catch (error) {
        console.error("Erro ao buscar usuário:", error);
        }
    },

    formatarData(data) {
        if (!data) return "";
        const date = new Date(data);
        const dia = String(date.getDate()).padStart(2, "0");
        const mes = String(date.getMonth() + 1).padStart(2, "0");
        const ano = date.getFullYear();
        return `${dia}/${mes}/${ano}`;
    },
    },

};
</script>
