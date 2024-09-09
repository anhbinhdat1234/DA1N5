<?php

trait Logger
{
    public function log($message)
    {
        echo "Logging message: $message" . PHP_EOL;
    }
}

trait FileUploader
{
    public function upload($file)
    {
        echo "Uploading file: $file" . PHP_EOL;
    }
}

class User
{
    use Logger, FileUploader;

    public function createUser()
    {
        $this->log("User created");
        $this->upload("profile_picture.jpg");
    }
}

echo '<pre>';
$user = new User();
$user->createUser();
