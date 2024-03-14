document.addEventListener('alpine:init', () => {
    // main section
    Alpine.data('scrollToTop', () => ({
        showTopButton: false,
        init() {
            window.onscroll = () => {
                this.scrollFunction();
            };
        },
        scrollFunction() {
            if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                this.showTopButton = true;
            } else {
                this.showTopButton = false;
            }
        },
        goToTop() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        },
    }));
    // theme customization
    Alpine.data('customizer', () => ({
        showCustomizer: false,
    }));
    // sidebar section
    Alpine.data('sidebar', () => ({
        init() {
            const selector = document.querySelector('.sidebar ul a[href="' + window.location.pathname + '"]');
            if (selector) {
                selector.classList.add('active');
                const ul = selector.closest('ul.sub-menu');
                if (ul) {
                    let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                    if (ele) {
                        ele = ele[0];
                        setTimeout(() => {
                            ele.click();
                        });
                    }
                }
            }
        },
    }));
    Alpine.data('header', () => ({
        init() {

        },
        notifications: [],
        messages: [],

    }));

});
document.addEventListener("alpine:init", () => {
    Alpine.data("modal", (initialOpenState = false) => ({
        open: initialOpenState,

        toggle() {
            this.open = !this.open;
        },
    }));
});

function toast_msg(status = 'success', msg, timer = 3000, position = 'top-end', showConfirmButton = false, padding = '2em') {
    if (timer < 3000) {
        timer = 3000;
    }
    let icon;
    if (status === 'warning') {
        // icon = 'warning';
        icon = '';
    } else {
        icon = status;
    }
    const toast = window.Swal.mixin({
        toast: true,
        position: position,
        showConfirmButton: showConfirmButton,
        timerProgressBar: true,
        timer: timer,
        customClass: {
            popup: `color-${status}`,

        },
        padding: padding,

    });
    toast.fire({
        icon: icon,
        title: msg,
    });
}

function clearMessageInput() {
    var inputField = document.querySelector('.text-field');
    inputField.value = "";
}

function clearSelectedFiles() {
    var imagePreviewsContainer = document.getElementById("imagePreviews");
    imagePreviewsContainer.innerHTML = "";
    var input = document.getElementById("image-upload-input");
    input.value = null;
    imagePreviewsContainer.style.display = "none";
}

function reload(page, id) {
    var domain = window.location.origin;
    var imagePreviewsContainer = document.getElementById("imagePreviews");
    clearSelectedFiles();
    clearMessageInput();
    var submitButton = document.getElementById('submit-button');
    submitButton.innerHTML = '<i class="far fa-thumbs-up"></i>';
    imagePreviewsContainer.innerHTML = "";
    $.ajax({
        url: domain + '/' + page + '/get_messages?conversationId=' + id,
        method: 'GET',
        success: function(data) {
            $('.chat-box').html(data);
            scrollToBottom();
        },
    });
}

function load(page) {
    page += (page.includes("?") ? "&" : "?") + "cache=" + Date.now();
    $.ajax({
        url: '/get/' + page,
        method: 'GET',
        success: function(data) {
            $('#content').html(data);
        },
        error: function() {
            alert('Không thể tải trang.');
        }
    });
}

function deload(page, id) {
    var domain = window.location.origin;
    var imagePreviewsContainer = document.getElementById("imagePreviews");
    clearSelectedFiles();
    clearMessageInput();
    imagePreviewsContainer.innerHTML = "";
    $.ajax({
        url: domain + '/' + page + '/get_messages?conversationId=' + id,
        method: 'GET',
        success: function(data) {
            $('.chat-box').html(data);
            //scrollToBottom();
        },
    });
}

function logout() {
    $(".list_toast").html('');
    $.ajax({
        url: "/api/auth/logout",
        type: "GET",
        dataType: "json",
        statusCode: {
            400: function() {
                toast_msg("error", "400 status code! user error");
            },
            500: function() {
                toast_msg("error", "500 status code! server error");
            },
        },
        success: (data) => {
            if (!data) {
                toast_msg("error", "400 status code! user error");
            } else {
                if (data.status == 'success') {
                    setInterval(() => {
                        location.reload();
                    }, 700);
                    console.log(data.status);
                }
                toast_msg(data.status, data.msg);

            }
        },
        error: function(request) {
            toast_msg("error", "server error");
        },
    });

}





//Form //
$(document).ready(function() {
    $(document).on("submit", "form[submit-ajax=duogxaolin]", function(e) {
        e.preventDefault();
        let _this = this;
        let url = $(_this).attr("action");
        let method = $(_this).attr("method");
        let enctype = "multipart/form-data";
        let href = $(_this).attr("href");
        let formData = new FormData(_this);
        let button = $(_this).find("button[type=submit]");
        if (button.attr("order")) {
            Swal.fire({
                title: "Xác Nhận!",
                text: button.attr("order"),
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Xác Nhận",
                cancelButtonText: "Thoát",
            }).then((result) => {
                if (result.isConfirmed) {
                    toast_msg('warning', ' <i class="fas fa-spinner fa-spin"></i> Đang xử lý! Vui Lòng Chờ... ', 15000, 'top', true);
                    submitForm(url, method, href, formData, button)
                } else {
                    toast_msg('error', 'Đã huỷ thao tác!');
                }
            })
        } else {
            submitForm(url, method, href, formData, button);
        }

    });
});

