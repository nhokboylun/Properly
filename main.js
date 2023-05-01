function toggle() {
  var blur = document.getElementById("popup");
  blur.classList.toggle("active");
}

function toggleFilterForm() {
  const filterFormContainer = document.getElementById("filterFormContainer");
  filterFormContainer.style.display =
    filterFormContainer.style.display === "none" ? "block" : "none";
}

document.addEventListener("DOMContentLoaded", function () {
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

  const filterForm = document.getElementById("filterForm");
  filterForm.addEventListener("submit", (event) => {
    event.preventDefault(); // Prevent the default submit action
    toggleFilterForm();
    // Get the filter form values
    const formData = new FormData(filterForm);
    const searchParams = new URLSearchParams(formData);

    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (this.readyState === 4 && this.status === 200) {
        photoContainer.innerHTML = ""; // Clear the contents of the photo container
        photoContainer.insertAdjacentHTML("beforeend", this.responseText);
      }
    };

    xhr.open("GET", "main.php?" + searchParams.toString(), true);
    xhr.send();
  });
});
