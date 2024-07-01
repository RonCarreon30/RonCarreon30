<?php

// Create connection
$conn = new mysqli($servername, $username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the saved academic years
$sql = "SELECT id, academic_year, start_date, end_date, status FROM academic_years";
$result = $conn->query($sql);
?>
                        <div class="mt-2 p-6 bg-white shadow-md rounded">
                            <form id="academicYearForm" method="post" action="save_AY.php">
                                <div class="flex flex-wrap -mx-3 mb-6">
                                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                        <label for="academicYear" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Academic Year:</label>
                                        <input type="text" id="academicYear" name="academicYear" placeholder="YYYY-YYYY" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                                    </div>
                                    <div class="w-full md:w-1/2 px-3">
                                        <label for="startDate" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Start Date:</label>
                                        <input type="date" id="startDate" name="startDate" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                                    </div>
                                    <div class="w-full md:w-1/2 px-3">
                                        <label for="endDate" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">End Date:</label>
                                        <input type="date" id="endDate" name="endDate" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                                    </div>
                                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                        <label for="acadYearStatus" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Status:</label>
                                        <select id="acadYearStatus" name="acadYearStatus" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                                            <option value="">Select status</option>
                                            <option value="1">Incoming</option>
                                            <option value="2">Current</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
                            </form>    
                        </div>

<!-- Academic Year List -->
    <div class="mt-2 overflow-y-auto max-h-[calc(100vh-200px)]">
        <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">Something went wrong.</span>
        </div>

        <div class="bg-white py-2.5 shadow sm:rounded-lg sm:px-10 ">
            <h2 class="text-center text-lg font-semibold text-gray-900 mb-4">Academic Year List</h2>
            <table class="min-w-full ">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                        <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Starting Date</th>
                        <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                        <th class="px-8 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-8 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="acadYearList" class="bg-white divide-y divide-gray-200">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["academic_year"] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["start_date"] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["end_date"] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["status"] . "</td>";
                            echo "<td class='px-8 py-4 whitespace-nowrap'>
                                    <div class='flex items-center'>
                                        <button type='button' class='inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2' onclick='editAcademicYear(" . $row["id"] . ")'>
                                            Edit
                                        </button>
                                        <button type='button' class='ml-2 inline-flex justify-center rounded-md border border-red-500 shadow-sm px-4 py-2 bg-red-500 text-sm font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2' onclick='deleteAcademicYear(" . $row["id"] . ")'>
                                            Delete
                                        </button>
                                    </div>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No data found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>