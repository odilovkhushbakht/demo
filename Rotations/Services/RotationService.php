<?php

namespace App\Modules\Rotations\Services;

use App\Acme\Services\BaseService;
use App\Modules\Rotations\Actions\Rotation\RotationDestroyAction;
use App\Modules\Rotations\Actions\Rotation\RotationNotInterestedCreateAction;
use App\Modules\Rotations\Actions\Rotation\RotationStoreAction;
use App\Modules\Rotations\Actions\Rotation\RotationUpdateAction;
use App\Modules\Rotations\Actions\RotationAnswer\StoreRotationPollAnswerAction;
use App\Modules\Rotations\Actions\RotationManager\RotationManagersSyncAction;
use App\Modules\Rotations\Actions\RotationPeriod\RotationPeriodStoreAction;
use App\Modules\Rotations\Actions\RotationPeriod\RotationPeriodUpdateAction;
use App\Modules\Rotations\Models\Rotation;
use DB;
use Illuminate\Http\JsonResponse;
use Throwable;

class RotationService extends BaseService
{
    /**
     * @throws Throwable
     */
    public function create(): Rotation
    {
        return DB::transaction(function () {
            $rotation = (new RotationStoreAction())->execute($this->request);
            (new RotationPeriodStoreAction())->execute($this->request, $rotation);
            (new RotationManagersSyncAction())->execute($rotation, $this->request->input('manager_ids'));

            return $rotation;
        });
    }

    /**
     * @throws Throwable
     */
    public function update(Rotation $rotation): Rotation
    {
        return DB::transaction(function () use ($rotation) {
            $rotation = (new RotationUpdateAction())->execute($this->request, $rotation);
            (new RotationPeriodUpdateAction())->execute($this->request, $rotation);
            (new RotationManagersSyncAction())->execute($rotation, $this->request->input('manager_ids'));

            return $rotation;
        });
    }

    /**
     * @throws Throwable
     */
    public function destroy(Rotation $rotation): JsonResponse
    {
        return (new RotationDestroyAction())->execute($rotation);
    }

    public function apply(): JsonResponse
    {
        (new StoreRotationPollAnswerAction())->execute($this->request);

        return response()->json(['message' => 'Успешно сохранено!']);
    }

    /**
     * @throws Throwable
     */
    public function notInterestedCreate(): JsonResponse
    {
        (new RotationNotInterestedCreateAction())->execute($this->request);

        return response()->json(['message' => 'Успешно сохранено!']);
    }
}
