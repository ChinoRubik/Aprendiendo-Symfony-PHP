<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomingController extends AbstractController
{
    #[Route('/homing', name: 'homing')]
    public function index(): Response
    {
        return $this->render('homing/index.html.twig', [
            'controller_name' => 'HomingController',
            'messaje' => 'Hola mundo desde Symfony'
        ]);
    }


    public function animales($nombre,$apellidos){
        $titulo = 'BIENVENIDO A LA PAGINA DE ANIMALES';
        $animales = array('Perro','Gato','Guacamaya','Tarantula');
        $avez = array(
        'tipo'=>'paloma',
        'color'=>'gris',
        'edad'=>'10');


        return $this->render('homing/animales.html.twig', [
            'title' => $titulo,
            'name' =>$nombre,
            'lastname' =>$apellidos,
            'animales' => $animales,
            'avez'=>$avez
        ]);
    }

    public function redirigir(){
        return $this->redirectToRoute('animales',[
            'nombre' => 'Este es el nombre'

        ]);
    }
}
