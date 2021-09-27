<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-toolbar-title class="text-bold">
          To do list App
          <a href="http://cevetco.com/maquette/" target="_blank" style="font-size: 15px">Consulter la maquette du premier test ici </a>
        </q-toolbar-title>
        <q-btn flat dense round icon="list" to="todolist">
          <q-tooltip content-class="bg-purple">Liste des tâches</q-tooltip>
        </q-btn>
        <q-btn flat dense round icon="history" to="historique">
          <q-tooltip content-class="bg-purple">Consulter l'historique</q-tooltip>
        </q-btn>
        <q-btn flat dense round icon="power_settings_new" @click="logout()" >
          <q-tooltip content-class="bg-purple">Se déconnecter</q-tooltip>
        </q-btn>
      </q-toolbar>
    </q-header>

    <q-page-container class="bg-grey-2">
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { openURL } from 'quasar'
import { mapMutations } from 'vuex'

export default {
  name: 'MainLayout',
  data () {
    return {
      leftDrawerOpen: false
    }
  },
  methods: {
    ...mapMutations('auth', [
      'LOGIN_OK'
    ]),
    openURL,
    logout () {
      this.$auth.logout({
        makeRequest: true,
        redirect: '/',
        success () {
          this.$router.push('/')
          console.log('success ')
        },
        error () {
          this.$router.push('/')
          console.log('error ')
        }
      })
    }
  },
  mounted: function () {
    this.$auth.fetch({})
      .then(response => {
        this.LOGIN_OK(response)
      })
  }
}
</script>
