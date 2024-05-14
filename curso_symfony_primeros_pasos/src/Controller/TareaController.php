<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TareaRepository;
use App\Entity\Tarea;

class TareaController extends AbstractController
{
    /**
     * @Route("/", name="app_listado_tarea")
     */
    public function listado(TareaRepository $tareaRepository): Response
    {
        $tareas = $tareaRepository->findAll();
        return $this->render('tarea/listado.html.twig', [
            'tareas' => $tareas,
        ]);
    }
    /**
     * @Route("/tarea/crear", name="app_crear_tarea")
     */
    public function crear(Request $request)
    {
        $tarea = new Tarea();
        $descripcion = $request->request->get('descripcion', null);
        if (null !== $descripcion) {
            if (!empty($descripcion)) {
                $em = $this->getDoctrine()->getManager();
                $tarea->setDescripcion($descripcion);
                $em->persist($tarea);
                $em->flush();
                $this->addFlash('success', 'Tarea creada correctamente!');
                return $this->redirectToRoute('app_listado_tarea');
            }
            else {
                $this->addFlash('warning', 'El campo descripción es obligatorio');
            }
        }
        return $this->render('tarea/crear.html.twig', [
            'tarea' => $tarea
        ]);
    }

    /**
     * @Route("/editar-tarea/{id}",name="app_editar_tarea")
     *      
     *      
     *      
     * 
     */
    public function editar(int $id, TareaRepository $tareaRepository, Request $request): Response
    {
        // $tarea = $tareaRepository->find($id);
        $tarea = $tareaRepository->findOneById($id);
        if (null === $tarea) {
            throw  $this->createNotFoundException();
        }
        $descripcion = $request->request->get('descripcion', null);
        if (null !== $descripcion) {
            if (!empty($descripcion)) {
                $em = $this->getDoctrine()->getManager();
                $tarea->setDescripcion($descripcion);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Tarea editada correctamente!'
                );
                return $this->redirectToRoute('app_listado_tarea');
            } else {
                $this->addFlash(
                    'warning',
                    'El campo "Descripción" es obligatorio'
                );
            }
        }
        return $this->render('tarea/editar.html.twig', [
            'tarea' => $tarea,
        ]);
    }

    /**
     * @Route("/tarea/eliminar/{id}", name="app_eliminar_tarea")
     */
    public function eliminar(int $id)
    {
        return $this->render('tarea/index.html.twig', [
            'controller_name' => 'TareaController',
        ]);
    }

    /**
     * @Route("/tarea/editar-params/{id}", 
     * name="app_editar_tarea_con_params_convert",
     * requirements={"id"="\d+"})
     */
    public function editarConParamasConvert(Tarea $tarea, Request $request)
    {
        $descripcion = $request->request->get('descripcion', null);

        if (null !== $descripcion) {
            if (!empty($descripcion)) {
                $em = $this->getDoctrine()->getManager();
                $tarea->setDescripcion($descripcion);
                $em->persist($tarea);
                $em->flush();
                $this->addFlash('success', 'Tarea editada correctamente!');
                return $this->redirectToRoute('app_listado_tarea');
            }
            else {
                $this->addFlash('warning', 'El campo descripción es obligatorio');
            }
        }
        return $this->render('tarea/editar.html.twig', [
            'tarea' => $tarea
        ]);
    }
}
