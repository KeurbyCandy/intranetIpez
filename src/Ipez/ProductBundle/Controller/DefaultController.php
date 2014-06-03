<?php

namespace Ipez\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ipez\ProductBundle\Entity\Product;
use Ipez\ProductBundle\Entity\Feature;

use Doctrine\ORM;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $products = array();

        try {
            $products = $this->getDoctrine()
                             ->getRepository('IpezProductBundle:Product')
                             ->findAll();
        } catch (ORM\NoResultException $e) {
            return $this->render('IpezProductBundle:Default:index.html.twig', array(
                    'products' => $products
            ));
        }

        return $this->render('IpezProductBundle:Default:index.html.twig', array(
                'products' => $products
        ));
    }

    public function createAction()
    {
        $product = new Product();

        $request = $this->getRequest();

        if ($request->getMethod() == 'POST')
        {
            if ($this->get('request')->get('reference') !== null &&
                    $this->get('request')->get('tradeName') !== null &&
                    $this->get('request')->get('cI') !== null)
            {

                $productCaract = implode($this->get('request')->get('productCaract', array()), ':');
               
                $product->setReference($this->get('request')->get('reference'))
                        ->setTradeName($this->get('request')->get('tradeName'))
                        ->setCI($this->get('request')->get('cI'));

                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();

                return $this->redirect($this->generateUrl('ipez_product_homepage'));
            }
        }

        if (!$product)
        {
            return $this->redirect($this->generateUrl('ipez_product_homepage'));
        }
        
        return $this->render('IpezProductBundle:Default:create.html.twig', array(
        ));
    }

    public function addFeatureAction($id)
    {
        $feature = new Feature();
        
        $products = array();
        try {
            $products = $this->getDoctrine()
                             ->getRepository('IpezProductBundle:Product')
                             ->find($id);
        } catch (ORM\NoResultException $e) {
            return $this->render('IpezProductBundle:Default:index.html.twig', array(
                    'products' => $products
            ));
        }
        
        $features = array();
        try {
            $features = $this->getDoctrine()
                             ->getRepository('IpezProductBundle:Feature')
                             ->findAll();
        } catch (ORM\NoResultException $e) {
            return $this->render('IpezProductBundle:Default:index.html.twig', array(
                    'products' => $products
            ));
        }
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST')
        {
            if ($this->get('request')->get('featureName') !== null &&
                    $this->get('request')->get('featureValue') !== null)
            {
               
                $feature->setNameFeature($this->get('request')->get('featureName'))
                        ->setValue($this->get('request')->get('featureValue'));

                $em = $this->getDoctrine()->getManager();
                $em->persist($feature);
                $em->flush();

                return $this->redirect($this->generateUrl('ipez_product_homepage'));
            }
        }
        
        return $this->render('IpezProductBundle:Default:add_feature.html.twig', array(
            'product' => $products,
            '' => $feature
        ));
    }
    
    public function readAction()
    {
        return $this->render('IpezProductBundle:Default:read.html.twig');
    }

    public function updateAction()
    {
        return $this->render('IpezProductBundle:Default:update.html.twig');
    }

    public function deleteAction($id)
    {
        try {
            $product = $products = $this->getDoctrine()
                                    ->getRepository('IpezProductBundle:Product')
                                    ->find($id);
            
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        
        } catch (ORM\NoResultException $ex) {
            echo "alert('Aucun produit trouvé')";
            return $this->redirect($this->generateUrl('ipez_product_homepage'));
        }
        
        return $this->redirect($this->generateUrl('ipez_product_homepage'));
    }

}
