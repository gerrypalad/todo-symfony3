<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Todo;

class TodoController extends Controller
{
    /**
     * @Route("/", name="todo_list")
     */
    public function listAction()
    {
        $todos = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->findAll();

        // view todos
        return $this->render('todo/index.html.twig',array(
            'todos'=>$todos
        ));
    }

    /**
     * @Route("/todo/create", name="todo_create")
     */
    public function createAction(Request $request)
    {
        $todo = new Todo();

        $form = $this->createFormBuilder($todo)
            ->add('name', TextType::class, array('attr'=> array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('category', TextType::class, array('attr'=> array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr'=> array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('priority', ChoiceType::class, array('choices'=>array('Low'=>'Low','Medium'=>'Medium','High'=>'High'),'attr'=> array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('due_date', DateTimeType::class, array('attr'=> array('class' => 'formcontrol', 'style' => 'margin-bottom:15px')))
            ->add('save', SubmitType::class, array('label' => 'Create ToDo','attr'=> array('class' => 'btn btn-primary btn-sm', 'style' => 'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //Get Data from Form
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $priority = $form['priority']->getData();
            $due_date = $form['due_date']->getData();

            $now = new\DateTime('now');

            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($due_date);
            $todo->setCreateDate($now);

            $em = $this->getDoctrine()->getManager();

            $em->persist($todo);
            $em->flush();
            
            $this->addFlash('notice','To Do ADDED' );
            
            return $this->redirectToRoute('todo_list');

        }

        // render the form
        return $this->render('todo/create.html.twig',array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/todo/edit/{id}", name="todo_edit")
     */
    public function editAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('todo/edit.html.twig');
    }

    /**
     * @Route("/todo/details/{id}", name="todo_details")
     */
    public function detailsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('todo/details.html.twig');
    }
}
