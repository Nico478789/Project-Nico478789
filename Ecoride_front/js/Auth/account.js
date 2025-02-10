const userMail = document.getElementById("EmailInput");
const welcome = document.getElementById("welcome");
const phone = document.getElementById("phoneId");
const userUpdate = document.getElementById("updateUser");
const updateForm = document.getElementById("form_update");
const image = document.getElementById("photo");
console.log(Object.entries(image));

token = getToken();

userUpdate.addEventListener("click", Update);

//Fetch : get the user data from database
const myHeaders = new Headers();
myHeaders.append("X-AUTH-TOKEN", token);

const raw = "";

const requestOptions = {
  method: "GET",
  headers: myHeaders,
  redirect: "follow",
};

fetch(apiUrl + "account/me", requestOptions)
  .then((response) => {
    if (response.ok) {
      return response.json();
    } else {
      alert("ça marche pas");
    }
  })
  .then((result) => {
    welcome.innerHTML =
      "bienvenue " + result.username + " votre crédit est de " + result.credits;
    phone.value = result.phoneNumber;
  })

  .catch((error) => console.error(error));

//Fin de fetch

// Update user database
function Update() {
  let dataForm = new FormData(updateForm);
  const myHeaders = new Headers();
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("X-AUTH-TOKEN", token);

  const raw = JSON.stringify({
    phoneNumber: dataForm.get("phoneName"),
    // photo: dataForm.get("photo"),
  });
  const requestOptions = {
    method: "PUT",
    headers: myHeaders,
    body: raw,
    redirect: "follow",
  };
  fetch(apiUrl + "account/edit", requestOptions)
    .then((response) => response.text())
    .then((result) => console.log(result))
    .catch((error) => console.error(error));
}

//End update user
