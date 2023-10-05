<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Admin\Menu;

use App\Common\Entry\Http\Admin\Menu\SectionBuilderInterface;
use Knp\Menu\ItemInterface;

class AuthSectionBuilder implements SectionBuilderInterface
{
    public function build(ItemInterface $menu): void
    {
        $settings = $menu
            ->addChild('auth')
            ->setLabel('app.admin.ui.menu.main.auth.label');

        $settings
            ->addChild(
                'user',
                [
                    'route' => 'app_user_index',
                ]
            )
            ->setLabel('app.admin.ui.menu.main.auth.user.list')
            ->setLabelAttribute('icon', 'users');
    }

    public function getOrder(): int
    {
        return 10;
    }
}
