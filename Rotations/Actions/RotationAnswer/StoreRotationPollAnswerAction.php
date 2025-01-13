<?php

namespace App\Modules\Rotations\Actions\RotationAnswer;


use App\Modules\Rotations\Models\RotationPeriod;
use App\Modules\Rotations\Models\UserRotation;
use DB;
use Illuminate\Http\Request;

class StoreRotationPollAnswerAction
{
    public function execute(Request $request): void
    {
        $periodId = RotationPeriod::select(['id'])
            ->where('rotation_id', $request->rotation_id)
            ->orderByDesc('end_date')
            ->first()
            ->id;

        $userRotation = new UserRotation();
        $userRotation->user_id = auth()->id();
        $userRotation->rotation_period_id = $periodId;
        $userRotation->status = 'new';
        $userRotation->save();

        $rotationPollAnswers = array_map(function ($poll) use ($userRotation) {
            return [
                'poll_id'          => $poll['poll_id'],
                'answer'           => json_encode($poll['answer']),
                'created_at'       => now()->toDateTimeString(),
                'user_rotation_id' => $userRotation->id,
            ];
        }, $request->polls);

        DB::table('rotation_poll_answers')->insert($rotationPollAnswers);
    }
}
