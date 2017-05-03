<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\CartProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class CartController extends Controller
{
//   /**
//    *
//    * @Route("/cartAdd/{id}", name="cart_add")
//    */
//   public function addToCartAction(Request $request, $id){
//       $maganer = $this->getDoctrine()->getManager();
//       $session = $this->get('session');
//       $user = $this->getUser();
//       $id_cart = $session->get('id_cart',false);
//       if(!$id_cart){
//           $cart = new Cart();
////           dump($user);exit;
//        $cart->setUserId($user->getId());
////           dump($user);exit;
//           $cart->setCreatedOn(new \DateTime());
//           $cart->setUpdatedOn(new \DateTime());
//
//            $maganer->persist($cart);
//            $maganer->flush();
//            $session->set('cart_id',$cart->getId());
//       }
//       $cart = $this->getDoctrine()->getRepository("AppBundle:Cart")->find($id_cart);
//
//       $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
//       if(!$product){
//           $cp = new CartProduct();
//           $cp->setCart($cart);
//           $cp->setProduct($product);
//           $cp->setQty(1);
//           $maganer->persist($cp);
//       }
//       $cart->setUpdatedOn(new \DateTime());
//       $maganer->persist($cart);
//       $maganer->flush();
//
//       return $this->render('cart_list.html.twig');
//   }
//

}
