<template>
  <q-page >
    <div class="flex flex-center q-pa-md">
      <span class="text-h5 text-primary text-bold">LISTE DES TACHES ACTUALISEE</span>
    </div>
    <div class="q-mb-md">
      <div class="q-pa-md ">
        <div class="row">
          <div class="col-10">
            <q-option-group
              v-model="separator"
              inline
              class="q-mb-md"
              :options="[
                { label: 'Horizontal', value: 'horizontal' },
                { label: 'Vertical', value: 'vertical' },
                { label: 'Cell (default)', value: 'cell' },
                { label: 'None', value: 'none' },
              ]"
            />
          </div>
          <div class="col-2">
            <q-btn color="primary" @click="clotureF()">Cloturer
              <q-tooltip content-class="bg-purple">Clôturer la liste des tâches pour la journée</q-tooltip>
            </q-btn>
          </div>
        </div>
        <div>
          <q-tabs
            v-model="tab"
            dense
            class="text-black"
            active-color="primary"
            indicator-color="primary"
            align="justify"
            narrow-indicator
          >
            <q-tab name="encours" label="Tâches en cours" />
            <q-tab name="achevees" label="Tâches achevées" />
            <q-tab name="faites" label="Tâches faites" />
          </q-tabs>

          <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="encours">
              <q-table
                color="primary"
                card-class="bg-blue-1 text-black text-bold"
                class="my-sticky-dynamic"
                virtual-scroll
                :virtual-scroll-sticky-size-start="48"
                wrap-cells
                dense
                bordered
                :data="encours"
                :columns="columns"
                row-key="name"
                :separator="separator"
                :filter="filter"
                no-data-label="Aucune tâche en cours."
                no-results-label="Nous n'avons trouvé aucun resultat correspondant au(x) mot(s) clé(s)."
                :pagination.sync="pagination"
                :rows-per-page-options="[0]"
              >
                <template v-slot:top-left>
                  <q-btn color="primary" icon="add" @click="tache = {}, addTache = true">Nouvelle
                    <q-tooltip content-class="bg-purple">Enregistrer une nouvelle tâche</q-tooltip>
                  </q-btn>
                </template>

                <template v-slot:top-right>
                  <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
                    <q-icon slot="append" name="search" />
                  </q-input>
                </template>

                <template v-slot:header="props">
                  <q-tr :props="props">
                    <q-th
                      v-for="col in props.cols"
                      :key="col.name"
                      :props="props"
                    >
                      {{ col.label }}
                    </q-th>
                    <q-th class="bg-blue-4">
                      Options
                    </q-th>
                  </q-tr>
                </template>

                <template v-slot:body="props">
                  <q-tr :props="props">
                    <q-td
                      v-for="col in props.cols"
                      :key="col.name"
                      :props="props"
                    >
                      {{ col.value }}
                    </q-td>
                    <q-td auto-width class="bg-blue-3">
                      <div class="row">
                        <q-btn color="info" icon="info" round push size="0.50em" @click="tache = props.row, modTache = true">
                          <q-tooltip content-class="bg-purple">Plus d'informations</q-tooltip>
                        </q-btn>
                        <q-space />
                        <q-btn color="green" icon="done" round push size="0.50em" @click="tache = props.row, doneTache = true">
                          <q-tooltip content-class="bg-purple">Tâche faite</q-tooltip>
                        </q-btn>
                        <q-space />
                        <q-btn color="red" icon="delete" round push size="0.50em" @click="tache = props.row, delTache = true">
                          <q-tooltip content-class="bg-purple">Supprimer la tâche de la base de données</q-tooltip>
                        </q-btn>
                      </div>
                    </q-td>
                  </q-tr>
                </template>

                <template v-slot:no-data="{ icon, message, filter }">
                  <div class="full-width row flex-center text-accent q-gutter-sm">
                    <q-icon size="2em" name="sentiment_dissatisfied" />
                    <span>
                      Désolé... {{ message }}
                    </span>
                    <q-icon size="2em" :name="filter ? 'filter_b_and_w' : icon" />
                  </div>
                </template>
              </q-table>
            </q-tab-panel>
            <q-tab-panel name="achevees">
              <q-table
                color="primary"
                card-class="bg-blue-1 text-black text-bold"
                class="my-sticky-dynamic"
                virtual-scroll
                :virtual-scroll-sticky-size-start="48"
                wrap-cells
                dense
                bordered
                :data="achevees"
                :columns="columns"
                row-key="name"
                :separator="separator"
                :filter="filter"
                no-data-label="Aucune tâche achevée."
                no-results-label="Nous n'avons trouvé aucun resultat correspondant au(x) mot(s) clé(s)."
                :pagination.sync="pagination"
                :rows-per-page-options="[0]"
              >
                <template v-slot:top-left>
                </template>

                <template v-slot:top-right>
                  <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
                    <q-icon slot="append" name="search" />
                  </q-input>
                </template>

                <template v-slot:header="props">
                  <q-tr :props="props">
                    <q-th
                      v-for="col in props.cols"
                      :key="col.name"
                      :props="props"
                    >
                      {{ col.label }}
                    </q-th>
                  </q-tr>
                </template>

                <template v-slot:body="props">
                  <q-tr :props="props">
                    <q-td
                      v-for="col in props.cols"
                      :key="col.name"
                      :props="props"
                    >
                      {{ col.value }}
                    </q-td>
                  </q-tr>
                </template>

                <template v-slot:no-data="{ icon, message, filter }">
                  <div class="full-width row flex-center text-accent q-gutter-sm">
                    <q-icon size="2em" name="sentiment_dissatisfied" />
                    <span>
                      Désolé... {{ message }}
                    </span>
                    <q-icon size="2em" :name="filter ? 'filter_b_and_w' : icon" />
                  </div>
                </template>
              </q-table>
            </q-tab-panel>
            <q-tab-panel name="faites">
              <q-table
                color="primary"
                card-class="bg-blue-1 text-black text-bold"
                class="my-sticky-dynamic"
                virtual-scroll
                :virtual-scroll-sticky-size-start="48"
                wrap-cells
                dense
                bordered
                :data="faites"
                :columns="columns"
                row-key="name"
                :separator="separator"
                :filter="filter"
                no-data-label="Aucune tâche en cours."
                no-results-label="Nous n'avons trouvé aucun resultat correspondant au(x) mot(s) clé(s)."
                :pagination.sync="pagination"
                :rows-per-page-options="[0]"
              >
                <template v-slot:top-left>
                </template>

                <template v-slot:top-right>
                  <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
                    <q-icon slot="append" name="search" />
                  </q-input>
                </template>

                <template v-slot:header="props">
                  <q-tr :props="props">
                    <q-th
                      v-for="col in props.cols"
                      :key="col.name"
                      :props="props"
                    >
                      {{ col.label }}
                    </q-th>
                  </q-tr>
                </template>

                <template v-slot:body="props">
                  <q-tr :props="props">
                    <q-td
                      v-for="col in props.cols"
                      :key="col.name"
                      :props="props"
                    >
                      {{ col.value }}
                    </q-td>
                  </q-tr>
                </template>

                <template v-slot:no-data="{ icon, message, filter }">
                  <div class="full-width row flex-center text-accent q-gutter-sm">
                    <q-icon size="2em" name="sentiment_dissatisfied" />
                    <span>
                      Désolé... {{ message }}
                    </span>
                    <q-icon size="2em" :name="filter ? 'filter_b_and_w' : icon" />
                  </div>
                </template>
              </q-table>
            </q-tab-panel>
          </q-tab-panels>
        </div>
      </div>
    </div>

    <q-dialog v-model="addTache" persistent transition-hide="scale" transition-show="scale">
      <q-card style="width: 450px">
        <q-card-section>
          <span class="text-h6">Nouvelles tâches</span><br>
          <span class="text-red">* : champ obligatoire</span>
          <q-form
            @submit="addTacheF"
            class="q-gutter-md"
            style="padding: 10px"
          >
            <div v-for="(find, index) in finds" :key="find.index">
              <q-input label="Titre de la tâche *" v-model="find.titre"
                :rules="[
                  val => val && val != '' || 'Entrez le titre de la tâche',
                  val => val.length <= 100 || 'Vous devez entrer moins de 100 caractères.'
                ]"
              />
              <q-input label="Description de la tâche " v-model="find.description" type="textarea"/>

              <q-input filled v-model="find.date_fin"
                :rules="[ val => val && val != null || 'Entrer la date de fin']" label="Date de fin">
                <template v-slot:prepend>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy transition-show="scale" transition-hide="scale">
                      <q-date v-model="find.date_fin" mask="YYYY-MM-DD HH:mm" :options="optionsFn">
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="Close" color="primary" flat />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
                </template>

                <template v-slot:append>
                  <q-icon name="access_time" class="cursor-pointer">
                    <q-popup-proxy transition-show="scale" transition-hide="scale">
                      <q-time v-model="find.date_fin" mask="YYYY-MM-DD HH:mm" format24h >
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="Close" color="primary" flat />
                        </div>
                      </q-time>
                    </q-popup-proxy>
                  </q-icon>
                </template>
              </q-input>

              <button @click="deleteFind(index)">
                delete
              </button>
            </div>

            <q-btn @click="addFind" color="primary">
              Ajouter une tâche
            </q-btn>

            <div class="row">
              <div class="col-3">
                <q-spinner
                  v-if="spinner == true"
                  color="accent"
                  size="3em"
                />
              </div>
              <div class="col-9">
                <q-btn label="Enregistrer" type="submit" color="primary"/>
                <q-btn label="Annuler" color="primary" flat @click="finds = []" class="q-ml-sm" v-close-popup/>
              </div>
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

    <q-dialog v-model="modTache" persistent transition-hide="scale" transition-show="scale">
      <q-card style="width: 450px">
        <q-card-section>
          <span class="text-h6">Les informations de la tâche</span><br>
          <span class="text-red">* : champ obligatoire</span>
          <q-form
            @submit="modTacheF"
            class="q-gutter-md"
            style="padding: 10px"
          >
            <q-input label="Titre de la tâche *" v-model="tache.titre"
              :readonly="mt"
              :rules="[
                val => val && val != '' || 'Entrez le titre de la tâche',
                val => val.length <= 100 || 'Vous devez entrer moins de 100 caractères.'
              ]"
            />
            <q-input label="Description de la tâche " v-model="tache.description" type="textarea" :readonly="mt"/>

            <q-input filled v-model="tache.date_fin" :readonly="mt"
              :rules="[ val => val && val != null || 'Entrer la date de fin']" label="Date de fin">
              <template v-slot:prepend>
                <q-icon name="event" class="cursor-pointer">
                  <q-popup-proxy transition-show="scale" transition-hide="scale">
                    <q-date v-model="tache.date_fin" mask="YYYY-MM-DD HH:mm" :options="optionsFn">
                      <div class="row items-center justify-end">
                        <q-btn v-close-popup label="Close" color="primary" flat />
                      </div>
                    </q-date>
                  </q-popup-proxy>
                </q-icon>
              </template>

              <template v-slot:append>
                  <q-icon name="access_time" class="cursor-pointer">
                    <q-popup-proxy transition-show="scale" transition-hide="scale">
                      <q-time v-model="tache.date_fin" mask="YYYY-MM-DD HH:mm" format24h >
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="Close" color="primary" flat />
                        </div>
                      </q-time>
                    </q-popup-proxy>
                  </q-icon>
                </template>
            </q-input>
            <div class="row">
              <div class="col-3">
                <q-spinner
                  v-if="spinner == true"
                  color="accent"
                  size="3em"
                />
              </div>
              <div v-if="mt == true">
                <q-btn label="Modifier" @click="mt = false" color="primary"/>
                <q-btn label="Ok" flat v-close-popup class="q-ml-sm"/>
              </div>
              <div class="col-9" v-else>
                <q-btn label="Enregistrer" type="submit" color="primary"/>
                <q-btn label="Annuler" color="primary" flat @click="mt = true" class="q-ml-sm" v-close-popup/>
              </div>
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

    <q-dialog v-model="delTache" persistent transition-hide="scale" transition-show="scale">
      <q-card class="bg-teal text-white" style="width: 300px">
        <q-card-section>
          Voulez vous supprimer la tâche {{ tache.titre}}?
        </q-card-section>
        <q-card-section>
          <q-spinner
            v-if="spinner == true"
            color="accent"
            size="3em"
          />
        </q-card-section>
        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn flat color="primary" label="Confirmer" @click="delTacheF()"/>
          <q-btn flat color="primary" label="Annuler" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <q-dialog v-model="doneTache" persistent transition-hide="scale" transition-show="scale">
      <q-card class="bg-teal text-white" style="width: 300px">
        <q-card-section>
          Voulez vous renseigner la tâche {{ tache.titre }} comme faite?
        </q-card-section>
        <q-card-section>
          <q-spinner
            v-if="spinner == true"
            color="accent"
            size="3em"
          />
        </q-card-section>
        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn flat color="primary" label="Confirmer" @click="doneTacheF()"/>
          <q-btn flat color="primary" label="Annuler" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog v-model="loading">
      <q-spinner-hourglass
        v-if="loading == true"
        color="primary"
        size="5em"
      />
    </q-dialog>
    <q-page-sticky expand position="top-right">
      <q-chip>
        Salut {{ this.$store.state.auth.User.nom }}
      </q-chip>
    </q-page-sticky>
  </q-page>
