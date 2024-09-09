<?php

class TestController
{
    public function show()
    {
        echo 'Đây là trang test ADMIN có ID = ' . $_GET['id'];
    }
}