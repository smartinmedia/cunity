$(document).ready(function () {
    $("#contactForm").bootstrapValidator({
        feedbackIcons: {
            valid: 'fa fa-check',
            invalid: 'fa fa-times',
            validating: 'fa fa-refresh'
        },
        message: "This field cannot be blank!",
        submitButtons: "button[type='submit'],input[type='submit']",
        fields: {
            firstname: {
                validators: {
                    stringLength: {
                        min: 3,
                        message: "Your firstname is too short (min. 3 chars)"
                    }
                }
            },
            lastname: {
                validators: {
                    stringLength: {
                        min: 3,
                        message: "Your lastname is too short (min. 3 chars)"
                    }
                }
            },
            email: {
                validators: {
                    emailAddress: {
                        message: "This is not a valid email-address"
                    }
                }
            }
        }
    });
});