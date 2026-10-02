const formRegister = document.getElementById("form-Register");

if (formRegister) {
  formRegister.addEventListener("submit", function (event) {
    event.preventDefault();

    const username = document.getElementById("IdUsername").value;
    const email = document.getElementById("IdEmail").value;
    const password = document.getElementById("IPassword").value;
    const confirmPassword = document.getElementById("ConfirmIdPassword").value;
    const genderMale = document.querySelector(
      'input[name="Idgender"][value="Male"]'
    );
    const genderFemale = document.querySelector(
      'input[name="Idgender"][value="Female"]'
    );
    const dob = document.getElementById("IdDOB").value;

    let isValid = true;
    let errorMessage = "";

    if (username.length < 5 || username.length > 15) {
      errorMessage += "Username must be between 5 and 15 characters.\n";
      isValid = false;
    }

    if (!email.includes("@") || !email.endsWith(".com")) {
      errorMessage += 'Email must contain "@" and end with ".com".\n';
      isValid = false;
    }

    if (password.length < 6 || password.length > 12) {
      errorMessage += "Password must be between 6 and 12 characters.\n";
      isValid = false;
    }

    if (password !== confirmPassword) {
      errorMessage += "Password and Re Enter Password must match.\n";
      isValid = false;
    }

    if (!genderMale.checked && !genderFemale.checked) {
      errorMessage += "Gender must be selected.\n";
      isValid = false;
    }

    if (!dob) {
      errorMessage += "Date of Birth must be filled.\n";
      isValid = false;
    } else {
      const today = new Date();
      const birthDate = new Date(dob);
      let age = today.getFullYear() - birthDate.getFullYear();
      const m = today.getMonth() - birthDate.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }
      if (age < 18) {
        errorMessage += "You must be at least 18 years old to register.\n";
        isValid = false;
      }
    }

    if (isValid) {
      alert("Registration successful! Submitting data to server.");
      formRegister.submit();
    } else {
      alert("Registration Failed:\n" + errorMessage);
    }
  });
}

const formLogin = document.getElementById("form-login");

if (formLogin) {
  formLogin.addEventListener("submit", function (event) {
    event.preventDefault();

    const email = document.getElementById("IdEmail").value;
    const password = document.getElementById("IdPassword").value;

    let isValid = true;
    let errorMessage = "";

    if (!email.includes("@") || !email.endsWith(".com")) {
      errorMessage += 'Email must contain "@" and end with ".com".\n';
      isValid = false;
    }

    if (password.length < 6 || password.length > 12) {
      errorMessage += "Password must be between 6 and 12 characters.\n";
      isValid = false;
    }

    if (isValid) {
      alert("Login successful! Submitting data to server.");
      formLogin.submit();
    } else {
      alert("Login Failed:\n" + errorMessage);
    }
  });
}
