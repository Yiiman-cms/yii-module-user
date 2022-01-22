new class {
    //register app
    input_register_name;
    input_mobile_register;
    input_register_password;
    input_register_password_confirm;
    input_verify_field;
    input_referral;
    check_laws;
    btn_register;
    btn_verify;
    btn_send_mobile_button;
    check_privacy;

    container;
    container_error;
    container_mobile;
    container_verify;
    container_fields;


    loginTab;

    isFirstStep = true;

    texts;

    constructor() {
        this.init();
    }

    init_triggers() {
        this.trigger_register();
        this.trigger_verify();
        this.trigger_send_code();
    }


    init_parameters() {
        this.container = $('#register-form');
        this.container_mobile = $('#mobile-register-box');
        this.container_fields = $('#register-fields');
        this.container_verify = $('#verify-fields');
        this.container_error = $('#register-errors');

        this.btn_register = $('#register-button');
        this.btn_verify = $('#verify-button-a');
        this.btn_send_mobile_button = $('#send-mobile-button');

        this.input_register_name = $('#register-name');
        this.input_mobile_register = $('#register-mobile');
        this.input_referral = $('#referral-input');
        this.input_register_password = $('#register-password');
        this.input_register_password_confirm = $('#register-password-confirm');
        this.input_verify_field = $('#verify-code-input');

        this.check_privacy = $('#check-privacy:checked');
        this.check_laws = $('#check-laws:checked');


        this.loginTab = $('#tab-login');


        this.texts = tokappsRegisterTexts
    }

    init() {
        this.init_parameters();
        this.init_triggers();
        this.step1();
    }


    step1() {
        this.container_mobile.show();
        this.container_fields.hide();
        this.container_verify.hide();

        this.isFirstStep = true;
        this.hideError();
    }

    step2() {
        this.container_mobile.hide();
        this.container_fields.hide();
        this.container_verify.show();

        this.hideError();
    }

    step3() {
        this.container_mobile.hide();
        this.container_fields.show();
        this.container_verify.hide();

        this.isFirstStep = false;
        this.hideError();
    }

    trigger_register() {
        var app = this;
        this.btn_register.off();
        this.btn_register.click(function (e) {
            e.preventDefault();
            app.hideError();
            if (app.validate_register_fields()) {
                app.ajax_final_register();
            }
        })
    }

    trigger_send_code() {
        var app = this;
        this.btn_send_mobile_button.off();
        this.btn_send_mobile_button.click(function (e) {
            e.preventDefault();
            app.hideError();
            app.ajax_send_mobile();
        });
    }

    trigger_verify() {
        var app = this;
        this.btn_verify.off();
        this.btn_verify.click(function () {
            app.hideError();
            if (app.validate_verify_fields()) {
                app.ajax_check_mobile();
            }
        });
    }


    validate_verify_fields() {
        let field = this.input_verify_field.val();
        if (field.length < 11) {
            this.hideError();
            this.addError('لطفا کد اعتبارسنجی را به طور صحیح وارد کنید');
            return false;
        } else {
            if (field.search('_') > -1) {
                this.addError('لطفا کد اعتبارسنجی را به طور صحیح وارد کنید');
            } else {
                this.hideError();
            }
            return true;
        }
    }

    addError(message) {
        this.container_error.append(`
        <li>` + message + `</li>
        `)
    }

    showLogin() {
        this.loginTab.click();
    }

    hideError() {
        this.container_error.empty();
    }

    ajax_send_mobile() {
        var app = this;
        var mobile = app.input_mobile_register.val();
        var referral=app.input_referral.val();
        this.ajax(
            sendMobile,
            {
                mobile: mobile,
                referral:referral
            }
        );
    }

    checkResponse(response) {
        var app = this;
        switch (response.status) {
            case 'register':
                this.step2();
                break;
            case 'login':
                this.showLogin();
                break;
            case 'error':
                this.addError(response.message);
                break;
            case 'errors':
                $.each(response.messages, function (index, value) {
                    app.addError(value);
                })
                break;
            case 'verified':
                this.step3();
                break;
            case 'registered':
                window.location.href=homeUrl;
                break;
        }
    }

    ajax_check_mobile() {
        var app = this;
        this.ajax(checkMobileUrl,
            {
                code: this.input_verify_field.val(),
                mobile: this.input_mobile_register.val(),
            },
        )
    }

    ajax_final_register() {
        this.ajax(registerUrl,
            {
                name: this.input_register_name.val(),
                password: this.input_register_password_confirm.val(),
                mobile: this.input_mobile_register.val()
            });
    }

    ajax(url, data, errorCallBack = function () {
    }) {
        var app = this;
        $.ajax({
            url: url,
            method: 'post',
            data: data,
            success: function (response) {
                app.checkResponse(response);
            },
            error: errorCallBack
        });

    }

    validate_register_fields() {
        let hasError = false;

        var name = this.input_register_name.val();
        var password = this.input_register_password.val();
        var password_confirm = this.input_register_password_confirm.val();
        var privacy = this.check_privacy.length;
        var laws = this.check_laws.length;

        this.init_parameters();
        this.hideError();
        if (name.length < 3) {
            this.addError(this.texts.no_name_entered);
            hasError = true;
        }
        if (password.length === 0) {
            this.addError(this.texts.password_not_entered);
            hasError = true;
            return false;
        }

        if (password.length < 8) {
            this.addError(this.texts.password_is_under_7_character);
            hasError = true;
            return false;
        }

        if (password_confirm.length === 0) {
            this.addError(this.texts.password_confirm_not_entered);
            hasError = true;
            return false;
        }

        if (password_confirm !== password) {
            this.addError(this.texts.password_confirm_not_entered);
            hasError = true;
            return false;
        }

        if (laws=== 0) {
            this.addError(this.texts.laws_not_checked);
            hasError = true;
        }

        if (privacy === 0) {
            this.addError(this.texts.privacy_not_checked);
            hasError = true;
        }

        if (hasError) {
            return false;
        }else {
            return true;
        }
    }
}
