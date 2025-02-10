import Route from "./Route.js";

//Définir ici vos routes
export const allRoutes = [
  new Route("/", "Accueil", "/pages/home.html", []),
  new Route(
    "/rides",
    "Les covoiturages",
    "/pages/on_the_road/rides.html",
    [],
    "/js/on_the_road/rides.js"
  ),
  new Route(
    "/signin",
    "Connection",
    "/pages/Auth/signin.html",
    ["disconnected"],
    "/js/Auth/signin.js"
  ),
  new Route(
    "/signup",
    "Inscription",
    "/pages/Auth/signup.html",
    ["disconnected"],
    "/js/Auth/signup.js"
  ),
  new Route(
    "/account",
    "Mon compte",
    "/pages/Auth/account.html",
    ["ROLE_USER"],
    "/js/Auth/account.js"
  ),
  new Route("/trip", "Detail du trajet", "/pages/trip.html", ["user"]),
  new Route("/new_trip", "Nouveau trajet", "/pages/new_trip.html", ["user"]),
  new Route("/moderateur", "Modérateur", "/pages/moderateur.html", ["worker"]),
  new Route("/admin", "Administration", "/pages/admin.html", ["admin"]),
  new Route("/on_the_road/history", "Historique", "/pages/history.html", [
    "user",
  ]),
];

//Le titre s'affiche comme ceci : Route.titre - websitename
export const websiteName = "Ecoride";
