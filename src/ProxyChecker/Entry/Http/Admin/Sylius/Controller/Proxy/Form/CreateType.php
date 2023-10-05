<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Sylius\Controller\Proxy\Form;

use App\ProxyChecker\Domain\Proxy\Enum\ProxyStatus;
use App\ProxyChecker\Domain\Proxy\Enum\ProxyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add('ipProxy', TextType::class, [
                'required' => true,
                'label' => 'app.admin.ui.modules.proxy_checker.proxy.properties.ip_proxy',
                'constraints' => [
                    new Length(max: 255),
                    new NotBlank(allowNull: false),
                ],
            ])
            ->add('ipReal', TextType::class, [
                'required' => true,
                'label' => 'app.admin.ui.modules.proxy_checker.proxy.properties.ip_real',
                'constraints' => [
                    new Length(max: 255),
                    new NotBlank(allowNull: false),
                ],
            ])
            ->add('port', IntegerType::class, [
                'required' => true,
                'label' => 'app.admin.ui.modules.proxy_checker.proxy.properties.port',
                'constraints' => [
                    new NotBlank(allowNull: false),
                ],
            ])
            ->add('proxyType', ChoiceType::class, [
                'required' => true,
                'label' => 'app.admin.ui.modules.proxy_checker.proxy.properties.proxy_type',
                'choices' => [
                    'app.admin.ui.modules.proxy_checker.proxy.enums.proxy_type.http' => ProxyType::Http,
                    'app.admin.ui.modules.proxy_checker.proxy.enums.proxy_type.socks' => ProxyType::Socks,
                    'app.admin.ui.modules.proxy_checker.proxy.enums.proxy_type.other' => ProxyType::Other,
                ],
                'constraints' => [
                    new Choice(choices: ProxyType::cases()),
                    new NotBlank(allowNull: false),
                ],
                'empty_data' => '',
                'autocomplete' => true,
            ])
            ->add('proxyStatus', ChoiceType::class, [
                'required' => true,
                'label' => 'app.admin.ui.modules.proxy_checker.proxy.properties.proxy_status',
                'choices' => [
                    'app.admin.ui.modules.proxy_checker.proxy.enums.proxy_status.work' => ProxyStatus::Work,
                    'app.admin.ui.modules.proxy_checker.proxy.enums.proxy_status.not_work' => ProxyStatus::NotWork,
                ],
                'constraints' => [
                    new Choice(choices: ProxyStatus::cases()),
                    new NotBlank(allowNull: false),
                ],
                'empty_data' => '',
                'autocomplete' => true,
            ])
            ->add('country', TextType::class, [
                'required' => false,
                'label' => 'app.admin.ui.modules.proxy_checker.proxy.properties.country',
                'constraints' => [
                    new Length(max: 255),
                ],
            ])
            ->add('region', TextType::class, [
                'required' => false,
                'label' => 'app.admin.ui.modules.proxy_checker.proxy.properties.region',
                'constraints' => [
                    new Length(max: 255),
                ],
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'label' => 'app.admin.ui.modules.proxy_checker.proxy.properties.city',
                'constraints' => [
                    new Length(max: 255),
                ],
            ])
            ->add('timeout', IntegerType::class, [
                'required' => false,
                'label' => 'app.admin.ui.modules.proxy_checker.proxy.properties.timeout',
                'constraints' => [
                ],
            ]);
    }
}
