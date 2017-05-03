<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */

    public function indexAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Product');

        $query = $repository->createQueryBuilder('p')
            ->where('p.quantity > :quantity')
            ->setParameter('quantity', '0')
            ->getQuery();
        $products = $query->getResult();

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate($products, $request->query->getInt('page',1),6);
        $max_promotion = $this->getDoctrine()->getRepository('AppBundle:Promotions')->fetchBiggestPromotion();
        $calc = $this->get('price_calculator');

        return $this->render('product/index.html.twig', array(
            'categories' => $categories,'pagination'=> $pagination,'promotion'=>$max_promotion,"calc"=>$calc
        ));
    }

    /**
     * @Route("/qwe{category}", name="category_list")
     * @Method("GET")
     */

    public function categoryAction(Request $request,Category $category)
    {
        $categories = $this->getDoctrine()->getRepository("AppBundle:Category")->findAll();
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Product');

        $query = $repository->createQueryBuilder('p')
            ->where('p.quantity > :quantity')
            ->andWhere('p.category = :category')
            ->setParameter('quantity', '0')
            ->setParameter('category', $category->getId())
//            ->orderBy('p.price', 'ASC')
            ->getQuery();
        $products = $query->getResult();

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate($query, $request->query->getInt('page',1),6);
        return $this->render(':product:index.html.twig', array(
            'categories' => $categories,'pagination'=> $pagination,
        ));
    }
}
