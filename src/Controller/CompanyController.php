<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Company;
use App\Form\Type\CompanyFormType;

// Include JSON Response
use Symfony\Component\HttpFoundation\JsonResponse;

class CompanyController extends AbstractController {
      /**
     * @Route("/company/add", name="add-company")
     */
      public function index() {
          $company = new Company();
          $form = $this->createForm(CompanyFormType::class, $company);
          return $this->render('company/add.html.twig', [
              'form' => $form->createView(),
          ]);
      }

    /**
     * Returns a JSON string with the cities of the Postal Code with the providen id.
     *
     * @Route("/company/list", name="list-company")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listCitiesOfPostalCode(Request $request) {
        // Get Entity manager and repository
        $em = $this->getDoctrine()->getManager();
        $citiesRepository = $em->getRepository("App:City");

        // Search the cities that belongs to the city with the given id as GET parameter "postal_code_id"
        $cities = $citiesRepository->createQueryBuilder("q")
            ->where("q.postal_code = :postal_code_id")
            ->setParameter("postal_code_id", $request->query->get("postal_code_id"))
            ->getQuery()
            ->getResult();

        // Serialize into an array the data that we need, in this case only name and id
        // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
        $responseArray = array();
        foreach($cities as $city){
            $responseArray[] = array(
                "id" => $city->getId(),
                "name" => $city->getName()
            );
        }

        // Return array with structure of the cities of the providen city id
        return new JsonResponse($responseArray);

        // e.g
        // [{"id":"3","name":"Treasure Island"},{"id":"4","name":"Presidio of San Francisco"}]
    }
}
