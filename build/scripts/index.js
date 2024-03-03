const loginBtn = document.getElementById('loginBtn');

  // Add click event listener to the login button
  loginBtn.addEventListener('click', () => {
    // Redirect to the dashboard page
    window.location.href = 'dashboard.html'; // Replace 'dashboard.html' with the actual URL of your dashboard page
  });

  function showCustomDialog() {
    document.getElementById('custom-dialog').classList.remove('hidden');
  }

  function confirmLogout() {
      // Redirect to index page after logout confirmation
      window.location.href = "index.html";
  }

  function cancelLogout() {
      document.getElementById('custom-dialog').classList.add('hidden');
}