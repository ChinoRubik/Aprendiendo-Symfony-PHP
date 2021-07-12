<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Animal;


//PARA CREAR CAMPOS DE FORMULARIO, QUE YA NO SE OCUPA, LA SECCION FUE PASADA AL FOLDER form/animalType.php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

//MI SESSION 
use Symfony\Component\HttpFoundation\Session\Session;

//IMPORTO MI FORMULARIO QUE EXPORTE
use App\Form\AnimalType;

class AnimalController extends AbstractController
{   


    public function crearAnimal(Request $request){
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class,$animal);
                    // //  ->setAction($this->generateUrl('animal_save'))
                    //  ->setMethod('POST')
                    //  ->add('tipo',TextType::class,[
                    //      'label' => 'Tipo de animal'
                    //  ])
                    //  ->add('color',TextType::class)
                    //  ->add('raza',TextType::class)
                    //  ->add('submit',SubmitType::class,[
                    //     'label' => 'Enviar',
                    //     'attr' => [ 'class' => 'buton']
                    //  ])
                    // ->getForm();

        //=========================================================
        //YA POR DEFECTO SYMFONY ME GUARDA TODO, YA ESTA ASIGANADO A CADA VALOR
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($animal);
            $em->flush();

            //SESSION
            $session = new Session();
            // $session->start();
            $session->getFlashBag()->add('message','Animal creado exitosamente');


            return $this->redirect('crear-animal');
        }

        //==========================================================
        
        return $this->render('animal/crear-animal.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    public function index(): Response{
        //Obeteniendo todos mis registros de la base de datos para mostrarlos en la vista
        $entityManager = $this->getDoctrine()->getRepository(Animal::class);
        //Obetengo a todos mis animales para presentarlos en la vista
        $animales = $entityManager->findAll();

        //Si quiero sacar mis objetos de la BD con una condicion
        $animal = $entityManager->findBy([
            'raza' => 'Chihuahueno'
        ],[
            'id' => 'desc'
        ]);
        //var_dump($animal);

        
        // ===================================================================
        //              QUERY BUILDER
        // EL QUERY BUILDER NOS AYUDA A PODER CREAR CONSULTAS QUE SON COMPLEJAS 
        $qb = $entityManager->createQueryBuilder('a')
                            //  ->andWhere("a.raza = 'Cascabel'")
                            ->orderBy('a.id','desc')
                            ->getQuery();
                    
        $resultset = $qb->execute();
        var_dump($resultset);
        var_dump("===========================================================================");
        // ======================================================================
        //REPOSITORY
        $RepositoryAnimals=$entityManager->exampleRepository('DESC');
        var_dump($RepositoryAnimals);

        // =====================================================================

        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
            'animales' => $animales
        ]);
    }

    public function save(): Response{
        //Guarda en mi Base de datos

        $entityManager = $this->getDoctrine()->getManager();

        //Creando el objeto
        $animal = new Animal();
        $animal->setTipo('Perro');
        $animal->setRaza('Chihuahueno');
        $animal->setColor('Negro');

        //Guardando en mi base de datos
        $entityManager->persist($animal);
        $entityManager->flush();


        return new Response('El animal que estas guardando en la BD es '.$animal->getId());
    }

    public function findAnimal($id): Response{
        //Saca de mi base de datos
        $entityManager = $this->getDoctrine()->getRepository(Animal::class);

        //Consigo mi animal
        $animal = $entityManager->find($id);

        //Corroboro si existe
        if($animal){
            $message = "Tu animal es de raza ".$animal->getRaza(). " y es color ".$animal->getColor();
        }else{
            $message ="No existe el ID ".$id." en la DB ";
        }
        return new Response($message);
    }

    public function updateAnimal($id): Response{
        //Actualizar en mi base de datos
        $em = $this->getDoctrine()->getManager();
        $entityManager = $this->getDoctrine()->getRepository(Animal::class);
        
        //Consigo a mi animal de la DB
        $animal = $entityManager->find($id);

        //Corroboro si existe
        if($animal){

            $animal->setColor("Rojo");
            //Guardando
            $em->persist($animal);
            $em->flush();
            $message = "Animal con $id actualizado correctamente";
        }else{
            $message = "No existe el ID ".$id;
        }
        return new Response($message);
    }

    public function deleteAnimal($id) : Response{

        //Eliminando de la base de datos 
        $em = $this->getDoctrine()->getManager();
        $entityManager = $this->getDoctrine()->getRepository(Animal::class);
        $animal = $entityManager->find($id);
        
        //Corroborando si me llego animal
        if($animal){

            //Eliminando
            $em->remove($animal);
            $em->flush();
            $message = "Se ha eliminado correctamente el ID ".$id;
        }else{
            $message = "No existe el ID ".$id;
        }
        return new Response ($message);
    }
}