function submitForm(url, method, href, formData, button) {
    let textButton = button.html().trim();
    let setting = {
        type: method,
        url,
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        beforeSend: function() {

            button.prop("disabled", true).html(' <span class="animate-ping w-3 h-3 ltr:mr-4 rtl:ml-4 inline-block rounded-full bg-white"></span> Loading');
        },

        complete: function() {
            button.prop("disabled", false).html(textButton);
        },
        success: function(data) {
            button.prop("disabled", false).html(textButton);
            if (!data) {
                toast_msg("error", "400 status code! server error");
            } else {
                if (!data.time) {
                    time = 1000;
                } else {
                    time = data.time;
                }
                if (data.status === "success") {

                    if (!data.redirect) {
                        if (!data.href) {
                            setTimeout(function() {
                                window.location.reload();
                            }, time);
                        } else {
                            setTimeout(function() {
                                window.location.href = data.href;
                            }, time);
                        }
                    }
                    toast_msg(data.status, data.msg, time);
                } else {
                    let stt = data.status || 'error';
                    let msg = data.msg || 'Server error';
                    toast_msg(data.status, data.msg, time);
                }

            }
        },
        error: function(xhr, textStatus, errorThrown) {
            button.prop("disabled", false).html(textButton);
            let errorMessage = '';
            if (xhr.status == 500) {
                errorMessage = "Lỗi nội bộ máy chủ. Vui lòng thử lại sau.";
            } else if (xhr.status == 404) {
                errorMessage = "Không tìm thấy đường dẫn yêu cầu.";
            } else {
                errorMessage = "Đã xảy ra lỗi. Vui lòng thử lại sau.";
            }
            toast_msg('error', errorMessage);
        }
    };

    $.ajax(setting);
}

$(document).ready(function() {
    $(document).on("submit", "form[submit-ajax=duogxaolinchat]", function(e) {
        e.preventDefault();
        let _this = this;
        let url = $(_this).attr("action");
        let method = $(_this).attr("method");
        let enctype = "multipart/form-data";
        let href = $(_this).attr("href");
        let formData = new FormData(_this);
        let button = $(_this).find("button[type=submit]");
        if (button.attr("order")) {
            Swal.fire({
                title: "Xác Nhận!",
                text: button.attr("order"),
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Xác Nhận",
                cancelButtonText: "Thoát",
            }).then((result) => {
                if (result.isConfirmed) {
                    toast_msg('warning', ' <i class="fas fa-spinner fa-spin"></i> Đang xử lý! Vui Lòng Chờ... ', 15000, 'top', true);
                    submitForm2(url, method, href, formData, button)
                } else {
                    toast_msg('error', 'Đã huỷ thao tác!');
                }
            })
        } else {
            submitForm2(url, method, href, formData, button);
        }

    });
});

function submitForm2(url, method, href, formData, button) {
    let textButton = button.html().trim();
    let setting = {
        type: method,
        url,
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        beforeSend: function() {

            button.prop("disabled", true).html(' <span class="animate-ping w-3 h-3 ltr:mr-4 rtl:ml-4 inline-block rounded-full bg-primary"></span>');
        },

        complete: function() {
            button.prop("disabled", false).html(textButton);
        },
        success: function(data) {
            button.prop("disabled", false).html(textButton);
            if (!data) {
                toast_msg("error", "400 status code! server error");
            } else {
                if (!data.time) {
                    time = 1000;
                } else {
                    time = data.time;
                }
                if (data.status === "success") {

                    if (data.href) {
                        if (data.type === 'deload') {
                            deload(data.href, data.id);
                        } else if (data.type === 'reload') {
                            reload(data.href, data.id);
                        } else {
                            load(data.href);
                        }

                    }
                    toast_msg(data.status, data.msg, time);
                } else {
                    let stt = data.status || 'error';
                    let msg = data.msg || 'Server error';
                    toast_msg(data.status, data.msg, time);
                }

            }
        },
        error: function(xhr, textStatus, errorThrown) {
            button.prop("disabled", false).html(textButton);
            let errorMessage = '';
            if (xhr.status == 500) {
                errorMessage = "Lỗi nội bộ máy chủ. Vui lòng thử lại sau.";
            } else if (xhr.status == 404) {
                errorMessage = "Không tìm thấy đường dẫn yêu cầu.";
            } else {
                errorMessage = "Đã xảy ra lỗi. Vui lòng thử lại sau.";
            }
            toast_msg('error', errorMessage);
        }
    };

    $.ajax(setting);
}

//form//
function setcook(name, value, days) {
    var expires = '';
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = '; expires=' + date.toUTCString();
    }
    document.cookie = name + '=' + value + expires + '; path=/';
}


document.addEventListener("alpine:init", () => {
    Alpine.data("dropdown", (initialOpenState = false) => ({
        open: initialOpenState,

        toggle() {
            this.open = !this.open;
        },
    }));
});