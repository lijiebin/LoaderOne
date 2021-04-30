<?php

namespace Application\Controller;

use ThirdOne\One;
use ThirdTwo\Two;
use Application\Model\UserModel;
use ThirdThree\Three;

class IndexController
{
    public function __construct()
    {
        new UserModel();
        
        new One();
        
        new Two();
        
        new Three();
    }
}