function validate_checkout() {
    var error = true;
    document.check_out.custName.background = "Red";
    if (document.check_out.custName.value == "") {
        error_type = 1;
        document.check_out.custName.focus();
        error = false;
    }
    if (document.check_out.address.value == "") {
        error_type = 1;
        document.check_out.address.focus();
        error = false;
    }
    if (document.check_out.suburb.value == "") {
        error_type = 1;
        document.check_out.suburb.focus();
        error = false;
    }
    if (document.check_out.state.value == "") {
        error_type = 1;
        document.check_out.state.focus();
        error = false;
    }
    if (document.check_out.country.value == "") {
        error_type = 1;
        document.check_out.country.focus();
        error = false;
    }
    if (document.check_out.phone.value == "") {
        error_type = 1;
        document.check_out.phone.focus();
        error = false;
    }
    if (document.check_out.email.value == "" || !validateEmail(document.check_out.email.value)) {
        error_type = 1;
        document.check_out.email.focus();
        error = false;
    }

    if (error == false) {
        alert("The required fields are not filled in or contain invalid data.");
        return false;
    } else {
        return true;
    }
}

function validateEmail(email) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}