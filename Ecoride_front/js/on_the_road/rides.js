// Il faudra récupérer les données
const ride = {
  user: "Donald",
  photo: new Image(),
  note: 0,
  origine: "Washington",
  destination: "Panama",
  price: 5,
  seats_available: 1,
  electric: false,
};
createACard(ride);
function createACard(ride) {
  // Create a card
  // Main card div + class content:
  let div1 = document.getElementById("cards");
  div1.classList.add(
    "card",
    "text-center",
    "m-3",
    "d-flex",
    "flex-row",
    "justify-content-around"
  );
  // 2nd div = 1st column (user name + photo + grade)
  let div2 = div1.appendChild(document.createElement("div"));
  // user name:
  userName = div2.appendChild(document.createElement("h5"));
  userName.innerHTML = ride.user;
  // photo
  photo = div2.appendChild(document.createElement("img"));
  photo.src = ride.photo;
  photo.classList.add("img-fluid");
  photo.src = "../../Pictures/Donald.png";
  photo.alt = "Photo du conducteur";
  // grade
  grade = div2.appendChild(document.createElement("p"));
  grade.innerHTML = ride.note;
  // div 3 = 2nd column (departure and arrival place and time)
  let div3 = div1.appendChild(document.createElement("div"));
  div3.classList.add("d-flex", "flex-column", "justify-content-around");
  // departure town
  let departure = div3.appendChild(document.createElement("p"));
  departure.innerHTML = ride.origine;
  // arrival town
  let destination = div3.appendChild(document.createElement("p"));
  destination.innerHTML = ride.destination;
  // div 4 = 3rd column (price + seats left)
  let div4 = div1.appendChild(document.createElement("div"));
  div4.classList.add("d-flex", "flex-column", "justify-content-around");
  // price
  let price = div4.appendChild(document.createElement("p"));
  price.innerHTML = ride.price;
  // number of seats left
  let seat = div4.appendChild(document.createElement("p"));
  seat.innerHTML = ride.seats_available;
  // div 5 = last column (electric car + link)
  let div5 = div1.appendChild(document.createElement("div"));
  div5.classList.add("d-flex", "flex-column", "justify-content-around");
  // check if using electric car
  let ecologic = div5.appendChild(document.createElement("p"));
  if (ride.electric) {
    ecologic.innerHTML = "Voyage écologique";
  } else {
    ecologic.innerHTML = "Voyage non écologique";
  }
  // link to detail
  let link = div5.appendChild(document.createElement("a"));
  link.innerHTML = "Voir détail / Réserver";
  link.href = "#";
  link.classList.add("btn", "btn-primary");
}
