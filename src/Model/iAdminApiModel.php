<?php

namespace Militer\mvcCore\Model;

interface iAdminApiModel extends iApiModel
{
    public function preferences(array $preferencesData);
    public function updateMainSitemap(array $sitemapData);
    public function updateAdminSitemap(array $sitemapData);


    public function test();
}
