<?php

declare(strict_types=1);
namespace App\Form\Mst;

use App\Entity\CommunicationMethod;
use App\Entity\Organization;
use App\Entity\Sex;
use App\Util\AuthManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * 顧客マスタ編集画面のForm
 */
class CstmrMstEditType extends AbstractType
{
    /** @var Organization */
    private $org;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * @required
     * @param AuthManager $authManager
     * @param EntityManagerInterface $em
     */
    public function _setInjection(AuthManager $authManager, EntityManagerInterface $em):void
    {
        $this->org = $authManager->getCurrentOrg();
        $this->em = $em;
    }

    /**
     * Formを組み立てる。
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameLast', TextType::class)
            ->add('nameFirst', TextType::class)
            ->add('nameLastKana', TextType::class)
            ->add('nameFirstKana', TextType::class)
            ->add('contactEmail', TextType::class)
            ->add('contactEmail2', TextType::class)
            ->add('contactEmail3', TextType::class)
            ->add('zipCode', TextType::class)
            ->add('prefecture', TextType::class)
            ->add('city', TextType::class)
            ->add('address1', TextType::class)
            ->add('address2', TextType::class)
            ->add('corporateName', TextType::class)
            ->add('corporateNameKana', TextType::class)
            ->add('departmentName', TextType::class)
            ->add('tel', TextType::class)
            ->add('mobilePhone', TextType::class)
            ->add('fax', TextType::class)
            ->add('paidDate', TextType::class)
            ->add('birthDay', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('sex', EntityType::class, [
                'class' => Sex::class,
                'choice_label' => 'ViewLabel',
                'choices' => $this->em->getRepository(Sex::class)->findAll(),
            ])
            ->add('isRegistered', CheckboxType::class)
            ->add('isValid', CheckboxType::class)
            ->add('remark', TextareaType::class)
            ->add('lastLogin', DateTimeType::class, [
                'widget' => 'single_text',
            ])
        ;
    }

    /**
     * オプションを設定する。
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => MasterWorkPos::class,
        ]);
    }
}
