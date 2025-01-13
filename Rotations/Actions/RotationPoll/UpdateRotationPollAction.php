<?php

namespace App\Modules\Rotations\Actions\RotationPoll;

use App\Http\Requests\Rotations\Admin\RotationPoll\UpdateRotationPollRequest;
use App\Modules\Rotations\Models\RotationPoll;

class UpdateRotationPollAction
{
    public function execute(UpdateRotationPollRequest $request): RotationPoll
    {
        $questionnaire_poll = RotationPoll::findOrFail($request->input('poll_id'));
        $questionnaire_poll->rotation_id = $request->input('rotation_id');
        $questionnaire_poll->label = $request->input('label');
        $questionnaire_poll->required = $request->input('required');
        $questionnaire_poll->inputs = $request->input('inputs');
        $questionnaire_poll->save();

        return $questionnaire_poll;
    }
}
