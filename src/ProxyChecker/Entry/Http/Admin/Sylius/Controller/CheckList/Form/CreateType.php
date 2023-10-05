<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Sylius\Controller\CheckList\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add('payload', TextareaType::class, [
                'required' => true,
                'label' => 'app.admin.ui.modules.proxy_checker.check_list.properties.payload',
                'constraints' => [
                    new NotBlank(allowNull: false),
                ],
            ])
        ;
    }
}
