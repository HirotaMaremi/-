<?php

declare(strict_types=1);
namespace App\Controller\Mst;

use App\Controller\Common\CommonControllerTrait;
use App\Entity\CustomerMst;
use App\Entity\Design;
use App\Entity\DesignWorkEstimationRelation;
use App\Entity\Estimation;
use App\Entity\User;
use App\Form\Mst\CstmrMstEditType;
use App\Util\OptimisticLock;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 顧客マスタ編集画面のコントローラー
 *
 * @Route("/cstmr")
 */
class AllCustomerMstEditController extends AbstractController
{
    use CommonControllerTrait;

    /**
     * @Route("/edit/{id}", name="app_mst_cstmr_all_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param int $id
     * @param string $hash
     * @return Response
     */
    public function edit(Request $request, ?int $id): Response
    {
        $this->em->beginTransaction();

        $filter = $request->get('filter', []);
        /** @var CustomerMst $cm */
        $cm = $this->em->getRepository(CustomerMst::class)->find($id);
        if (!$cm) {
            throw new NotFoundHttpException();
        }
        $form = $this->createForm(CstmrMstEditType::class, $cm);
        $version = OptimisticLock::createVersionHash([$cm]);

        return $this->render('Mst/cstmrMstEdit.html.twig', [
            'cm' => $cm,
            'filter' => $filter,
            'form' => $form->createView(),
            'version' => $version,
            'isAllOrg' => true,
        ]);
    }
}
