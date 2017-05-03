<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoginController extends Controller
{

    /**
     * @Route("/login", name="our_login")
     */
    public function login(){

//        $name = $this->get('session')->set('user', )
//        $this->get('session')->getFlashBag()->add('success', ' you have login successfully!');
        return $this->render('security/login.html.twig');

    }
    /**
     * @Route("/logout", name="logout")
     *
     */
    public function logout(){
//        $this->get('session')->getFlashBag()->add('success', ' Logout is successfully!');

        $this->get('session')->getFlashBag()->add('success','Logout successfull!');
    }


}
