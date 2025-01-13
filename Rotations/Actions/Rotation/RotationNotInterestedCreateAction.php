<?php

namespace App\Modules\Rotations\Actions\Rotation;

use App\Modules\Rotations\Models\UserNotInterestedRotation;
use Illuminate\Http\Request;

class RotationNotInterestedCreateAction
{
    /**
     * @throws \Throwable
     */
    public function execute(Request $request): void
    {
        $userNotInterestedRotation = new UserNotInterestedRotation();
        $userNotInterestedRotation->user_id = auth()->id();
        $userNotInterestedRotation->rotation_id = $request->rotation_id;
        $userNotInterestedRotation->save();
    }
}
