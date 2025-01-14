<template>
    <div id="main">
        <RegisterForm />
    </div>
</template>

<script>
import RegisterForm from '@/components/RegisterForm.vue';

export default{
    name: "Register",
    components: {
      RegisterForm
    }
}
</script>


<style scoped>
  #main {
    height: 75.8vh;
    display: flex;
    align-items: center;
    justify-content: center;
    bottom: 0;
    width: 100%;
  }
</style>