<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Form\SearchType;
use App\Repository\EmployeeRepository;
use App\Services\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function searchEmployee(EmployeeRepository $employeeRepository, Request $request): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $firstname = $form->get('firstname')->getData();
            $lastname = $form->get('lastname')->getData();

            $employees = $employeeRepository->search($email, $firstname, $lastname);

            if (count($employees) == 0) {
                $this->addFlash('error', 'Not found!');
            } else {
                return $this->render('home/index.html.twig', ['form' => $form->createView(), 'employees' => $employees]);
            }
        };
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'employees' => $employeeRepository->findAll()
        ]);

    }

    #[Route('/employee/add', name: 'app_employee_add')]
    public function addEmployee(EmployeeRepository $employeeRepository, Request $request): Response
    {
        $employee = new Employee();
        // create form


        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** start of saving photo */
            $photo = $form->get('photo')->getData();
            if (!is_null($photo)) {
                $new_name_photo = uniqid() . '.' . $photo->guessExtension();
                $photo->move(
                    $this->getParameter('upload_dir'), // parameter defined in config/services.yaml
                    $new_name_photo
                );
                $employee->setPhoto($new_name_photo);
            } else {
                //set a default photo
                $employee->setPhoto('defaultImage.png');
            }
            /** end of saving photo */
            $employeeRepository->save($employee, true);
            return $this->redirectToRoute('app_home');
        }

        return $this->render('employee/newEmployee.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[Route('/employee/edit/{id}', name: "app_employee_edit", defaults: ["id" => null])]
    public function editEmployee($id, EmployeeRepository $employeeRepository, Request $request): Response
    {
        $old_photo_name = '';
        if (is_null($id)) {
            $employee = new Employee();
        } else {
            $employee = $employeeRepository->find($id);
            $old_photo_name = $employee->getPhoto();
        }

        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** start of saving image */

            $photo = $form->get("photo")->getData();
            if (!is_null($photo)) {
                $new_name_photo = uniqid() . '.' . $photo->guessExtension();
                $photo->move($this->getParameter('upload_dir'), $new_name_photo);

                $employee->setPhoto($new_name_photo);
                //verify the old photo file exist
                $old_photo_path = $this->getParameter("upload_dir") . $old_photo_name;

                if (file_exists($old_photo_path)) {
                    unlink($old_photo_path);
                }
            } else {
                $employee->setPhoto($old_photo_name);
            }
            /** end of saving image */
            $employeeRepository->save($employee, true);
            return $this->redirectToRoute('app_home');
        }

        return $this->render('employee/newEmployee.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee
        ]);

    }

    #[Route('/employee/{id}', name: "app_employee_one")]
    public function showEmployee($id, EmployeeRepository $employeeRepository): Response
    {
        $employee = $employeeRepository->find($id);
        return $this->render('employee/showEmployee.html.twig', [
            'employee' => $employee
        ]);
    }

    #[Route('/employee/remove/{id}', name: "app_employee_remove")]
    public function removeEmployee($id, EmployeeRepository $employeeRepository): Response
    {
        $employee = $employeeRepository->find($id);
        $employeeRepository->remove($employee, true);

        $photo_name = $employee->getPhoto();
        $photo_path = $this->getParameter('upload_dir') . $photo_name;
        $is_not_default = $photo_name !== 'defaultImage.png';

        if (file_exists($photo_path) && $is_not_default) {
            unlink($photo_path);
        }

        return $this->redirectToRoute('app_home');
    }
}
