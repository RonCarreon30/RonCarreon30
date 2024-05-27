<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="mt-2 p-6 bg-white shadow-md rounded">
    <?php
    // Include the database configuration file
    include 'config.php';

    // Fetch courses
    $courses = $conn->query("SELECT id, course_name FROM courses");

    // Fetch year levels and sections combined
    $yearSections = $conn->query("SELECT id, CONCAT(year_level, '-', section) AS year_section FROM year_section");
    ?>

    <form method="post" action="save_course_yr_sec.php">
        <div class="w-full px-3 mb-6">
            <label for="course" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Course:</label>
            <select id="course" name="course" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                <option value="" disabled selected>Select a course</option>
                <?php while ($row = $courses->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['course_name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="w-full px-3 mb-6">
            <label for="yearSection" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Year Level & Section:</label>
            <select id="yearSection" name="yearSection" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                <option value="" disabled selected>Select a year level and section</option>
                <?php while ($row = $yearSections->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['year_section']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
    </form>
</div>

<!-- Course Year & Section List -->
<div class="mt-2 overflow-y-auto max-h-[calc(100vh-200px)]">
    <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">Something went wrong.</span>
    </div>

    <div class="bg-white py-2.5 shadow sm:rounded-lg sm:px-10">
        <h2 class="text-center text-lg font-semibold text-gray-900 mb-4">Course Year & Section List</h2>
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Course Name</th>
                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Year & Section</th>
                    <th class="px-8 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="courseYearSecList" class="bg-white divide-y divide-gray-200">
                <?php
                // Database connection
                include 'config.php';

                // Query to fetch course year & section data
                $sql = "SELECT c.course_name, CONCAT(ys.year_level, '-', ys.section) AS year_section, cys.id
                        FROM course_year_section cys
                        JOIN courses c ON cys.course_id = c.id
                        JOIN year_section ys ON cys.year_section_id = ys.id";
                $result = $conn->query($sql);

                // Check if there are any records
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["course_name"] . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["year_section"] . "</td>";
                        echo "<td class='px-8 py-4 whitespace-nowrap'>
                                <div class='flex items-center'>
                                    <button type='button' class='inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2' onclick='editCourseYearSec(" . $row["id"] . ")'>
                                        Edit
                                    </button>
                                    <button type='button' class='ml-2 inline-flex justify-center rounded-md border border-red-500 shadow-sm px-4 py-2 bg-red-500 text-sm font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2' onclick='deleteCourseYearSec(" . $row["id"] . ")'>
                                        Delete
                                    </button>
                                </div>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    // Output a message if no data found
                    echo "<tr><td colspan='3'>No data found</td></tr>";
                }

                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

