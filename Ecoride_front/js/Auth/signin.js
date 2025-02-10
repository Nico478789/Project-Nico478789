const mailInput = document.getElementById("EmailInput");
const passInput = document.getElementById("PasswordInput");
const btnSignIn = document.getElementById("btnSignIn");
const formSignin = document.getElementById("formSignin");

btnSignIn.addEventListener("click", checkCredentials);

function checkCredentials() {
  let dataForm = new FormData(formSignin);

  //   Apeler l'API pour vérfication
  const myHeaders = new Headers();
  myHeaders.append("Content-Type", "application/json");

  const raw = JSON.stringify({
    username: dataForm.get("Email"),
    password: dataForm.get("Password"),
  });

  const requestOptions = {
    method: "POST",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };
  fetch(apiUrl + "login", requestOptions)
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        mailInput.classList.add("is-invalid");
        passInput.classList.add("is-invalid");
        alert("quelque chose s'est mal passé");
        throw new Error("Erreur dans les identifiants");
      }
    })
    .then((result) => {
      alert("vous êtes connecté");

      const token = result.apiToken;
      setToken(token);

      // placer l e token en cookie

      setCookie(roleCookieName, result.roles[0], 7);

      window.location.replace("/account");
    })
    .catch((error) => console.log("error", error));

  // fin de méthode fetch
}
