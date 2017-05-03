<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Promotions;
use AppBundle\Form\PromotionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PromotionsController extends Controller
{
    /**
     * @Route("/promotion/add", name="add_promotion_form")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addPromotion()
    {
        if (!$this->isGranted('ROLE_ADMIN', $this->getUser()) &&
            !$this->isGranted('ROLE_EDITOR', $this->getUser())
        ) {
            $this->get('session')->getFlashBag()->add('error', 'You are not authenticated to access this URL address');

            return $this->redirectToRoute('homepage');
        }
        $form = $this->createForm(PromotionType::class);
        return $this->render('promotion_add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/promotion/add", name="add_promotion_process")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addPromotionProcess(Request $request)
    {
        if (!$this->isGranted('ROLE_ADMIN', $this->getUser()) &&
            !$this->isGranted('ROLE_MODERATOR', $this->getUser())
        ) {
            $this->get('session')->getFlashBag()->add('error', 'You are not authenticated to access this URL address');

            return $this->redirectToRoute('homepage');
        }
        $promotion = new Promotions();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render('promotion_add.html.twig', ['form' => $form->createView()]);
    }

}
