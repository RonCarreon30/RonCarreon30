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
    <form method="post" action="save_subject.php" id="subjectForm">
        <div id="subjectFields" class="max-h-80 overflow-y-auto">
            <div class="w-full px-3 mb-6">
                <label for="subjectName" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Subject Name:</label>
                <input type="text" name="subjectName[]" placeholder="Enter subject name" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
            </div>
            <div class="w-full px-3 mb-6">
                <label for="subjectCode" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Subject Code:</label>
                <input type="text" name="subjectCode[]" placeholder="Enter subject code" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
            </div>
        </div>
    <button type="button" onclick="addSubjectField()" class="mb-4 bg-gray-300 hover:bg-blue-700 text-white rounded-full w-6 h-6 flex items-center justify-center">
    <i class="fas fa-plus"></i>
</button>
        <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
    </form>
</div>

<!-- Subject List -->
<div class="mt-2 overflow-y-auto max-h-[calc(100vh-200px)]">
    <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">Something went wrong.</span>
    </div>

    <div class="bg-white py-2.5 shadow sm:rounded-lg sm:px-10 ">
        <h2 class="text-center text-lg font-semibold text-gray-900 mb-4">Subjects List</h2>
        <table class="min-w-full ">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Subject Name</th>
                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Subject Code</th>
                    <th class="px-8 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="subjectList" class="bg-white divide-y divide-gray-200">
                <?php
                // Fetch subject data from the database
                $sql = "SELECT id, subject_name, subject_code FROM subjects";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["subject_name"] . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["subject_code"] . "</td>";
                        echo "<td class='px-8 py-4 whitespace-nowrap'>
                                <div class='flex items-center'>
                                    <button type='button' class='inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2' onclick='editSubject(" . $row["id"] . ")'>
                                        Edit
                                    </button>
                                    <button type='button' class='ml-2 inline-flex justify-center rounded-md border border-red-500 shadow-sm px-4 py-2 bg-red-500 text-sm font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2' onclick='deleteSubject(" . $row["id"] . ")'>
                                        Delete
                                    </button>
                                </div>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No data found</td></tr>";
                }
                ?>   
            </tbody>
        </table>
    </div>
</div>

    <script>
        function addSubjectField() {
            const subjectFields = document.getElementById('subjectFields');
            const newSubjectField = `
                <hr class="my-4 border-gray-300">
                <div class="subject-group">
                    <div class="w-full px-3 mb-6">
                        <label for="subjectName" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Subject Name:</label>
                        <input type="text" name="subjectName[]" placeholder="Enter subject name" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                    </div>
                    <div class="w-full px-3 mb-6">
                        <label for="subjectCode" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Subject Code:</label>
                        <input type="text" name="subjectCode[]" placeholder="Enter subject code" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                    </div>
                </div>

            `;
            subjectFields.insertAdjacentHTML('beforeend', newSubjectField);
        }
    </script>
