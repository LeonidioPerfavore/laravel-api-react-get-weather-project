const validateEmail = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
};

const validatePassword = (password) => password.length >= 8;
const validateName = (name) => name.length >= 2;

const registrationValidationErrors = (name, email, password) => {
    if (!name) { return 'Name required'; }
    if (!validateName) { return 'Name min length 2'; }
    if (!email) { return 'Email required'; }
    if (!validateEmail(email)) { return 'Email not valid'; }
    if (!password) { return 'Password required'; }
    if (!validatePassword(password)) { return 'Password min length 8'; }
    return false;
}

const loginValidationErrors = (email, password) => {
    if (!email) { return 'Email required'; }
    if (!validateEmail(email)) { return 'Email not valid'; }
    if (!password) { return 'Password required'; }
    if (!validatePassword(password)) { return 'Password min length 8'; }
    return false;
}

export {validateEmail, validatePassword, registrationValidationErrors, loginValidationErrors};
