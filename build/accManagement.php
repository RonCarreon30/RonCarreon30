<?php
require_once "config.php";
// Query to fetch user data from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>
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
                    <h2 class="text-lg font-semibold">Account Management</h2>
                </div>
            </header>

            <!-- Main content area -->
            <main class="flex-1 p-4 h-screen">
                <div class="flex items-center justify-between p-1 rounded-md">
                    <div class="flex items-center">
                        <input type="text" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400" placeholder="Search...">
                        <button class="ml-2 px-4 py-2 bg-plv-blue text-white rounded-md flex items-center justify-center hover:bg-plv-highlight focus:outline-none focus:ring focus:ring-plv-highlight">
                            <img src="img/icons8-search-50.png" alt="Search Icon" class="w-4 h-4 mr-2">
                            Search
                        </button>
                    </div>
                    <div>
                        <button onclick="showUserForm()" class="ml-auto px-4 py-2 bg-plv-blue text-white rounded-md flex items-center justify-center hover:bg-plv-highlight focus:outline-none focus:ring focus:ring-plv-highlight">
                            <img src="img/icons8-add-user-30.png" alt="Add Account Icon" class="w-4 h-4 mr-2">
                            Add User
                        </button>
                    </div>
                </div>               
            
                <!-- User List -->
                <div class="mt-2">
                    <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">Something went wrong.</span>
                    </div>
                    <div class="bg-white py-2.5 shadow sm:rounded-lg sm:px-10 overflow-y-auto max-h-[calc(100vh-200px)]">
                        <h2 class="text-center text-lg font-semibold text-gray-900 mb-4">User List</h2>
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-3 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="userList" class="bg-white divide-y divide-gray-200">
                                <?php
                                // Output user data dynamically
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='px-3 py-4 whitespace-nowrap'>" . $row["first_name"] . " " . $row["last_name"] . "</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["email"] . "</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["department"] . "</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["userRole"] . "</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap'>
                                                <div class='flex items-center'>
                                                    <button type='button' class='inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2' onclick='editUser(" . $row["id"] . ")'>
                                                        Edit
                                                    </button>
                                                    <button type='button' class='ml-2 inline-flex justify-center rounded-md border border-red-500 shadow-sm px-4 py-2 bg-red-500 text-sm font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2' onclick='deleteUser(" . $row["id"] . ")'>
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No users found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--Add User Form-->      
                <div id="addUserForm" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md flex flex-col items-center">
                        <h1 class="text-center mb-10 text-slate-700 font-semibold text-xl">ADD USER ACCOUNT</h1>
                        <form method="post" action="create_user.php" id="createUserForm" class="space-y-10">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input id="firstName" name="firstName" type="text" required class="shadow-sm p-1 focus:ring-blue-500 focus:border-blue-500 block w-full border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input id="lastName" name="lastName" type="text" required class="shadow-sm p-1 focus:ring-blue-500 focus:border-blue-500 block w-full border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input id="email" name="email" type="email" required class="shadow-sm p-1 focus:ring-blue-500 focus:border-blue-500 block w-full border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="contactNumber" class="block text-sm font-medium text-gray-700">Contact Number</label>
                                    <input id="contactNumber" name="contactNumber" type="tel" required class="shadow-sm p-1 focus:ring-blue-500 focus:border-blue-500 block w-full border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                                    <select id="department" name="department" required class="shadow-sm p-1 focus:ring-blue-500 focus:border-blue-500 block w-full border border-gray-300 rounded-md">
                                        <option value="Accountancy">Accountancy</option>
                                        <option value="Business Administration">Business Administration</option>
                                        <option value="Arts and Sciences">Arts and Sciences</option>
                                        <option value="Education">Education</option>
                                        <option value="Public Administration">Public Administration</option>
                                        <option value="Civil Engineering">Civil Engineering</option>
                                        <option value="Information Technology">Information Technology</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700">User Role</label>
                                    <select id="role" name="role" required class="shadow-sm p-1 focus:ring-blue-500 focus:border-blue-500 block w-full border border-gray-300 rounded-md">
                                        <option value="Admin">Admin</option>
                                        <option value="Registrar">Registrar</option>
                                        <option value="Facility Head">Facility Head</option>
                                        <option value="Dept. Head">Dept. Head</option>
                                        <option value="Student Rep">Student Rep</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                    <input id="password" name="password" type="password" required autocomplete="new-password" class="shadow-sm p-1 focus:ring-blue-500 focus:border-blue-500 block w-full border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <input id="confirmPassword" name="confirmPassword" type="password" required autocomplete="new-password" class="shadow-sm p-1 focus:ring-blue-500 focus:border-blue-500 block w-full border border-gray-300 rounded-md">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <button onclick="closeForm()" class="col-span-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Cancel</button>
                                <button type="submit" onclick="SubmitForm()" class="col-span-1 px-4 py-2 bg-plv-blue text-white rounded-lg hover:bg-plv-highlight focus:outline-none focus:ring focus:ring-plv-highlight">Create Account</button>
                            </div>
                        </form>
                    </div>
                </div> 
            </main> 
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed z-10 inset-0 bg-black bg-opacity-50 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background Overlay -->
                <div class="fixed inset-0 bg-gray-500 opacity-75"></div>
                <!-- Modal Content -->
                <div class="bg-white rounded-lg p-8 max-w-sm mx-auto relative">
                    <!-- Green Check Icon -->
                    <img src="img/undraw_completing_re_i7ap.svg" alt="Success Image" class="mx-auto mb-4 w-16 h-20">
                    <!-- Modal Header -->
                    <h2 class="text-lg font-semibold mb-4">Account Created Successfully!</h2>
                    <!-- Close Button -->
                    <button onclick="closeSuccessModal()" class="absolute top-0 right-0 mt-2 mr-2 focus:outline-none">
                        <svg class="w-6 h-6 text-gray-500 hover:text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
    </div>
        
    <!-- confirm logout modal -->
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
    <script src="scripts/accMngmnt.js"></script>
    <!-- JavaScript for edit and delete actions -->
    <script>
        function editUser(userId) {
            // Implement edit user functionality here
            console.log("Edit user with ID: " + userId);
        }

        function deleteUser(userId) {
            // Implement delete user functionality here
            console.log("Delete user with ID: " + userId);
        }

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

        function closeSuccessModal() {
            var successModal = document.getElementById('successModal');
            // Hide modal
            successModal.classList.add('hidden');
                
            // Reset success parameter to false
            var urlParams = new URLSearchParams(window.location.search);
            urlParams.set('success', 'false');
            var newUrl = window.location.pathname + '?' + urlParams.toString();
            window.history.replaceState({}, '', newUrl);
        }
    </script>
</body>
</html>
<?php
// Close MySQL connection
$conn->close();
?>
