<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Ui\Admin\Action;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class HomeAction
{
    /** @var RouterInterface */
    private $router;

    /**
     * HomeAction constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return RedirectResponse
     */
    public function __invoke() : RedirectResponse
    {
        return new RedirectResponse($this->router->generate('admin_institution_list'));
    }
}
