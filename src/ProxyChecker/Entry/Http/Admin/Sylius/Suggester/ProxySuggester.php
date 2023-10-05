<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Sylius\Suggester;

use App\Common\Service\Suggester\SuggesterInterface;
use App\ProxyChecker\Domain\Proxy\Proxy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField(alias: self::SUGGESTER_NAME)]
class ProxySuggester extends AbstractType implements SuggesterInterface
{
    private const SUGGESTER_NAME = 'proxy_checker_proxy';

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Proxy::class,
            'choice_label' => 'ipProxy',
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }

    public function getSuggesterName(): string
    {
        return self::SUGGESTER_NAME;
    }
}
