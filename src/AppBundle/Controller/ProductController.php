<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm('AppBundle\Form\ProductType', $product);
        $form->add('category',null, ['placeholder' => 'select category']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $product->setDate(new \DateTime());
            /** @var UploadedFile $file */
            $file = $product->getImage();

            $this->get('session')->getFlashBag()->add('success', ' Product is successfully created!');

            $fileName = md5($product->getName() . '' . $product->getDate()->format('Y-m-d'));
            $file->move($this->get('kernel')->getRootDir() . "/../web/images/project/" , $fileName);
            $product->setImage($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product)
    {
        if (!$this->isGranted('ROLE_ADMIN', $this->getUser()) &&
            !$this->isGranted('ROLE_MODERATOR', $this->getUser())
        ) {
            $this->get('session')->getFlashBag()->add('error', 'You are not authenticated to access this URL address');

            return $this->redirectToRoute('homepage');
        }
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('AppBundle\Form\ProductType', $product);
        $editForm->add("category");
//        $editForm->add('image', FileType::class,['data_class'=> null, 'required'=>false]);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if($product->getImage() instanceof UploadedFile){
                /** @var UploadedFile $file */
                $file = $product->getImage();
                $fileName = md5($product->getName() . '' . $product->getDate()->format('Y-m-d'));
                $file->move($this->get('kernel')->getRootDir() . "/../web/images/project/" , $fileName);
                $product->setImage($fileName);
            }
            $this->get('session')->getFlashBag()->add('success', ' Product is successfully edited!');

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product)
    {
        if (!$this->isGranted('ROLE_ADMIN', $this->getUser()) &&
            !$this->isGranted('ROLE_MODERATOR', $this->getUser())
        ) {
            $this->get('session')->getFlashBag()->add('error', 'You are not authenticated to access this URL address');

            return $this->redirectToRoute('homepage');
        }
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            $this->get('session')->getFlashBag()->add('success', ' Product is successfully deleted!');
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();

        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


}
