<?php

namespace Militer\mvcCore\Model\interfaces;

interface iAdminApiModel extends iApiModel
{
    public function preferences(array $preferencesData);
    public function updateMainSitemap(array $sitemapData);
    public function updateAdminSitemap(array $sitemapData);

    public function addMainNewPage(array $mainNewPageData);
    public function addAdminNewPage(array $adminNewPageData);
}
