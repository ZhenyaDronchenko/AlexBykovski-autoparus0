<?php

namespace App\SiteMap;


interface SiteMapUrlProvider
{
    public function provide(string $requestFile): array;
    public function provideIndex(): array;
    public function provideSimple(string $type): array;
}