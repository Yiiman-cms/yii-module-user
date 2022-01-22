new class {
    btn_logout;

    constructor() {
        this.init();
    }

    init() {
        this.init_parameters();
        this.init_triggers();
    }

    init_triggers() {
        this.trigger_logout_btn();
    }

    init_parameters() {
        this.btn_logout=$('#btn-logout');
    }


    trigger_logout_btn(){
        var app=this;
        this.btn_logout.off();
        this.btn_logout.click(function(e){
            e.preventDefault();
            app.ajax(
                logoutUrl,
                {status:"logout"}
                );
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
                alert(response.message)
                break;
            case 'errors':
                $.each(response.messages, function (index, value) {
                    app.addError(value);
                })
                break;
            case 'success':
                location.reload();
        }
    }

}
