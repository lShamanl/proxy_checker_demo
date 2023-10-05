<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Sylius\Controller\CheckList\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add('id', HiddenType::class, [
                'label' => 'app.admin.ui.modules.proxy_checker.check_list.properties.id',
                'attr' => [
                    'readonly' => true,
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank(allowNull: false),
                ],
            ])
            ->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'empty_data' => '',
                'required' => true,
                'label' => 'app.admin.ui.modules.proxy_checker.check_list.properties.created_at',
                'constraints' => [
                    new NotBlank(allowNull: false),
                ],
            ])
            ->add('updatedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'empty_data' => '',
                'required' => true,
                'label' => 'app.admin.ui.modules.proxy_checker.check_list.properties.updated_at',
                'constraints' => [
                    new NotBlank(allowNull: false),
                ],
            ])
            ->add('endAt', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'empty_data' => '',
                'required' => false,
                'label' => 'app.admin.ui.modules.proxy_checker.check_list.properties.end_at',
                'constraints' => [
                ],
            ])
            ->add('payload', TextareaType::class, [
                'required' => true,
                'label' => 'app.admin.ui.modules.proxy_checker.check_list.properties.payload',
                'constraints' => [
                    new NotBlank(allowNull: false),
                ],
            ])
            ->add('allIteration', IntegerType::class, [
                'required' => false,
                'label' => 'app.admin.ui.modules.proxy_checker.check_list.properties.all_iteration',
                'constraints' => [
                ],
            ])
            ->add('successIteration', IntegerType::class, [
                'required' => false,
                'label' => 'app.admin.ui.modules.proxy_checker.check_list.properties.success_iteration',
                'constraints' => [
                ],
            ]);
    }
}
