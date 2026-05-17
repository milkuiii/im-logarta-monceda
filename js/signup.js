function toggleRoleFields(role) {
    const studentDiv = document.getElementById('student-fields');
    const facultyDiv = document.getElementById('faculty-fields');
    const studentInputs = studentDiv.querySelectorAll('input');
    const facultyInputs = facultyDiv.querySelectorAll('input');

    if (role === 'student') {
        studentDiv.style.display = 'block';
        facultyDiv.style.display = 'none';
        
        studentInputs.forEach(input => input.required = true);
        facultyInputs.forEach(input => { input.required = false; input.value = ''; });
    } else {
        studentDiv.style.display = 'none';
        facultyDiv.style.display = 'block';
        
        studentInputs.forEach(input => { input.required = false; input.value = ''; });
        facultyInputs.forEach(input => input.required = true);
    }
}