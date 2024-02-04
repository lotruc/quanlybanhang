/**
 * show modal dialog confirm
 * @param {String} message message to confirm
 * @param {Function} preConfirmCallback function call back when confirm
 */
function showConfirmDialog(message, preConfirmCallback) {
    Swal.fire({
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Huỷ bỏ',
        confirmButtonText: 'Đồng ý',
        allowEnterKey: false,
        didOpen: function () {
            // Listen for keydown events on the modal
            document.addEventListener('keydown', keydownListener);
        },
        willClose: function () {
            // Remove the keydown event listener when the modal is closing
            document.removeEventListener('keydown', keydownListener);
        },
        preConfirm: preConfirmCallback
    });

    function keydownListener(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            preConfirmCallback();
            Swal.close();
        }
    }
}

/**
 * show success message
 * @param {String} mess
 * @param {Function} callback function call back when notification done
 */
function notiSuccess(mess = 'Thành công', position = 'top-end', callback = function () { }) {
    $('#alert-error').addClass('d-none');
    const Toast = Swal.mixin({
        toast: true,
        position: position,
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    Toast.fire({
        icon: 'success',
        title: mess
    }).then(() => {
        callback();
    });
}

/**
 * show error message
 * @param {String} mess
 */
function notiError(mess = "Đã xảy ra lỗi. Vui lòng thử lại.") {
    Swal.fire({
        icon: 'error',
        title: 'Lỗi',
        text: mess,
    });
};

/**
 * handle image
 * @param {Element} input
 * @param {Element} image
 */
function handleImageUpload(input, image) {
    if (input.files && input.files[0]) {
        $(image).attr('src', URL.createObjectURL(input.files[0]));
    }
}

/**
 * debounce function
 * @param {Function} func callback function
 * @param {Number} wait waiting time
 * @param {boolean} immediate 
 */
function debounce(func, wait, immediate) {
    var timeout;
    return function () {
        var context = this, args = arguments;
        var later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};