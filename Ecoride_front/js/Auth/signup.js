const inputName = document.getElementById("user_name");
const inputMail = document.getElementById("EmailInput");
const inputPassword = document.getElementById("PasswordInput");
const inputPassword2 = document.getElementById("PasswordCheck");
const btnConfirm = document.getElementById("btn_suscribe");
const formSuscribe = document.getElementById("form_suscribe");
btnConfirm.disabled = true;

inputName.addEventListener("keyup", validateForm);
inputMail.addEventListener("keyup", validateForm);
inputPassword.addEventListener("keyup", validateForm);
inputPassword2.addEventListener("keyup", validateForm);
btnConfirm.addEventListener("click", suscribeUser);

function validateForm() {
  const nameOk = validateRequired(inputName);
  const mailOk = validateMail(inputMail);
  const passwordOk = validatePassword(inputPassword);
  const password2Ok = validatePassword2(inputPassword, inputPassword2);

  if (nameOk && mailOk && passwordOk && password2Ok) {
    btnConfirm.disabled = false;
  } else {
    btnConfirm.disabled = true;
  }
}

function validateMail(input) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const mailUser = input.value;
  if (mailUser.match(emailRegex)) {
    input.classList.add("is-valid");
    input.classList.remove("is-invalid");
    return true;
  } else {
    input.classList.add("is-invalid");
    input.classList.remove("is-valid");
    return false;
  }
}

function validatePassword(input) {
  const passwordRegex =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/;
  const passwordUser = input.value;
  if (passwordUser.match(passwordRegex)) {
    input.classList.add("is-valid");
    input.classList.remove("is-invalid");
    return true;
  } else {
    input.classList.add("is-invalid");
    input.classList.remove("is-valid");
    return false;
  }
}

function validatePassword2(input, input2) {
  const passwordUser = input.value;
  const password2User = input2.value;
  if (passwordUser != "" && passwordUser == password2User) {
    input2.classList.add("is-valid");
    input2.classList.remove("is-invalid");
    return true;
  } else {
    input2.classList.add("is-invalid");
    input2.classList.remove("is-valid");
    return false;
  }
}

function validateRequired(input) {
  if (input.value != "") {
    input.classList.add("is-valid");
    input.classList.remove("is-invalid");
    return true;
  } else {
    input.classList.add("is-invalid");
    input.classList.remove("is-valid");
    return false;
  }
}

function suscribeUser() {
  let dataForm = new FormData(formSuscribe);

  const myHeaders = new Headers();
  myHeaders.append("Content-Type", "application/json");

  const raw = JSON.stringify({
    email: dataForm.get("Email"),
    password: dataForm.get("Password"),
    user_name: dataForm.get("user_name"),
    credits: 20,
    driver: false,
  });

  const requestOptions = {
    method: "POST",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };

  fetch(apiUrl + "registration", requestOptions)
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        alert("Erreur lors de l'inscription");
        throw new Error("Erreur");
      }
    })
    .then((result) => {
      alert(
        "Bravo " +
          dataForm.get("user_name") +
          ", vous êtes maintenant inscrit, vous pouvez vous connecter."
      );
      document.location.href = "/signin";
    })
    .catch((error) => console.log("error", error));
}
