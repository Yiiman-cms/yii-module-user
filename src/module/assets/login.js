new class {
    container_error;
    btn_login;
    input_mobile;
    input_password;

    texts;

    constructor() {
        this.init();
    }

    init() {
        this.init_parameters();
        this.init_triggers();
    }

    init_triggers() {
        this.trigger_login_btn();
    }

    trigger_login_btn() {
        var app = this;
        this.btn_login.off();
        this.btn_login.click(function (e) {
            if (app.validate_login()) {
                app.ajax_login();
            }
        });
    }

    init_parameters() {
        this.container_error = $('#login-errors');

        this.btn_login = $('#login-button');
        this.input_mobile = $('#login-mobile');
        this.input_password = $('#login-password');

        this.texts = tokappsLoginTexts;
    }


    validate_login() {
        this.init_parameters();
        this.init_triggers();
        var hasError = false;
        var mobile = this.input_mobile.val();
        var password = this.input_password.val();

        this.hideError();


        if (mobile.length === 0) {
            this.addError(this.texts.EnterMobile);
            hasError = true;
            return false;
        }

        if (mobile.search('_') > -1) {
            this.addError(this.texts.InvalidMobile);
            hasError = true;
            return false;
        }

        if (password.length === 0) {
            this.addError(this.texts.EnterPassword);
            hasError = true;
        }

        if (password.length < 7) {
            this.addError(this.texts.PasswordUnder7Character);
            hasError = true;
        }

        if (hasError) {
            return false;
        } else {
            return true;
        }

    }


    ajax_login() {
        this.ajax(
            loginUrl,
            {
                mobile: this.input_mobile.val(),
                password: this.input_password.val()
            }
        );
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


    hideError() {
        this.container_error.empty();
    }

    addError(message) {
        this.container_error.append(`
        <li>` + message + `</li>
        `)
    }


    checkResponse(response) {
        var app = this;
        switch (response.status) {
            case 'error':
                this.addError(response.message);
                break;
            case 'errors':
                $.each(response.messages, function (index, value) {
                    app.addError(value);
                })
                break;
            case 'logined':
                location.reload();
        }
    }

}
