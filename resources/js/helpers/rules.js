export const required = (v, fieldName = "Field") => {
    return !!v || `${fieldName} is required`;
};

export const emailValid = (v, fieldName = "Email") => {
    return /.+@.+\..+/.test(v) || `${fieldName} must be valid`;
};

export const notZero = (v, fieldname = "Field") => {
    return v !== 0 || `${fieldname} should be atleast one`;
};
