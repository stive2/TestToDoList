export const LOGIN_OK = (state, user) => {
  state.User.nom = user.data.user.nom
  state.User.id = user.data.user.id
  state.User.email = user.data.user.email
  state.loggedIn = false
}
