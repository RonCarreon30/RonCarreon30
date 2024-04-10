// Function to fetch facilities data from the backend
function fetchFacilities() {
    fetch('fetch_facilities.php') // Replace 'fetch_facilities.php' with the endpoint to fetch facilities data from your backend
        .then(response => response.json())
        .then(data => displayFacilities(data))
        .catch(error => console.error('Error fetching facilities:', error));
}

// Call the fetchFacilities function to fetch data when the page loads
fetchFacilities();


// Function to display facilities as cards
function displayFacilities(facilities) {
    const facilityCards = document.getElementById('facilityCards');
    facilityCards.innerHTML = '';

    facilities.forEach(facility => {
        const card = document.createElement('div');
        card.className = 'bg-white p-4 rounded-md shadow-md cursor-pointer';
        card.innerHTML = `
            <h2 class="text-lg font-bold">${facility.name}</h2>
            <p class="text-sm">${facility.description}</p>
        `;
        card.addEventListener('click', () => showReservationForm(facility));
        facilityCards.appendChild(card);
    });
}

// Function to show the reservation form for a selected facility
function showReservationForm(facility) {
    const reservationForm = document.getElementById('reservationForm');
    reservationForm.classList.remove('hidden');
    // Update the form fields or perform any other necessary actions based on the selected facility
}

// Function to filter facilities based on search input
function filterFacilities() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const filteredFacilities = facilities.filter(facility => facility.name.toLowerCase().includes(searchInput));
    displayFacilities(filteredFacilities);
}

// Event listener for search input
document.getElementById('searchInput').addEventListener('input', filterFacilities);

// Initial display of facilities when the page loads
displayFacilities(facilities);

// Function to toggle the visibility of the reservation form
function toggleReservationForm() {
    const reservationForm = document.getElementById('reservationForm');
    reservationForm.classList.toggle('hidden');
}

// Event listener for the close button
document.getElementById('closeForm').addEventListener('click', toggleReservationForm);