</template>

<script>

export default {
  name: 'PageIndex',

  data () {
    return {
      pagination: {
        rowsPerPage: 0
      },
      tab: 'encours',
      mt: true,
      loading: false,
      spinner: false,
      addTache: false,
      modTache: false,
      delTache: false,
      doneTache: false,
      separator: 'cell',
      finds: [],
      item: {},
      encours: [],
      achevees: [],
      faites: [],
      tache: {},
      filter: '',
      columns: [
        {
          name: 'index',
          label: '#',
          field: 'index',
          classes: 'bg-blue-2 ellipsis',
          headerClasses: 'bg-primary text-white'
        },
        {
          name: 'desc',
          required: true,
          label: 'Titre',
          align: 'left',
          field: row => row.titre,
          format: val => `${val}`,
          sortable: true,
        },
        { name: 'description', align: 'left', label: 'description', field: 'description', sortable: true, sort: (a, b) => parseInt(a, 10) - parseInt(b, 10)},
        { name: 'date_fin', align: 'left', label: 'Date de fin', field: 'date_fin', sortable: true, sort: (a, b) => parseInt(a, 10) - parseInt(b, 10)},
        { name: 'statut', align: 'left', label: 'Statut', field: 'statut', sortable: true, sort: (a, b) => parseInt(a, 10) - parseInt(b, 10)},
        { name: 'date_enreg', align: 'left', label: 'Date de création', field: 'date_enreg', sortable: true, sort: (a, b) => parseInt(a, 10) - parseInt(b, 10)},
        { name: 'user', align: 'left', label: 'Créateur', field: 'user', sortable: true, sort: (a, b) => parseInt(a, 10) - parseInt(b, 10)}
      ]
    }
  },
  methods: {
    optionsFn (date) {
      const day = new Date()
      var today = day.getFullYear() + '/' + ('0' + (day.getMonth() + 1)).slice(-2) + '/' + ('0' + day.getDate()).slice(-2)
      return date >= today
    },
    addFind: function () {
      this.finds.push({ def: '' });
    },
    deleteFind: function (index) {
      this.finds.splice(index, 1);
    },
    addTacheF () {
      this.spinner = true
      this.item.taches = this.finds
      this.$axios.post('/createTache', this.item)
        .then((response) => {
          this.$q.notify({
            color: 'positive',
            textColor: 'white',
            icon: 'cloud_done',
            message: response.data.message
          })
          this.spinner = false
          this.finds = []
          this.item = {}
          this.tache = {}
          this.getTacheF()
          this.addTache = false
        })
        .catch((error) => {
          this.spinner = false
          this.$q.notify({
            color: 'negative',
            textColor: 'white',
            icon: 'error',
            message: error.response.data.message
          })
          if (error.response.data.message === 'Token has expired') {
            this.$router.push('/')
          }
        })
    },
    modTacheF () {
      this.spinner = true
      this.$axios.put(`/updateTache/${this.tache.id}`, this.tache)
        .then((response) => {
          this.$q.notify({
            color: 'positive',
            textColor: 'white',
            icon: 'cloud_done',
            message: response.data.message
          })
          this.spinner = false
          this.getTacheF()
          this.mt = true
        })
        .catch((error) => {
          this.spinner = false
          this.$q.notify({
            color: 'negative',
            textColor: 'white',
            icon: 'error',
            message: error.response.data.message
          })
          if (error.response.data.message === 'Token has expired') {
            this.$router.push('/')
          }
        })
    },
    delTacheF () {
      this.spinner = true
      this.$axios.delete(`/deleteTache/${this.tache.id}`)
        .then((response) => {
          this.$q.notify({
            color: 'positive',
            textColor: 'white',
            icon: 'cloud_done',
            message: response.data.message
          })
          this.spinner = false
          this.tache = {}
          this.getTacheF()
          this.delTache = false
        })
        .catch((error) => {
          this.spinner = false
          this.$q.notify({
            color: 'negative',
            textColor: 'white',
            icon: 'error',
            message: error.response.data.message
          })
          if (error.response.data.message === 'Token has expired') {
            this.$router.push('/')
          }
        })
    },
    doneTacheF () {
      this.spinner = true
      this.$axios.put(`/doneTache/${this.tache.id}`)
        .then((response) => {
          this.$q.notify({
            color: 'positive',
            textColor: 'white',
            icon: 'cloud_done',
            message: response.data.message
          })
          this.spinner = false
          this.tache = {}
          this.getTacheF()
          this.doneTache = false
        })
        .catch((error) => {
          this.spinner = false
          this.$q.notify({
            color: 'negative',
            textColor: 'white',
            icon: 'error',
            message: error.response.data.message
          })
          if (error.response.data.message === 'Token has expired') {
            this.$router.push('/')
          }
        })
    },
    getTacheF () {
      this.loading = true
      this.encours = []
      this.achevees = []
      this.faites = []
      this.$axios.get('/tachesEnCours')
        .then((response) => {
          this.encours = this.encours.concat(response.data.slice(0).map(r => ({ ...r })))
          this.encours.forEach((row, index) => {
            row.index = index + 1
          })
        })
      this.$axios.get('/tachesAchevees')
        .then((response) => {
          this.achevees = this.achevees.concat(response.data.slice(0).map(r => ({ ...r })))

          this.achevees.forEach((row, index) => {
            row.index = index + 1
          })
        })
      this.$axios.get('/tachesFaites')
        .then((response) => {
          this.faites = this.faites.concat(response.data.slice(0).map(r => ({ ...r })))

          this.faites.forEach((row, index) => {
            row.index = index + 1
          })
          this.loading = false
        })
        .catch((error) => {
          this.loading = false
          this.$q.notify({
            color: 'negative',
            textColor: 'white',
            icon: 'error',
            message: error.response.data.message
          })
          if (error.response.data.message === 'Token has expired') {
            this.$router.push('/')
          }
        })
    },
    clotureF () {
      this.loading = true
      this.$axios.post('/clotureTache')
        .then((response) => {
          this.$q.notify({
            color: 'positive',
            textColor: 'white',
            icon: 'cloud_done',
            message: response.data.message
          })
          this.getTacheF()
          this.loading = false
        })
        .catch((error) => {
          this.loading = false
          this.$q.notify({
            color: 'negative',
            textColor: 'white',
            icon: 'error',
            message: error.response.data.message
          })
          if (error.response.data.message === 'Token has expired') {
            this.$router.push('/')
          }
        })
    },
  },
  created () {
    this.getTacheF()
  }
}
</script>

<style lang="sass">
.my-sticky-dynamic
  /* height or max-height is important */
  height: 500px

  .q-table__top,
  .q-table__bottom,
  thead tr:first-child th /* bg color is important for th; just specify one */
    background-color: #fff

  thead tr th
    position: sticky
    z-index: 1
  /* this will be the loading indicator */
  thead tr:last-child th
    /* height of all previous header rows */
    top: 48px
  thead tr:first-child th
    top: 0
</style>
