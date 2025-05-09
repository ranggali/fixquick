const forms = document.querySelector(".forms"),
      pwShowHide = document.querySelectorAll(".eye-icon"),
      links = document.querySelectorAll(".link");

pwShowHide.forEach(eyeIcon => {
    eyeIcon.addEventListener("click", () => {
        let pwFields = eyeIcon.parentElement.parentElement.querySelectorAll(".password");
        
        pwFields.forEach(password => {
            if(password.type === "password"){
                password.type = "text";
                eyeIcon.classList.replace("bx-hide", "bx-show");
                return;
            }
            password.type = "password";
            eyeIcon.classList.replace("bx-show", "bx-hide");
        })
        
    })
})      

links.forEach(link => {
    link.addEventListener("click", e => {
       e.preventDefault(); //preventing form submit
       forms.classList.toggle("show-signup");
    })
})

// arrow
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('role');
    const arrow = select.parentElement.querySelector('.arrow-icon');

    // Fungsi untuk memutar panah saat select fokus
    select.addEventListener('focus', function() {
        arrow.classList.add('arrow-up');
    });

    // Fungsi untuk mengembalikan panah saat select kehilangan fokus
    select.addEventListener('blur', function() {
        arrow.classList.remove('arrow-up');
    });

    // Untuk menangani perubahan pilihan tanpa kehilangan fokus
    select.addEventListener('change', function() {
        arrow.classList.remove('arrow-up');
    });
});