<?php
/**
 * This file is subject to the terms and conditions defined in file
 * 'LICENSE.txt', which is part of this source code package.
 *
 * @copyright Prisma Group (C) 2014
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * Default route, attach to /
     * @var string
     */
    protected $defaultRoute;

    /**
     * Constructor.
     *
     * @param string $defaultRoute
     */
    public function __construct($defaultRoute)
    {
        $this->defaultRoute = $defaultRoute;
    }

    /**
     * If $config['default_route'] is set, redirect to this route.
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        if ($this->defaultRoute === '/') {
            return new ViewModel();
        }
        $this->redirect()->toUrl($this->defaultRoute);
    }
}
