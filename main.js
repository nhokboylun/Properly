const searchButton = document.getElementById("searchButton");
const locationSearch = document.getElementById("locationSearch");
const photoContainer = document.getElementById("photo-container");

searchButton.addEventListener("click", () => {
  const searchCity = locationSearch.value;

  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      photoContainer.innerHTML = ""; // Clear the contents of the photo container
      photoContainer.insertAdjacentHTML("beforeend", this.responseText);
    }
  };

  xhr.open("POST", "main.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("search_city=" + searchCity);
});
