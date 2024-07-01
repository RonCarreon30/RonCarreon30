                        <div class="mt-2 p-6 bg-white shadow-md rounded">
                            <form id="semesterForm" method="post" action="save_semester.php">
                                <div class="flex flex-wrap -mx-3 mb-6">
                                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                        <label for="semesterName" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Semester Name:</label>
                                        <input type="text" id="semesterName" name="semesterName" placeholder="Enter semester name" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
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
                                        <label for="semStatus" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Status:</label>
                                        <select id="semStatus" name="semStatus" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                                            <option value="">Select status</option>
                                            <option value="1">Incoming</option>
                                            <option value="2">Current</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
                            </form>
                        </div>

                        <!--Semester List -->
                        <div class="mt-2 overflow-y-auto max-h-[calc(100vh-200px)]">
    <div class="bg-white py-2.5 shadow sm:rounded-lg sm:px-10">
        <h2 class="text-center text-lg font-semibold text-gray-900 mb-4">Semester List</h2>
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Starting Date</th>
                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                    <th class="px-8 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-8 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="semesterList" class="bg-white divide-y divide-gray-200">
                <?php
                // Fetch semester data from the database
                $sql = "SELECT id, semester_name, start_date, end_date, status FROM semesters";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["semester_name"] . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["start_date"] . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["end_date"] . "</td>";
                        echo "<td class='px-8 py-4 whitespace-nowrap'>" . $row["status"] . "</td>";
                        echo "<td class='px-8 py-4 whitespace-nowrap'>
                                <div class='flex items-center'>
                                    <button type='button' class='inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2' onclick='editSemester(" . $row["id"] . ")'>
                                        Edit
                                    </button>
                                    <button type='button' class='ml-2 inline-flex justify-center rounded-md border border-red-500 shadow-sm px-4 py-2 bg-red-500 text-sm font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2' onclick='deleteSemester(" . $row["id"] . ")'>
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



