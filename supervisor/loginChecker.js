$(function() {
    function session_checking() {
        $.post("sessionChecker.php", function (data) {
            if (data == "-1") {
                window.close();
                alert("Your session has been expired!");
                location.reload();
            }
        });
    }
    var validateSession = setInterval(session_checking, 20000);
});