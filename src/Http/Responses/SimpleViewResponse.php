<?php

declare(strict_types=1);

namespace Laravel\Fortify\Http\Responses;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Fortify\Contracts\ConfirmPasswordViewResponse;
use Laravel\Fortify\Contracts\LoginViewResponse;
use Laravel\Fortify\Contracts\RegisterViewResponse;
use Laravel\Fortify\Contracts\RequestPasswordResetLinkViewResponse;
use Laravel\Fortify\Contracts\ResetPasswordViewResponse;
use Laravel\Fortify\Contracts\TwoFactorChallengeViewResponse;
use Laravel\Fortify\Contracts\VerifyEmailViewResponse;
use Symfony\Component\HttpFoundation\Response;

class SimpleViewResponse implements
    LoginViewResponse,
    ResetPasswordViewResponse,
    RegisterViewResponse,
    RequestPasswordResetLinkViewResponse,
    TwoFactorChallengeViewResponse,
    VerifyEmailViewResponse,
    ConfirmPasswordViewResponse
{
    /**
     * The name of the view or the callable used to generate the view.
     *
     * @var callable|string
     */
    protected $view;

    /**
     * Create a new response instance.
     *
     * @param  callable|string  $view
     * @return void
     */
    public function __construct($view)
    {
        $this->view = $view;
    }

    /**
     * @param Request $request
     *
     * @return Application|Factory|mixed|Response|View
     */
    public function toResponse($request)
    {
        if (! \is_callable($this->view) || \is_string($this->view)) {
            return view($this->view, ['request' => $request]);
        }

        $response = \call_user_func($this->view, $request);

        if ($response instanceof Responsable) {
            return $response->toResponse($request);
        }

        return $response;
    }
}
