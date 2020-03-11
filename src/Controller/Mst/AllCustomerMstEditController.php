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
 * @Route("/mst/allCstmr")
 */
class AllCustomerMstEditController extends AbstractController
{
    use CommonControllerTrait;

    /**
     * @Route("/edit/{id}/{hash}", name="app_mst_cstmr_all_edit", methods={"GET", "POST"})
     * @Security("user.isGrantedRead(2023)")
     * @param Request $request
     * @param int $id
     * @param string $hash
     * @return Response
     */
    public function edit(Request $request, ?int $id, string $hash): Response
    {
        $this->em->beginTransaction();
        $this->checkGrantedWrite(2023, $request->getMethod(), false);

        $filter = $request->get('filter', []);
        /** @var CustomerMst $cm */
        $cm = $this->em->getRepository(CustomerMst::class)->findByIdHash($id, $hash);
        if (!$cm) {
            throw new NotFoundHttpException();
        }
        $registerer = $this->em->getRepository(User::class)->findById($cm->getCreatedBy());
        $estimations = $this->em->getRepository(Estimation::class)->findByCustomer($id);
        $designs = $this->em->getRepository(Design::class)->findByCustomer($id);

        //デザインの箇所で前回加工見積の情報を表示するためにデザイン・加工関連を取得
        $designWorkEstimationRelationMap = [];
        foreach ($designs as $d) {
            $designWorkEstimationRelationMap[$d->getId()] = $this->em->getRepository(DesignWorkEstimationRelation::class)->findLatestByDesign($d->getId());
        }

        $deposit = $cm->getDeposit();

        $form = $this->createForm(CstmrMstEditType::class, $cm);
        $version = OptimisticLock::createVersionHash([$cm]);

        return $this->render('Mst/cstmrMstEdit.html.twig', [
            'cm' => $cm,
            'registerer' => $registerer,
            'filter' => $filter,
            'form' => $form->createView(),
            'version' => $version,
            'isAllOrg' => true,
            'estimations' => $estimations,
            'designs' => $designs,
            'designWorkEstimationRelationMap' => $designWorkEstimationRelationMap,
            'deposit' => $deposit,
        ]);
    }
}
