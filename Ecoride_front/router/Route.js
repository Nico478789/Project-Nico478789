export default class Route {
  constructor(url, title, pathHtml, authorize, pathJS = "") {
    this.url = url;
    this.title = title;
    this.pathHtml = pathHtml;
    this.authorize = authorize;
    this.pathJS = pathJS;
  }
}
/** authorize
 * [] tout le monde peut y accéder
 * ["disconnected"] réservé aux utilisateurs déconnectés
 * ["user"] réservé aux utilisateurs avec le role user
 * ["admin"] réservé aux utilisateurs avec le role admin
 * ["worker"] réservé aux utilisateurs avec le role worker
 */
