document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendarioCitas');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth'
  });
  calendar.render();
});
