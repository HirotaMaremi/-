<?php

declare(strict_types=1);
namespace App\Controller\Mst;

use App\Controller\Common\CommonControllerTrait;
use App\Entity\CustomerMst;
use App\Entity\Department;
use App\Entity\IdentifiedCustomerRelation;
use App\Entity\User;
use App\Form\Mst\AllCustomerListFindType;
use App\Service\CustomerIntegrator;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 顧客一覧画面のコントローラー
 *
 * @Route("/cstmr")
 */
class AllCustomerListController extends AbstractController
{
    use CommonControllerTrait;

    private const LIST_ONE_PAGE_LIMIT = 20;

    /** @var CustomerIntegrator */
    private $integrator;

    /**
     * @required
     * @param CustomerIntegrator $integrator
     */
    public function _setInjection(CustomerIntegrator $integrator): void
    {
        $this->integrator = $integrator;
    }

    /**
     * @Route("/list", name="app_mst_cstmr_all_list", options={"expose"=true}, methods={"GET"})
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function top(Request $request, PaginatorInterface $paginator): Response
    {
        $findingForm = $this->createForm(AllCustomerListFindType::class);
        $findingForm->handleRequest($request);
        $filter = [];

        if ($findingForm->isSubmitted()) {
            $filter = $this->createFilterFromForm($findingForm);
        }

        $query = $this->em->getRepository(CustomerMst::class)->createFilterQuery($filter);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            self::LIST_ONE_PAGE_LIMIT,
            [
                'wrap-queries'=>true
            ]
        );

        return $this->render('Mst/allCstmrMstList.html.twig', [
            'findingForm' => $findingForm->createView(),
            'pagination' => $pagination,
            'filter' => $filter,
        ]);
    }

    private function createFilterFromForm(FormInterface $findingForm): array
    {
        return $findingForm->getData();
    }
}