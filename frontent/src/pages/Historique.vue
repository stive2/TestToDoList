<template>
  <q-page >
    <div class="flex flex-center q-pa-md">
      <span class="text-h5 text-primary text-bold">HISTORIQUE</span>
    </div>
    <div class="q-mb-md">
      <div class="q-pa-md ">
        <div class="row">
          <div class="col-9">
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
          <div class="col-3">
            <q-input filled v-model="date"
              :rules="[ val => val && val != null || 'Choisir la date']" label="Date">
              <template v-slot:prepend>
                <q-icon name="event" class="cursor-pointer">
                  <q-popup-proxy transition-show="scale" transition-hide="scale">
                    <q-date v-model="date" mask="YYYY-MM-DD" :options="optionsFn">
                      <div class="row items-center justify-end">
                        <q-btn v-close-popup label="Close" color="primary" flat />
                      </div>
                    </q-date>
                  </q-popup-proxy>
                </q-icon>
              </template>
            </q-input>
          </div>
        </div>
        <div>
          <q-table
            color="primary"
            card-class="bg-blue-1 text-black text-bold"
            class="my-sticky-dynamic"
            virtual-scroll
            :virtual-scroll-sticky-size-start="48"
            wrap-cells
            dense
            bordered
            :data="taches"
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
              <q-btn color="primary" @click="getTacheF()" :disable="date == null">Afficher
                <q-tooltip content-class="bg-purple">Afficher l'historique</q-tooltip>
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
        </div>
      </div>
    </div>
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
      loading: false,
      spinner: false,
      date: null,
      separator: 'cell',
      taches: [],
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
      return date <= today
    },
    getTacheF () {
      this.loading = true
      this.taches = []
      this.$axios.get(`historique/${this.date}`)
        .then((response) => {
          this.taches = this.taches.concat(response.data.slice(0).map(r => ({ ...r })))

          this.taches.forEach((row, index) => {
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
