<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserProducts;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserProductsController extends Controller
{
    /**
     * @Route("/user/products", name="user_products_list")
     */
    public function UserProductsListAction(){
        $user = $this->getUser();
        $products = $this->getDoctrine()->getRepository('AppBundle:UserProducts')->findBy(['userId'=>$user->getId()]);
//
        return $this->render('user_products_list.html.twig',['products'=>$products]);
    }
}
