<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['saveAccount'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        // Save account settings logic here
        echo '<script>alert("Account settings saved successfully!");</script>';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    if (isset($_POST['saveAppearance'])) {
        $theme = $_POST['theme'];
        // Save appearance settings logic here
        echo '<script>alert("Appearance settings saved successfully!");</script>';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    if (isset($_POST['savePrivacy'])) {
        $password = $_POST['password'];
        $twoFactor = isset($_POST['twoFactor']) ? 'Yes' : 'No';
        // Save privacy settings logic here
        echo '<script>alert("Privacy settings saved successfully!");</script>';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    if (isset($_POST['saveSupport'])) {
        $supportEmail = $_POST['supportEmail'];
        // Save support settings logic here
        echo '<script>alert("Support request sent successfully!");</script>';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function toggleAccordion(section) {
            var content = document.getElementById(section);
            content.classList.toggle("hidden");
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar-container">
            <?php include 'sidebar.php'; ?>
        </div>
        <!-- Content area -->
        <div class="flex flex-col flex-1">
            <!-- Header -->
            <header class="bg-white shadow-lg">
                <div class="flex items-center justify-between px-6 py-3 border-b">
                    <h2 class="text-lg font-semibold">Settings</h2>
                </div>
            </header>
            <!-- Main content area -->
            <main class="flex-1 p-4 overflow-y-auto">
                <div class="container mx-auto px-4 py-8">
                    <div class="bg-white shadow-md rounded-lg mb-8">
                        <!-- Accordion Section for Account Settings -->
                        <div class="border-b">
                            <button class="w-full text-left px-6 py-4 font-semibold text-lg focus:outline-none" onclick="toggleAccordion('accountSection')">
                                Account Settings
                            </button>
                            <div id="accountSection" class="px-6 py-4 hidden">
                                <form method="post" action="">
                                    <div class="mb-4">
                                        <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
                                        <input type="text" id="username" name="username" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                                    </div>
                                    <div class="mb-4">
                                        <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                                        <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                    </div>
                                    <button type="submit" name="saveAccount" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Save Account Changes</button>
                                </form>
                            </div>
                        </div>
                        <!-- Accordion Section for Appearance Settings -->
                        <div class="border-b">
                            <button class="w-full text-left px-6 py-4 font-semibold text-lg focus:outline-none" onclick="toggleAccordion('appearanceSection')">
                                Appearance Settings
                            </button>
                            <div id="appearanceSection" class="px-6 py-4 hidden">
                                <form method="post" action="">
                                    <div class="mb-4">
                                        <label for="theme" class="block text-gray-700 font-medium mb-2">Theme</label>
                                        <select id="theme" name="theme" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                                            <option value="light">Light</option>
                                            <option value="dark">Dark</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="saveAppearance" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Save Appearance Settings</button>
                                </form>
                            </div>
                        </div>
                        <!-- Accordion Section for Privacy and Security -->
                        <div class="border-b">
                            <button class="w-full text-left px-6 py-4 font-semibold text-lg focus:outline-none" onclick="toggleAccordion('privacySection')">
                                Privacy and Security
                            </button>
                            <div id="privacySection" class="px-6 py-4 hidden">
                                <form method="post" action="">
                                    <div class="mb-4">
                                        <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                                        <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                                    </div>
                                    <div class="mb-4">
                                        <label for="twoFactor" class="block text-gray-700 font-medium mb-2">Two-Factor Authentication</label>
                                        <input type="checkbox" id="twoFactor" name="twoFactor" class="form-checkbox h-6 w-6 text-blue-500">
                                    </div>
                                    <button type="submit" name="savePrivacy" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Save Privacy Settings</button>
                                </form>
                            </div>
                        </div>
                        <!-- Accordion Section for Help and Support -->
                        <div class="border-b">
                            <button class="w-full text-left px-6 py-4 font-semibold text-lg focus:outline-none" onclick="toggleAccordion('helpSection')">
                                Help and Support
                            </button>
                            <div id="helpSection" class="px-6 py-4 hidden">
                                <form method="post" action="">
                                    <div class="mb-4">
                                        <label for="supportEmail" class="block text-gray-700 font-medium mb-2">Support Email</label>
                                        <input type="email" id="supportEmail" name="supportEmail" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                                    </div>
                                    <button type="submit" name="saveSupport" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Contact Support</button>
                                </form>
                            </div>
                        </div>
                        <!-- Accordion Section for About -->
                        <div class="border-b">
                            <button class="w-full text-left px-6 py-4 font-semibold text-lg focus:outline-none" onclick="toggleAccordion('aboutSection')">
                                About
                            </button>
                            <div id="aboutSection" class="px-6 py-4 hidden">
                                <p class="text-gray-700">This application is designed to help you manage your settings easily and efficiently. Version 1.0.0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <div id="footer-container">
                <?php include 'footer.php' ?>
            </div>
        </div>
    </div>
            <!-- Logout confirmation modal -->
        <div id="custom-dialog" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md flex flex-col items-center">
                <img class="w-36 mb-4" src="img\undraw_warning_re_eoyh.svg" alt="">
                <p class="text-lg text-slate-700 font-semibold mb-4">Are you sure you want to logout?</p>
                <div class="flex justify-center mt-5">
                    <button onclick="cancelLogout()" class="mr-4 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Cancel</button>
                    <button onclick="confirmLogout()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-500">Logout</button>
                </div>
            </div>
        </div> 
        <script src="scripts/logout.js"></script>
</body>
</html>
