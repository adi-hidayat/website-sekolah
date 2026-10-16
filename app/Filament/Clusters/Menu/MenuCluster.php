<?php

namespace App\Filament\Clusters\Menu;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class MenuCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Menu';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'menu';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;
}
