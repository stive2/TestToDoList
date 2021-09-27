<template>
  <q-page class="flex flex-center register">
    <div class="row" style="padding: 100px">
      <transition
        appear
        enter-active-class="animated fadeInLeft"
        leave-active-class="animated fadeOutRight"
      >
        <div>
          <span class="text-h4 text-bold text-blue">Inscrivez-vous!</span><br>
        </div>
      </transition>
    </div>
    <transition
      appear
      enter-active-class="animated fadeInRight"
      leave-active-class="animated fadeOutLeft"
    >
      <q-card square  class="flex-center text-center register" style="width: 300px; padding:10px; bordered">
        <q-form
          @submit="onSubmit"
          @reset="onReset"
          class="q-gutter-md"
          style="padding: 10px;"
        >
          <q-input
            bg-color="white"
            filled
            type="nom"
            v-model="form.nom"
            label="Nom d'utilisateur *"
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Entrer votre nom d\'utilisateur']"
          />

          <q-input
            bg-color="white"
            filled
            type="email"
            v-model="form.email"
            label="Email de l'utilisateur *"
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Entrer votre email']"
          >
            <template v-slot:prepend>
              <q-icon name="person" />
            </template>
          </q-input>

          <q-input
            bg-color="white"
            v-model="form.password"
            label="Mot de passe"
            filled
            :type="isPwd ? 'password' : 'text'"
            lazy-rules
            :rules="[ val => val && val.length > 6 || 'Entrez le mot de passe (+6 caractères)']"
          >
            <template v-slot:append>
              <q-icon
                :name="isPwd ? 'visibility_off' : 'visibility'"
                class="cursor-pointer"
                @click="isPwd = !isPwd"
              />
            </template>
          </q-input>
          <div>
            <q-btn label="S'inscrire" type="submit" color="primary" rounded/>
          </div>
          <q-spinner
            v-if="spinner == true"
            color="accent"
            size="3em"
          />
        </q-form>
        <router-link :to="{name: 'connexion'}" class="dark-grey-text ml-1 font-weight-bold" > Vous avez déjà un compte? Connectez-vous ici!</router-link><br>
      </q-card>
    </transition>
  </q-page>
</template>

<script>
export default {
  name: 'Inscription',
  data () {
    return {
      isPwd: true,
      spinner: false,
      form: {
        nom: '',
        email: '',
        password: ''
      }
    }
  },

  methods: {
    onSubmit () {
      this.spinner = true
      this.$axios.post('/register', this.form)
        .then((response) => {
          this.$q.notify({
            color: 'positive',
            textColor: 'white',
            icon: 'cloud_done',
            message: response.data.message
          })
          this.$router.push('/')
        })
        .catch((error) => {
          this.spinner = false
          this.$q.notify({
            color: 'negative',
            textColor: 'white',
            icon: 'error',
            message: error.response.data.message
          })
        })
    },
    onReset () {
      this.form.nom = ''
      this.form.email = ''
      this.form.password = ''
    },
    trigger () {
      const bar = this.$refs.bar

      bar.start()

      this.timer = setTimeout(() => {
        if (this.$refs.bar) {
          this.$refs.bar.stop()
        }
      }, Math.random() * 3000 + 1000)
    }
  }
}
</script>

<style scoped>
.register{
  /* background-image: url("https://www.letudiant.fr/uploads/mediatheque/ETU_ETU/3/3/2221833-istock-923796226-original.jpg"); */
  background-image: url('../assets/images/bg.png');
  /* Full height */
  height: 100%;

  /* Center and scale the image nicely */
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
};
</style>
