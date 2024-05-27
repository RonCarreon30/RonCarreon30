    <?php
    // Include the database configuration file
    include 'config.php';

    // Fetch courses
    $faculty = $conn->query("SELECT id, faculty_name, department FROM faculty");
    $subjects = $conn->query("SELECT id, subject_code FROM subjects");
    
    ?>
<div class="mt-2 p-6 bg-white shadow-md rounded">
    <form method="post" action="save_faculty_subject.php">
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3">
                <label for="faculty_id" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Faculty:</label>
                <select id="faculty_id" name="faculty_id" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                    <option value="" disabled selected>Select a faculty member</option>
                    <?php while ($row = $faculty->fetch_assoc()): ?>
                        <option value="<?php echo $row['id'];?>"><?php echo $row['faculty_name'] . ' - ' . $row['department']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label for="subject_id" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Subject:</label>
                <select id="subject_id" name="subject_id" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                    <option value="" disabled selected>Select a subject</option>
                        <?php while ($row = $subjects->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['subject_code']; ?></option>
                        <?php endwhile; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Assign</button>
    </form>
</div>

    <!-- Assigned Subjects -->
    <div class="mt-2 overflow-y-auto max-h-[calc(100vh-200px)]">
        <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">Something went wrong.</span>
        </div>

        <div class="bg-white py-2.5 shadow sm:rounded-lg sm:px-10 ">
            <h2 class="text-center text-lg font-semibold text-gray-900 mb-4">Subjects Assigned to Faculty</h2>
            <table class="min-w-full ">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Faculty Name</th>
                        <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Subjects</th>
                        <th class="px-8 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="assignedSubjectsList" class="bg-white divide-y divide-gray-200">

                </tbody>
            </table>
        </div>