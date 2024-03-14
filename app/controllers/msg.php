<?php
function msg_error3($text)
{
    return '<div class="alert alert-danger alert-dismissible alert-custom">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div>';
}
function msg_success3($text)
{
    return '<div class="alert alert-success alert-dismissible alert-custom">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div>';
}


function msg_success2($text)
{
    return die('<div class="alert alert-success alert-dismissible alert-custom ">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    <ul class="mb-0 pl-3"><li>' . $text . '</li></ul></div>');
}
function msg_error2($text)
{
    return die('<div class="alert alert-danger alert-dismissible alert-custom ">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    <ul class="mb-0 pl-3"><li>' . $text . '</li></ul></div>');
}
function msg_warning2($text)
{
    return die('<div class="alert alert-warning alert-dismissible alert-custom">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div>');
}
function msg_success($text, $url, $time)
{
    return die('<div class="alert alert-success alert-dismissible alert-custom">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div><script type="text/javascript">setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}
function msg_error($text, $url, $time)
{
    return die('<div class="alert alert-danger alert-dismissible alert-custom">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div><script type="text/javascript">setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}
function msg_warning($text, $url, $time)
{
    return die('<div class="alert alert-warning alert-dismissible alert-custom">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div><script type="text/javascript">setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}
function admin_msg_success($text, $url, $time)
{
    return die('<script type="text/javascript">Swal.fire("Thành Công", "' . $text . '","success");
    setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}
function admin_msg_error($text, $url, $time)
{
    return die('<script type="text/javascript">Swal.fire("Thất Bại", "' . $text . '","error");
    setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}
function admin_msg_error2($text)
{
    return die('<script type="text/javascript">Swal.fire("Thất Bại", "' . $text . '","error");</script>');
}
function ticket_error($text)
{
    return die('<script type="text/javascript">Swal.fire("Thất Bại", "' . $text . '","error");</script>');
}
function admin_msg_warning($text, $url, $time)
{
    return die('<script type="text/javascript">Swal.fire("Thông Báo", "' . $text . '","warning");
    setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}
function admin_msg_warning2($text)
{
    return die('<script type="text/javascript">Swal.fire("Thông Báo", "' . $text . '","warning");</script>');
}
function admin_toast($status, $msg,$href = '',$time = 1000)
{
    return die('<script type="text/javascript">

    toast_msg(' . $status . ', ' . $msg . ')
    setTimeout(function(){ location.href = "' . $href . '" },' . $time . ')
    </script>');
}
