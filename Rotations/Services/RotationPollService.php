<?php

namespace App\Modules\Rotations\Services;

use App\Acme\Services\BaseService;
use App\Modules\Rotations\Actions\Rotation\RotationDestroyAction;
use App\Modules\Rotations\Actions\Rotation\RotationNotInterestedCreateAction;
use App\Modules\Rotations\Actions\RotationAnswer\StoreRotationPollAnswerAction;
use App\Modules\Rotations\Models\Rotation;
use App\Modules\Rotations\Models\RotationPoll;
use Illuminate\Http\JsonResponse;
use Throwable;

class RotationPollService extends BaseService
{
    /**
     * @throws Throwable
     */
    public function create(): RotationPoll
    {
        $poll = new RotationPoll();
        $poll->rotation_id = $this->request->input('rotation_id');
        $poll->label = $this->request->input('label');
        $poll->inputs = $this->request->input('inputs');
        $poll->required = $this->request->input('required');
        $poll->save();

        return $poll;
    }

    /**
     * @throws Throwable
     */
    public function update(RotationPoll $poll): RotationPoll
    {
        $poll->label = $this->request->input('label');
        $poll->required = $this->request->input('required');
        $poll->inputs = $this->request->input('inputs');
        $poll->save();

        return $poll;
    }

    /**
     * @throws Throwable
     */
    public function destroy(RotationPoll $poll): JsonResponse
    {
        if ($poll->answers()->exists()) {
            abort(422, 'У данного вопроса есть ответы');
        }
        $poll->delete();
        return response()->json(['message' => 'Successfully deleted!']);
    }
}
