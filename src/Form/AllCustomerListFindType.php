<?php

declare(strict_types=1);
namespace App\Form\Mst;

use App\Entity\Organization;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * 顧客一覧画面のForm
 */
class AllCustomerListFindType extends AbstractType
{
    /**
     * Formを組み立てる。
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', TextType::class)
            ->add('nameLast', TextType::class)
            ->add('nameFirst', TextType::class)
            ->add('nameLastKana', TextType::class)
            ->add('nameFirstKana', TextType::class)
            ->add('corporateName', TextType::class)
            ->add('contactEmail', TextType::class)
            ->add('tel', TextType::class)
            ->add('orderTimesFrom', NumberType::class)
            ->add('orderTimesTo', NumberType::class)
            ->add('priceFrom', NumberType::class)
            ->add('priceTo', NumberType::class)
        ;
    }

    /**
     * オプションを設定する。
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'mapped' => false,
            'required' => false,
            'method' => 'get',
            'csrf_protection' => false,
        ]);
    }
}
