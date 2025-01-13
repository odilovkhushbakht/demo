<?php

namespace App\Modules\Rotations\Actions\RotationPoll;

use App\Http\Requests\Rotations\Admin\RotationPoll\StoreRotationPollRequest;
use App\Modules\Rotations\Models\Rotation;
use App\Modules\Rotations\Models\RotationPoll;
use Illuminate\Support\Facades\DB;
use Throwable;

class StoreRotationPollAction
{
    /**
     * @param StoreRotationPollRequest $request
     * @return mixed
     * @throws Throwable
     */
    public function execute(StoreRotationPollRequest $request, Rotation $rotation): array
    {
         DB::transaction(function () use ($request, $rotation) {
            return array_map(function ($poll) use ($request, $rotation) {
                $rotation_poll = new RotationPoll();
                $rotation_poll->rotation_id = $rotation->id;
                $rotation_poll->label = $poll['label'];
                $rotation_poll->inputs = $poll['inputs'];
                $rotation_poll->required = $poll['required'];
                $rotation_poll->save();
                return $rotation_poll;
            }, $request->input('polls'));
        });
    }
}
