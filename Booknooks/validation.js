/*
B20250035 Alya Sofiya binti Haji Rosaimi
B20250113 Nur Aini Jamilah Mohamad Johnny
B20250176 Ahmad Ridhuan bin Mohamad Johnny
*/

document.addEventListener("DOMContentLoaded", function () {

    // Validate Registration
    const registerform = document.querySelector("form[action='register.php']");
    if (registerform) {
        registerform.addEventListener("submit", function (e){

            let errors = [];
            const username = document.getElementById("username")?.value.trim();
            const email = document.getElementById("email")?.value.trim();
            const password = document.getElementById("password")?.value;
            const confirmPassword = document.getElementById("confirm_password")?.value;
            const emailReg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!username || username.length < 5){
                errors.push("Username must be at least 5 characters.");
            }

            if (!email || !emailReg.test(email)){
                errors.push("Please enter a valid email address.");
            }

            if (!password || password.length < 8){
                errors.push("Password must be at least 8 characters.");
            }

            if (confirmPassword !== undefined && password !== confirmPassword){
                errors.push("Passwords do not match");
            }

            if (errors.length > 0){
                e.preventDefault();
                alert("Registration Validation Error:\n- " + errors.join("\n- "));
            }
        });
    }

    // Review and Anti Spam Validation
    const reviewForm = document.querySelector("form[action='add_review.php']");

    if (reviewForm) {
        reviewForm.addEventListener("submit", function (e){
            const reviewText = document.getElementById("review_text")?.value.trim();
            const honeypot = document.querySelector("input[name='user_website']")?.value;

            if (honeypot && honeypot !== "") {
                e.preventDefault();
                alert("Automated submission detected.");
                return;
            }

            if (!reviewText || reviewText.length < 5) {
                e.preventDefault();
                alert("Review content must be at least 5 characters long.");
            }
        });
    }

    // Catalog Item Validation (add + edit book)
    const bookForm = document.querySelector("form[action='add_book.php'], form[action^='edit_book.php']");

    if (bookForm) {
        bookForm.addEventListener("submit", function (e){

            let errors = [];
            const title = document.getElementById("title")?.value.trim();
            const author = document.getElementById("author")?.value.trim();
            const year = parseInt(document.getElementById("published_year")?.value, 10);
            const currentYear = new Date().getFullYear();

            if (!title)
                errors.push("Book title is required.");

            if (!author)
                errors.push("Author name is required.");

            if (isNaN(year) || year < 1000 || year > currentYear) {
                errors.push(`Publication year must be valid (1000-${currentYear}).`);
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert("Book Entry Error:\n- " + errors.join("\n- "));
            }
        });
    }
});
