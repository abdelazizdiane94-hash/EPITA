// main.js

document.addEventListener("DOMContentLoaded", function() {
    // Initialize project modals
    const projectButtons = document.querySelectorAll('.project-button');
    projectButtons.forEach(button => {
        button.addEventListener('click', function() {
            const projectId = this.getAttribute('data-project-id');
            openModal(projectId);
        });
    });

    // Function to open project modals
    function openModal(projectId) {
        const modal = document.getElementById(`modal-${projectId}`);
        modal.style.display = "block";
    }

    // Close modals when clicking outside of them
    window.onclick = function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });
    };

    // Filter projects based on selected skills
    const filterButtons = document.querySelectorAll('.filter-button');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const skill = this.getAttribute('data-skill');
            filterProjects(skill);
        });
    });

    function filterProjects(skill) {
        const projects = document.querySelectorAll('.project-card');
        projects.forEach(project => {
            if (project.classList.contains(skill) || skill === 'all') {
                project.style.display = "block";
            } else {
                project.style.display = "none";
            }
        });
    }
});