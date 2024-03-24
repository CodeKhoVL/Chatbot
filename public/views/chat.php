<?php
require_once('../../config.php');
require_once('../../public/includes/header.php');
require_once('../../public/includes/navbar.php');
?>
<div class="custom-container">

    <div id="main-container" role="main" class="fw">
        <div class="col-xs-12 col-sm-12 content">

            <!-- Tin nổi bật - hight light -->
            <div class="sukien" style="padding-top: 5px; padding-bottom: 10px;">
                <div class="row" id="tin_new">
                    <div class="col-md-2">
                        <img class="hidden-xs" src="https://tuyensinh.hou.edu.vn/files/anhmoi/tinnoibat.png" class="img-responsive" alt="" style="float: left;width:120%;margin-top: 5px;">
                    </div>
                    <div class="col-md-10">
                        <p id="tinhot" style="float: left;margin-left: 1%;margin-top: 1.5%;line-height: 25px;">
                            <strong><a href="https://tuyensinh.hou.edu.vn/tintucchitiet/BT420/"> <b style="font-size: 25px;">Trường Đại học Mở Hà Nội công bố Đề án tuyển sinh năm 2024</b>
                                </a></strong>
                            <img src="https://tuyensinh.hou.edu.vn/files/anhmoi/hot.gif" style="padding-bottom: 5px; display: inline;">
                        </p>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-right: 0px;">
                <div class="class">
                    <div class="card" id="chatbox">
                        <header>
                            <h2>ChatBot</h2>
                        </header>

                        <ul class="chatbox" id="chatlog">
                            <li class="chat coming">
                                <p>Xin chào ! Nếu có câu hỏi nào liên quan đến trường, tuyển sinh vui lòng hỏi tại đâu nhé</p>
                            </li>
                        </ul>

                        <div class="chat-input">
                            <input onkeypress="handleKeyPress(event)" id="userInput" type="text" placeholder="Gửi tin nhắn. . ." required>
                            <button onclick="sendMessage()" id="sendButton" class="material-symbols-outlined">send</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php
    require_once('../../public/includes/footer.php');
    ?>