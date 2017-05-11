<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\CartProduct;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CartController extends Controller
{
    /**
     * @Route("/cart/add/{id}", name="cart_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request,$id)
    {

        $manager = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);

        if($request->get('qty') > $product->getQuantity()){
            $this->addFlash("error", "There is no enought Quantity in stock!");

            return $this->redirectToRoute('product_show', ['id'=>$product->getId()]);
        }

        $product->setQuantity( $product->getQuantity() - $request->get('qty'));

        $id_cart = $session->get('id_cart', false);

        if (!$id_cart) {
            $cart = new Cart();
            $cart->setUserId($this->getUser());
            $cart->setDateCreated(new \DateTime());
            $cart->setDateUpdated(new \DateTime());

            $manager->persist($cart);
            $manager->flush();

            $session->set('id_cart', $cart->getId());
        }

        $cart = $this->getDoctrine()->getRepository('AppBundle:Cart')->find($session->get('id_cart', false));

            if ($product) {
                $cp = $this->getDoctrine()->getRepository('AppBundle:CartProduct')->findOneBy([
                    'cart' => $cart,
                    'product' => $product
                ]);

                if (!$cp) {
                    $cp = new CartProduct();
                    $cp->setCart($cart);
                    $cp->setProduct($product);
                    $cp->setQty($request->get('qty'));
                } else {
                    $cp->setQty($cp->getQty()  + $request->get('qty'));
                }


                $manager->persist($cp);
            }


        $cart->setDateUpdated(new \DateTime());

        $manager->persist($cart);

        $manager->flush();

        $this->addFlash("success", "The product was successfully added to your cart");

        return $this->redirectToRoute('cart_list');
    }


    /**
     * @Route("/cart/list", name="cart_list")
     */
    public function listAction()
    {
        $user = $this->getUser();

        $cart = $this->getDoctrine()
            ->getRepository('AppBundle:Cart')
            ->findOneBy(['userId' => $user->getId()]);
        $product = [];
        $cart_product = $this->getDoctrine()
            ->getRepository('AppBundle:CartProduct')
            ->findBy(['cart' => $cart->getId()]);
        $calc = $this->get('price_calculator');

        $max_promotion = $this->getDoctrine()->getRepository('AppBundle:Promotions')->fetchBiggestPromotion();

        return $this->render("cart/list.html.twig",
            [
                "cart" => $cart,
                "cart_product" => $cart_product,
                "user" => $user,
                "max_promotion" => $max_promotion,
                "calc" => $calc
            ]
        );
    }

    /**
     * @Route("/cart/remove/{id}", name="remove_cart_product_process")
     * @param CartProduct $cartProduct
     * @return Response
     */
    public function removeProduct(CartProduct $cartProduct)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($cartProduct);
        $em->flush();

        $this->addFlash("success", "The product was successfully removed form your cart");

        return $this->redirectToRoute("cart_list");
    }

    /**
     * @Route("/cart/checkout", name="cart_checkout")
     * @return Response
     */
    public function checkOutCart()
    {

        $this->addFlash("success", "Your order is being processed");

        return $this->render("cart/checkout.html.twig");


    }
}
