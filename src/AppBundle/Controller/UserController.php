<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
   /**
    * @Route("/users", name="all_users_list")
    * @Security("has_role('ROLE_ADMIN')")

    */
    public function listAllUsersAction(){
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        return $this->render('default/list_all_users.html.twig',['users'=>$users]);
    }


    /**
     * @Route("/delete_user/{id}", name="delete_user")
      * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction($id)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);

            $this->get('session')->getFlashBag()->add('success', ' User is successfully deleted!');
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

        return $this->redirectToRoute('all_users_list');
    }
    /**
     * @Route("/ban_user/{id}", name="ban_user")
      * @Security("has_role('ROLE_ADMIN')")
     */
    public function banUserAction($id)
    {
        if (!$this->isGranted('ROLE_ADMIN', $this->getUser())
        ) {
            $this->get('session')->getFlashBag()->add('error', 'You are not authenticated to access this URL address');

            return $this->redirectToRoute('homepage');
        }
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $user->setIsBlock(1);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        return $this->redirectToRoute('all_users_list');
    }
    /**
     * @Route("/unBan_user/{id}", name="unBan_user")
      * @Security("has_role('ROLE_ADMIN')")
     */
    public function unBanUserAction($id)
    {
        if (!$this->isGranted('ROLE_ADMIN', $this->getUser())) {
            $this->get('session')->getFlashBag()->add('error', 'You are not authenticated to access this URL address');

            return $this->redirectToRoute('homepage');
        }
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $user->setIsBlock(0);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        return $this->redirectToRoute('all_users_list');
    }

    /**
     * @Route("/userProfile/{id}", name="user_profile_list")
     */
    public function userProfileAction($id){
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);

        return $this->render(':default:user_profile.html.twig',['user'=>$user]);
    }


}
