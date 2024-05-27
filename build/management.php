<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLV: RESERVA</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="flex h-screen bg-gray-100">
        
        <!-- Load the Sidebar here   -->
        <div id="sidebar-container">
            <?php include 'sidebar.php'; ?>
        </div>

        <!-- Content area -->
        <div class="flex flex-col flex-1">
            <header class="bg-white shadow-lg">
                <div class="flex items-center justify-between px-6 py-3 border-b">
                    <h2 class="text-lg font-semibold">
                        Management
                    </h2>
                    <!-- Dropdown menu -->
                    <div>
                        <select id="pageDropdown" onchange="loadPage(this.value)">
                            <option value="" disabled selected>Select a Page</option>
                            <option value="roomMngmnt.php">Page 1</option>
                            <option value="facilityMngmnt.php">Page 2</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                </div>
            </header>

            <!-- Main content area -->
            <main class="flex-1 p-4 h-screen" id="main-content">

            </main>
        </div>
    </div>

    <!-- Logout confirmation dialog it his hidden -->
    <div id="custom-dialog" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md flex flex-col items-center">
            <img class="w-36 mb-4" src="img/undraw_warning_re_eoyh.svg" alt="">
            <p class="text-lg text-slate-700 font-semibold mb-4">Are you sure you want to logout?</p>
            <div class="flex justify-center mt-5">
                <button onclick="cancelLogout()" class="mr-4 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Cancel</button>
                <button onclick="confirmLogout()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-500">Logout</button>
            </div>
        </div>
    </div>

    <script src="scripts/logout.js"></script>
    <script src="scripts/functions.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Check if the success parameter is present in the URL
        var urlParams = new URLSearchParams(window.location.search);
        var success = urlParams.has('success') && urlParams.get('success') === 'true';    
        if (success) {
            var successModal = document.getElementById('successModal');
            // Show modal
            successModal.classList.remove('hidden');
            }
        }); 

        function loadPage(page) {
        if (page) {
            fetch(page)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('main-content').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        }
    }
    </script>
</body>
</html>
