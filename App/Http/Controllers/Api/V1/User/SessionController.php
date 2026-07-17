<?php

namespace Modules\SupportChat\App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SupportChat\App\Actions\User\SessionDataAction;
use Modules\SupportChat\App\Http\Resources\UserSessionResource;

/**
 * HTTP API endpoint that returns aggregated session data for the authenticated user (v1).
 */
class SessionController extends Controller
{
    /**
     * Build the session payload.
     *
     * @param Request $request
     * @param SessionDataAction $action
     * @return UserSessionResource
     */
    public function __invoke(
        Request $request,
        SessionDataAction $action
    ): UserSessionResource {
        $sessionData = $action->execute($request->user()->id);

        return new UserSessionResource($sessionData);
    }
}
