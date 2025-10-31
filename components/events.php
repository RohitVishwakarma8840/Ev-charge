<!-- <?php
include("db.php");

?> -->

<!DOCTYPE html>
<html>
<head>
  <title>All Events</title>
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 8px 12px;
      border: 1px solid #ccc;
      text-align: left;
    }
    .badge {
      padding: 4px 8px;
      border-radius: 5px;
      color: #fff;
      font-size: 12px;
    }
    .meet { background: #007bff; }
    .humla { background: #28a745; }
    .workshop { background: #ffc107; color: #000; }
    .archive { background: #ff5722; }
  </style>
</head>
<body>

<h2>Upcoming Events</h2>
<table>
  <thead>
    <tr>
      <th>Date</th>
      <th>Event Type</th>
      <th>Event</th>
      <th>Location</th>
      <th>Sessions</th>
      <th>Archieve</th>
    </tr>
  </thead>
  <tbody id="eventTableBody">
    <!-- Data will load here -->
  </tbody>
</table>

<script>
// ✅ Fetch data from API and render in table
fetch("../api/getEvents.php")
  .then(res => res.json())
  .then(data => {
    const tbody = document.getElementById('eventTableBody');
    if (data.length === 0) {
      tbody.innerHTML = '<tr><td colspan="5">No events found</td></tr>';
      return;
    }

    data.forEach(event => {
      const row = `
        <tr>
          <td>${event.event_date}</td>
          <td><span class="badge ${event.event_type.toLowerCase()}">${event.event_type}</span></td>
          <td>${event.title}</td>
          <td>${event.location}</td>
          <td><span class="badge archive">Sessions: ${event.sessions}</span></td>
          <td>${event.archive_status}</td>
        </tr>
      `;
      tbody.innerHTML += row;
    });
  })
  .catch(err => {
    console.error('Error fetching events:', err);
  });
</script>

</body>
</html>

<!-- <?php $conn->close(); ?> -->
