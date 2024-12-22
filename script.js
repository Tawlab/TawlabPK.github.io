// รอให้ DOM โหลดเสร็จก่อน
document.addEventListener('DOMContentLoaded', function() {
    // อ้างอิง element ต่างๆ
    const goRegisterButton = document.getElementById('go-register');
    const goLoginButton = document.getElementById('go-login');
    const loginSection = document.getElementById('login');
    const registerSection = document.getElementById('register');
    const teacherRadio = document.getElementById('register-teacher');
    const parentRadio = document.getElementById('register-parent');
    const teacherCodeSection = document.getElementById('teacher-code-section');

    // ฟังก์ชันสลับการแสดงผล
    function toggleSection(showLogin) {
        loginSection.style.display = showLogin ? 'block' : 'none';
        registerSection.style.display = showLogin ? 'none' : 'block';
    }

    // ฟังก์ชันสำหรับจัดการการแสดงช่องกรอกรหัสประจำตัวครู
})