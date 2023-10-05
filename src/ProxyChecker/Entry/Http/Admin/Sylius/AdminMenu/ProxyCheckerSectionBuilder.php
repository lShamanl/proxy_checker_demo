<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Sylius\AdminMenu;

use App\Common\Entry\Http\Admin\Menu\SectionBuilderInterface;
use Knp\Menu\ItemInterface;

class ProxyCheckerSectionBuilder implements SectionBuilderInterface
{
    public function build(ItemInterface $menu): void
    {
        $settings = $menu
            ->addChild('proxy_checker')
            ->setLabel('app.admin.ui.menu.proxy_checker.label');

        $settings
            ->addChild('check_list', ['route' => 'app_proxy_checker.check_list_index'])
            ->setLabel('app.admin.ui.menu.proxy_checker.check_list.list')
            ->setLabelAttribute('icon', 'list');

        $settings
            ->addChild('proxy', ['route' => 'app_proxy_checker.proxy_index'])
            ->setLabel('app.admin.ui.menu.proxy_checker.proxy.list')
            ->setLabelAttribute('icon', 'list');
    }

    public function getOrder(): int
    {
        return 20;
    }
}
