    <div class="mt-2 p-6 bg-white shadow-md rounded">
        <!-- Check for success message and display it -->
        <?php if(isset($_GET['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline"><?php echo $_GET['success']; ?></span>
            </div>
        <?php endif; ?>

        <!-- Check for error message and display it -->
        <?php if(isset($_GET['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"><?php echo $_GET['error']; ?></span>
            </div>
        <?php endif; ?>

        <form method="post" action="save_course.php">
            <div class="w-full px-3 mb-6">
                <label for="courseName" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Course Name:</label>
                <input type="text" id="courseName" name="courseName" placeholder="Enter course name" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
            </div>
            <div class="w-full px-3 mb-6">
                <label for="courseCode" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Course Code:</label>
                <input type="text" id="courseCode" name="courseCode" placeholder="Enter course code" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
            </div>
            <div class="w-full px-3 mb-6">
                <label for="courseCollege" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">College:</label>
                <input type="text" id="courseCollege" name="courseCollege" placeholder="Enter college" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
            </div>            
            <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
        </form>
    </div>

        <!--Course List -->
<div class="mt-2 overflow-y-auto max-h-[calc(100vh-200px)]">
    <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">Something went wrong.</span>
    </div>

    <div class="bg-white py-2.5 shadow sm:rounded-lg sm:px-10 ">
        <h2 class="text-center text-lg font-semibold text-gray-900 mb-4">Course List</h2>
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Course Name</th>
                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Course Code</th>
                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">College</th>
                    <th class="px-8 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Assuming $conn is your database connection object
                $sql = "SELECT * FROM courses";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["course_name"] . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["course_code"] . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["course_college"] . "</td>";
                        echo "<td class='px-8 py-4 whitespace-nowrap'>
                                <div class='flex items-center'>
                                    <button type='button' class='inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2' onclick='editCourse(" . $row["id"] . ")'>
                                        Edit
                                    </button>
                                    <button type='button' class='ml-2 inline-flex justify-center rounded-md border border-red-500 shadow-sm px-4 py-2 bg-red-500 text-sm font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2' onclick='deleteCourse(" . $row["id"] . ")'>
                                        Delete
                                    </button>
                                </div>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No data found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

