<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\EventDispatcher\Tests\Service;
use Doctrine\Common\Util\Debug;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class RegisterController extends Controller
{
//    /**
//     * @Route("/register", name="register_form")
//     * @Method("GET")
//     */
//    public function register(){
//        $form= $this->createForm(UserType::class);
//        return $this->render(':default:register.html.twig',['form'=>$form->createView()]);
//    }
    /**
     * @Route("/register", name="register_form_submit")
     */
    public function registerSubmit(\Symfony\Component\HttpFoundation\Request $request){
        $user = new User();
        $form= $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

//        $validator = $this->get('validator');
//        $errors = $validator->validate($user);
//        if (count($errors) > 0) {
//            return $this->render(':default:proba.html.twig', array(
//                'errors' => $errors,'form'=>$form->createView()));
//        }

            if($form->isSubmitted()) {
            $encoder = $this->container->get('security.password_encoder');
            $hashedPassword = $encoder->encodePassword($user,$user->getPassword());
                $userRole = $this->getDoctrine()->getRepository(Role::class)->findOneBy(['name'=>['ROLE_USER']]);
                $user->addRole($userRole);
                $user->setIsBlock(0);
            $user->setPassword($hashedPassword);
                $this->get('session')->getFlashBag()->add('success', ' You have registered successfully!');
                $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('product_show');

             }

        return $this->render(':default:register.html.twig',['form'=>$form->createView()]);
    }
}
