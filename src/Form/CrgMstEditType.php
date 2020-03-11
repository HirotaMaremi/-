<?php

declare(strict_types=1);
namespace App\Form\Mst;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * 送料マスタ編集画面のForm
 */
class CrgMstEditType extends AbstractType
{
    /**
     * Formを組み立てる。
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prefecture', TextType::class)
            ->add('isPlain', CheckboxType::class, [
                'required' => false,
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
            'csrf_protection' => false,
        ]);
    }
}
