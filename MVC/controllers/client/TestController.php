<?php

class TestController
{
    public function show()
    {
        echo 'Đây là trang test CLIENT có ID = ' . $_GET['id'];
    }
}