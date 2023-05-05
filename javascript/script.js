function post_query(url, name, data) {

    var str = '';
    $.each( data.split('*+*'), function(k, v) {
        str += '&' + v + '=' + $('#' + v).val(); // "+" в javascript соединяет как "." в php. #email.val() -->  example@email.com
    });
    $.ajax({
        url : '/' + url,
        type: 'POST',
        data: name + '_f=1' + str,
        cache: false,
        success: function(result) {
            obj = jQuery.parseJSON(result);
            if (obj.go) {
                go(obj.go);
            }
            else {
                switch (obj.status) {
                    case 'warning':
                        Swal.fire(
                            'Внимание',
                            obj.message,
                            'warning'
                        ).then((result) => {
                            if (result.value == true || result.value == undefined) {
                                if (obj.close_u == true) {
                                    window.close();
                                }
                            }
                        });
                        break;
                    case 'error':
                        Swal.fire(
                            'Ошибка!',
                            obj.message,
                            'error'
                        ).then((result) => {
                            if (result.value == true || result.value == undefined) {
                                if (obj.close_u == true) {
                                    window.close();
                                }
                            }
                        });
                        break;
                    case 'info':
                        Swal.fire(
                            'Уведомление',
                            obj.message,
                            'info'
                        ).then((result) => {
                            if (result.value == true || result.value == undefined) {
                                if (obj.close_u == true) {
                                    window.close();
                                }
                            }
                        });
                        break;
                    case 'Question':
                        Swal.fire(
                            'Информация',
                            obj.message,
                            'question'
                        ).then((result) => {
                            if (result.value == true || result.value == undefined) {
                                if (obj.close_u == true) {
                                    window.close();
                                }
                            }
                        });
                        break;
                    default:
                        Swal.fire(
                            'Успешно',
                            obj.message,
                            'success'
                        ).then((result) => {
                            if (result.value == true || result.value == undefined) {
                                if (obj.url_u) {
                                    window.location.href = obj.url_u;
                                }
                            }
                        });
                        break;
                }

            }
        }
    });
}
function go(url) {
    window.location.href='/' + url;
}