<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLV: RESERVA</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/tailwind-theme@5.10.0/main.min.css"></script> <!-- Load Tailwind theme -->
    <script>

      document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar')
        const calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          themeSystem: 'tailwind' // Use Tailwind theme
        })
        calendar.render()
      })

    </script>
</head>
<body>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <!-- Component Start -->
        <div class="flex flex-col items-center w-16 h-full overflow-hidden text-blue-200 bg-plv-blue rounded-r-lg">
            <!-- Sidebar content -->
        </div>
        <!-- Content area -->
        <div class="flex flex-col flex-1">
            <!-- Header -->
            <header class="bg-white shadow-lg">
                <!-- Header content -->
            </header>
            <!-- Main content area -->
            <main class="flex-1 p-4">
                <!-- Calendar -->
                <div class="flex h-full w-1/3 space-y-4">
                    <div id='calendar' class="w-full h-full bg-white border border-gray-200 rounded-lg shadow-lg"></div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
