new class {
    //reset password APP
    elements = {
        ul_error: $('#reset-errors'),
        ul_newPassError: $('#newPass-errors'),
        btn_send_mobile_button: $('#reset-button'),
        btn_send_newPass: $('#send-newPass-button'),
        btn_check_verify: $('#verify-button'),
        text_lostPassword: $('.lost_password'),
        tab_reset: $('[href="#tab-reset"]'),
        tab_verify: $('[href="#tab-verify"]'),
        tab_newPass: $('[href="#tab-change-password"]'),
        input_mobile: $('#reset-mobile'),
        input_code: $('#verify-code'),
        input_pass1: $('#newPass1'),
        input_pass2: $('#newPass2'),
    }

    init() {
        //اجرای سربرگ ریست پسورد پس از کلیک روی
        this.triggers.init(this);

    }

    urls = {
        checkMobile: '',
        verifyCode: '',
        setPassword:'',
    };

    messages;

    triggers = {
        init: function (app) {
            app.triggers.lost_password(app);
            app.triggers.send_mobile(app);
            app.triggers.checkCode(app);
            app.triggers.newPass_send(app);
        },
        lost_password: function (app) {
            app.elements.text_lostPassword.off();
            app.elements.text_lostPassword.click((e) => {
                e.preventDefault();
                app.elements.tab_reset.click();
            });
        },
        send_mobile: function (app) {
            app.elements.btn_send_mobile_button.click((e) => {

                var val = app.elements.input_mobile.val();//ورودی شماره موبایل
                if (val.length > 0) {
                    $.ajax(
                        {
                            url: app.urls.checkMobile,
                            data: {mobile: val},
                            method: 'post',
                            success: function (res) {

                                if (res.status === 'success' || res.status === 'register') {
                                    app.elements.tab_verify.click();
                                    app.error.hide(app);
                                } else {
                                    app.error.show(res.message, app);
                                }
                            }
                        }
                    );
                }
            })
        },
        checkCode: function (app) {

            app.elements.btn_check_verify.off();
            app.elements.btn_check_verify.click((e)=>{
                var data = {
                    code: app.elements.input_code.val(),
                    mobile: app.elements.input_mobile.val()
                };
                e.preventDefault();
                $.ajax
                (
                    {
                        url: app.urls.verifyCode,
                        method: 'post',
                        data: data
                    }
                )
                    .done(function (data) {
                        if (data.status === 'verified') {
                            app.elements.tab_newPass.click();
                        } else {

                        }
                    })
                    .fail(function (e) {

                    });
            });
        },
        newPass_send: function (app) {
            app.elements.btn_send_newPass.off();
            app.elements.btn_send_newPass.click((e) => {
                    e.preventDefault();
                    if (app.verify.pass1(app) && app.verify.pass2(app)) {
                            var data={
                                mobile:app.elements.input_mobile.val(),
                                newpass:app.elements.input_pass1.val()
                            };
                                $.ajax
                                    (
                                        {
                                          url: app.urls.setPassword,
                                          method:'post',
                                          data:data
                                        }
                                    )
                                  .done(function( data ) {
                                      location.reload();
                                  })
                                  .fail(function(e) {

                                  });

                    }
                }
            );
        }
    }


    verify = {
        pass1: function (app) {
            app.error.newPass.hide(app);
            var val = app.elements.input_pass1.val();
            if (val.length < 8) {
                app.error.newPass.show('رمز عبور انتخابی باید حداقل ۸ رقم باشد', app);
                return false;
            }

            return true;
        },
        pass2: function (app) {
            var val = app.elements.input_pass2.val();
            if (val.length < 8) {
                app.error.newPass.show('رمز عبور انتخابی باید حداقل ۸ رقم باشد', app);
                return false;
            }
            if (app.elements.input_pass2.val() != app.elements.input_pass1.val()) {
                app.error.newPass.show('رمز های عبور انتخابی با هم یکی نیستند - لطفا در هر دو ورودی یک رمز را وارد کنید', app);
                return false;
            }
            return true;
        }
    }

    error = {
        newPass: {
            show: function (message, app) {
                app.elements.ul_newPassError.html(`
        <li>` + message + `</li>
        `);
            },
            hide: function (app) {
                app.elements.ul_newPassError.empty()
            }
        },
        show: function (message, app) {
            app.elements.ul_error.html(`
        <li>` + message + `</li>
        `);
        },
        hide: function (app) {
            app.elements.ul_error.empty()
        }
    }


}

