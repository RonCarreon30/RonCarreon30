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
        <!-- Sidebar -->
        <!-- Component Start -->
        <div class="flex flex-col items-center w-16 h-full overflow-hidden text-blue-200 bg-plv-blue rounded-r-lg">
            <a class="flex items-center justify-center mt-3" href="#">
                <img class="w-8 h-8" src="img/PLV Logo.png" alt="Logo">
            </a>
            <div class="flex flex-col items-center mt-3 border-t border-gray-700">
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="adminDashboard.html" title="Dashboard">
                    <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="accManagement.php" title="Account Management">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>               
                </a>
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="facilityMngmnt.html" title="Facility Management">
                    <img src="img/building-icon.png" alt="Facility Management Icon" class="w-6 h-6">
                </a>
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="roomMngmnt.html" title="Room Management">
                    <img src="img/icons8-open-door-24.png" alt="Room Management Icon" class="w-6 h-6">
                </a>
                
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="adminCalendar.html" title="Events Calendar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>                      
                </a>
            </div>
            <div class="flex flex-col items-center mt-2 border-t border-gray-700">
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="#" title="Account Settings">
                    <svg class="w-6 h-6 stroke-current"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </a>
                <a class="relative flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="#" title="Notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                      </svg>
                      
                    <span class="absolute top-0 left-0 w-2 h-2 mt-2 ml-2 bg-plv-highlight rounded-full"></span>
                </a>
            </div>
                <!-- Trigger the custom confirmation dialog -->
                <a class="flex items-center justify-center w-16 h-16 mt-auto bg-persian-blue hover:bg-plv-highlight" onclick="showCustomDialog()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                </a>
        </div>
        <!-- Component End  -->

        <!-- Content area -->
        <div class="pl-16 flex flex-col flex-1">
            <!-- Header -->
            <header class="bg-white shadow-lg">
                <!-- Header content -->
                <div class="flex items-center justify-between px-6 py-3 border-b">
                    <h2 class="text-lg font-semibold">Account Management</h2>
                    <!-- Add any header content here -->
                </div>
            </header>
            <!-- Main content area -->
            <main class="p-4 border-2 border-red-600 h-screen">
                <div class="mt-2">
                    <div class="p-4 rounded-md border-2 border-blue-600">
                        SEARCHBAR and Buttons
                    </div>
                    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                        <form method="post" action="create_user.php" id="createUserForm" class="space-y-6">
                            <div class="grid grid-cols-2 gap-x-4">
                                <div>
                                    <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input id="firstName" name="firstName" type="text" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input id="lastName" name="lastName" type="text" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
            
                            <div class="grid grid-cols-2 gap-x-4">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input id="email" name="email" type="email" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="contactNumber" class="block text-sm font-medium text-gray-700">Contact Number</label>
                                    <input id="contactNumber" name="contactNumber" type="tel" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
            
                            <div class="grid grid-cols-2 gap-x-4">
                                <div>
                                    <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                                    <select id="department" name="department" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
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
                                    <select id="role" name="role" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="Admin">Admin</option>
                                        <option value="Registrar">Registrar</option>
                                        <option value="Facility Head">Facility Head</option>
                                        <option value="Dept. Head">Dept. Head</option>
                                        <option value="Student Rep">Student Rep</option>
                                    </select>
                                </div>
                            </div>
            
                            <div class="grid grid-cols-2 gap-x-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                    <input id="password" name="password" type="password" required autocomplete="new-password" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <input id="confirmPassword" name="confirmPassword" type="password" required autocomplete="new-password" class="shadow-sm focus:ring-plv-blue focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
            
                            <div>
                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-plv-blue hover:bg-plv-highlight focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Create Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            
                <!-- User List -->
                <div class="mt-2">
                    <div class="bg-white py-2.5 shadow sm:rounded-lg sm:px-10">
                        <h2 class="text-center text-lg font-semibold text-gray-900 mb-4">User List</h2>
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-3 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Email</th>
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
                                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["role"] . "</td>";
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
            </main>          
    <!-- HTML for custom confirmation dialog -->
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
    <script src="scripts/logout.js">

    </script>
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
    </script>
</body>
</html>
<?php
// Close MySQL connection
$conn->close();
?>
