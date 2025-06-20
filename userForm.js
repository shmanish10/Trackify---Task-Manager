//navbar script--
function toggleDropdown() {
    document.getElementById("dropdownMenu").classList.toggle("show");
}

document.addEventListener("click", function(event) {
    const dropdown = document.getElementById("dropdownMenu");
    const userIcon = document.querySelector(".user-icon");

    // If click is outside both the dropdown menu and the user-icon toggle
    if (!dropdown.contains(event.target) && !userIcon.contains(event.target)) {
        dropdown.classList.remove("show");
    }
});



//update user form script--
function openUpdateModal(button) {
  document.getElementById('update_id').value = button.dataset.id;
  document.getElementById('update_task_name').value = button.dataset.task;
  document.getElementById('update_task_details').value = button.dataset.details;
  document.getElementById('update_due_date').value = button.dataset.date;
  document.getElementById('update_priority').value = button.dataset.priority;

  document.getElementById('updateModal').style.display = 'block';
}

function closeModal() {
  document.getElementById('updateModal').style.display = 'none';
}

// to close modal when clicking outside
window.onclick = function(event) {
  if (event.target == document.getElementById('updateModal')) {
    closeModal();
  }
}