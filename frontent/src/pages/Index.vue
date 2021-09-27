<template>
  <q-page class="flex flex-center register">
    <div class="row" style="padding: 100px">
      <transition
        appear
        enter-active-class="animated fadeInLeft"
        leave-active-class="animated fadeOutRight"
      >
        <div>
          <span class="text-h4 text-bold text-blue">Connectez-vous!!</span><br>
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
            :rules="[ val => val && val.length > 0 || 'Please type something']"
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
            <q-btn label="Login" type="submit" color="primary" rounded/>
          </div>
          <q-spinner
            v-if="spinner == true"
            color="accent"
            size="3em"
          />
        </q-form>
        <router-link :to="{name: 'inscription'}" class="dark-grey-text ml-1 font-weight-bold" > Pas de compte? inscrivez-vous ici!</router-link><br>
      </q-card>
    </transition>
  </q-page>
</template>

<script>
export default {
  name: 'PageIndex',
  data () {
    return {
      isPwd: true,
      spinner: false,
      form: {
        email: '',
        password: ''
      }
    }
  },

  methods: {
    onSubmit () {
      this.spinner = true

      this.$auth.login({
        fetchUser: false,
        data: this.form,
        rememberMe: this.rememberMe
      })
        .then(response => {
          this.spinner = false
          this.$router.push('/todolist')
        }, (error) => {
          console.log('Res: ', error.response.data.message)
          this.$q.notify({
            color: 'red-5',
            textColor: 'white',
            icon: 'warning',
            message: error.response.data.message
          })
          this.spinner = false
        })
    },
    onReset () {
      this.form.email = null
      this.form.password = null
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
