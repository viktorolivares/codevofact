<?php

namespace App\Http\ViewComposers\Tenant;

use App\Models\Tenant\Configuration;

class CompactSidebarViewComposer
{
    public function compose($view)
    {
    	$configuration = Configuration::first();
        $view->show_ws = $configuration->enable_whatsapp;
        $view->phone_whatsapp = $configuration->phone_whatsapp;
        $view->vc_compact_sidebar = $configuration;
    }
}
